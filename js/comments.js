import toast, { TOAST_TYPES_ENUM } from "/js/toast.js"
/*
    <form onsubmit="postComment(this)">
        <div class="input_group">
            <label for="comment">Kommentera</label>
            <textarea min title="hello" placeholder="Besvara <?= strip_tags($t["username"]) ?>s tråd"></textarea>
        </div>

        <div class="starrow">
            <input type="submit" class="btn branding" />
        </div>
    </form>
*/

/**
 * 
 * @param {Error} err 
 */
function genErr(err) {
    toast("Något gick fel", `fel: ${err.message}`, { type: TOAST_TYPES_ENUM.ERROR })
}

/**
 * 
 * @param {number} date 
 */
function formatDate(date) {
    const d = new Date(date * 1000)
    return d.toLocaleDateString()
}


/**
 * skulle sälja min själ till djävulen för tillgång till next.js och typescript
 * @param {{ id: string, username: string, userid: number, created: number, text: string, html: string }} data 
 */
async function createComment(data, depth = 2, trueDepth = 0) {
    const div = document.createElement("div")
    div.classList = "comment stack gap-large"

    const toprow = document.createElement("div")
    toprow.classList = "row top"

    const hidebtn = document.createElement("button")
    hidebtn.classList = "hidebtn"
    hidebtn.innerText = "[ - ]"

    const un = document.createElement("p")
    un.classList = "username"
    un.innerText = data.username

    const op = document.createElement("b")
    op.classList = "op"
    op.innerText = " (TS)"

    if(threadDetails.userid == data.userid) {
        un.append(op)
    }

    const date = document.createElement("p")
    date.classList = "data"
    date.innerText = formatDate(data.created)

    toprow.append(hidebtn, un, date)

    const content = document.createElement("div")
    content.classList = "md ccontent"
    content.innerHTML = data.html

    const bottomrow = document.createElement("div")
    bottomrow.classList = "row bottom"

    const rbtn = document.createElement("button")
    rbtn.classList = "btn tetriary reply"

    const delbtn = document.createElement("button")
    delbtn.classList = "btn tetriary reply"
    delbtn.innerText = "radera"

    const txt = document.createElement("span")
    txt.innerText = "besvara"
    rbtn.append(txt)

    if(typeof currentUser != "undefined") {
        bottomrow.append(rbtn)
        if(data.userid == currentUser.id || currentUser.id == threadDetails.author) {
            bottomrow.append(delbtn)
        }
    }
    


    const fuckinAnswerDivWhatever = document.createElement("div")
    fuckinAnswerDivWhatever.classList = `replies stack gap-large level-${trueDepth % 5}`
    fuckinAnswerDivWhatever.id = data.id

    async function getChildren() {
        trueDepth += 1;
        const c = await getComments(data.id)

        for(const cm of c) {
            fuckinAnswerDivWhatever.append(await createComment(cm, depth - 1, trueDepth))
        }
    } 

    if(data.userid == "deleteduser") bottomrow.innerHTML = ""

    if(depth > 0) {
        getChildren()
    } else {
        const lmore = document.createElement("button")
        lmore.className = "btn tetriary reply stack gap-medium"
        lmore.innerText = "ladda fler svar"

        lmore.onclick = async () => {
            depth = 2;
            await getChildren();
            lmore.remove()
        }

        console.log(data)

        if(data.children != 1) {
            bottomrow.append(lmore)
        }
    }

    const rest = document.createElement("div")
    rest.classList = "stack gap-medium"

    hidebtn.onclick = () => {
        if(rest.classList.contains("hide")) {
            rest.classList.remove("hide")
            hidebtn.innerText = "[ - ]"
        } else {
            rest.classList.add("hide")
            hidebtn.innerText = "[ + ]"
        }
    }

    const ccs = document.createElement("div")
    ccs.classList = "stack gap-medium commentContainer"

    rbtn.onclick = () => {
        bindComment(ccs, data.id, true)
    }

    delbtn.onclick = () => {
        content.innerText = "[raderat]"
        un.innerText = "raderat"
        deleteComment(data.id)
    }
    
    rest.append(content, bottomrow, ccs, fuckinAnswerDivWhatever)

    div.append(toprow, rest)

    return div
}

/**
 * @param {{ id: string, username: string, created: number, text: string, html: string }} data 
 */
async function bindCommentElem(data, parent, prepend = false, scrollto = false) {
    const p = document.getElementById(parent)
    const c = await createComment(data)

    if(prepend)
        p.prepend(c)
    else 
        p.append(c)

    if(scrollto)
        c.scrollIntoView(false, { behavior: "smooth", block: "center" })
}

function getComments(parent) {
    return new Promise((resolve, reject) => {
        fetch(`/api/comments.php?parent=${parent}`)
        .then(r => {
            r.json()
                .then(data => {
                    if(data.code == 200) {
                        resolve(data.data)
                    } else {
                        toast(data.data.title, data.data.description, { type: TOAST_TYPES_ENUM.ERROR })
                    }
                })
            .catch(reject)
        })
        .catch(reject)
    })
}

function createCommentForm() {
    const f = document.createElement("form")
    const ig = document.createElement("div")
    const l = document.createElement("label")
    const ta = document.createElement("textarea")
    const sr = document.createElement("div")
    const sbmb = document.createElement("input")

    sbmb.type = "submit"
    sbmb.classList = "btn branding"
    sr.classList = "starrow"
    ta.minLength = 5
    ta.maxLength = 250
    ta.name = "comment"
    ta.placeholder = "skriv en kommentar"
    l.setAttribute("for", "comment")
    l.innerText = "Skapa Kommentar"
    ig.classList = "input_group"

    sr.append(sbmb)
    ig.append(l, ta)

    f.append(ig, sr)

    return f
}

/**
 * 
 * @param {SubmitEvent} ev 
 */
function onSubmit(ev, id) {
    ev.preventDefault()

    const { value } = ev.target.querySelector("textarea")
    const fd = new FormData()
    fd.append("parent", id)
    fd.append("text", value)
    fd.append("threadid", threadDetails.id)



    fetch("/api/new_comment.php", {
        method: "POST",
        body: fd
    })
    .then(d => {
        d
        .json()
            .then(res => {
                if(res.code === 200) {
                    bindCommentElem(res.data, id, true, true)
                } else {
                    toast(res.data.title, res.data.description, { type: TOAST_TYPES_ENUM.ERROR })
                }
            })
        .catch(genErr)
    })
    .catch(genErr)

    
}

/**
 * 
 * @param {String|HTMLElement} parent 
 */
function bindComment(parent, id, deleteAfterUse = false) {
    let p = parent
    if(typeof parent == "string") {
        p = document.getElementById(parent)
    } 

    const form = createCommentForm()
    form.onsubmit = (e) => {
        onSubmit(e, id)
        if(deleteAfterUse) form.remove()
    }

    p.append(form)

    form.scrollIntoView(false, { behavior: "smooth", block: "center" })
}



function promptUserLogin() {
    window.location.replace(`/auth/login.php?url=${encodeURI(window.location.href)}`)
}

function populate() {
    getComments(threadDetails.id)
        .then(d => {
            for(const c of d) {
                bindCommentElem(c, c.parent)
            }
        })
}

document.addEventListener("DOMContentLoaded", () => {
    if(typeof currentUser != "undefined") bindComment("main_comment", threadDetails.id)
    populate()
})

function deleteComment(comment) {
    if(confirm("Är du helt säker?")) {
        const fd = new FormData()
        fd.append("comment", comment)
        fetch(`/api/delete.php`, { method: "POST", body: fd })
            .then(data => {
                data.json()
                    .then(res => {
                        if(res.code != 200) {
                            toast(res.data.title, res.data.description, { type: TOAST_TYPES_ENUM.ERROR })
                        } 
                    })
                    .catch(genErr)
            })
            .catch(genErr)
    }
}

function deleteThread() {
    if(confirm("Är du helt säker?")) {
        const fd = new FormData()
        fd.append("thread", threadDetails.id)
        fetch(`/api/delete.php`, { method: "POST", body: fd })
            .then(data => {
                data.json()
                    .then(res => {
                        if(res.code == 200) {
                            window.location.replace("/index.php")
                        } else {
                            toast(res.data.title, res.data.description, { type: TOAST_TYPES_ENUM.ERROR })
                        }
                    })
                    .catch(genErr)
            })
            .catch(genErr)
    }
}

window.deleteThread     = deleteThread
window.promptUserLogin  = promptUserLogin

