import toast, { TOAST_TYPES_ENUM } from "./toast.js"

/*
<li data-starred-id="<?= $t["id"] ?>">
    <button onclick="removeStar('<?= $t["id"] ?>', this)" class="btn tetriary star starred">
        <svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m11.322 2.923c.126-.259.39-.423.678-.423.289 0 .552.164.678.423.974 1.998 2.65 5.44 2.65 5.44s3.811.524 6.022.829c.403.055.65.396.65.747 0 .19-.072.383-.231.536-1.61 1.538-4.382 4.191-4.382 4.191s.677 3.767 1.069 5.952c.083.462-.275.882-.742.882-.122 0-.244-.029-.355-.089-1.968-1.048-5.359-2.851-5.359-2.851s-3.391 1.803-5.359 2.851c-.111.06-.234.089-.356.089-.465 0-.825-.421-.741-.882.393-2.185 1.07-5.952 1.07-5.952s-2.773-2.653-4.382-4.191c-.16-.153-.232-.346-.232-.535 0-.352.249-.694.651-.748 2.211-.305 6.021-.829 6.021-.829s1.677-3.442 2.65-5.44z" fill-rule="nonzero"/></svg>
    </button>
    <a rel="prefetch" href="/thread.php?id=<?= $st["threadID"] ?>"><?= strip_tags($st["title"]) ?></a>
</li>
*/

function createStarRow(buttonElem) {
    if(!threadDetails) return
    const { id, title } = threadDetails

    const li = document.createElement("li")
    li.setAttribute("data-starred-id", id)

    const btn = document.createElement("button")
    btn.classList = "btn tetriary star starred"
    btn.onclick = () => removeStar(id, buttonElem)
    btn.innerHTML = '<svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m11.322 2.923c.126-.259.39-.423.678-.423.289 0 .552.164.678.423.974 1.998 2.65 5.44 2.65 5.44s3.811.524 6.022.829c.403.055.65.396.65.747 0 .19-.072.383-.231.536-1.61 1.538-4.382 4.191-4.382 4.191s.677 3.767 1.069 5.952c.083.462-.275.882-.742.882-.122 0-.244-.029-.355-.089-1.968-1.048-5.359-2.851-5.359-2.851s-3.391 1.803-5.359 2.851c-.111.06-.234.089-.356.089-.465 0-.825-.421-.741-.882.393-2.185 1.07-5.952 1.07-5.952s-2.773-2.653-4.382-4.191c-.16-.153-.232-.346-.232-.535 0-.352.249-.694.651-.748 2.211-.305 6.021-.829 6.021-.829s1.677-3.442 2.65-5.44z" fill-rule="nonzero"/></svg>'

    const a = document.createElement("a")
    a.rel = "prefetch"
    a.href = `/thread.php?id=${id}`
    a.innerText = title

    li.append(btn, a)

    document.getElementById("savedThread").append(li)
}

/**
 * 
 * @param {string} threadid 
 * @param {HTMLButtonElement} elem 
 */
function addStar(threadid, elem) {
    const xd = new FormData()
    xd.append("threadid", threadid)

    fetch("api/star.php", {
        method: "POST",
        body: xd
    })
    .then(r => {
        if(r.status != 200) {
            toast("fel", "kunde inte spara tråden", { type: TOAST_TYPES_ENUM.ERROR })
        } else {
            elem.classList.add("starred")
            elem.onclick = () => removeStar(threadid, elem)
            createStarRow(elem)
        }
        console.log(r)
        
    })
    .catch(e => {
        console.error(e)
        toast("fel", "kunde inte spara tråden", { type: TOAST_TYPES_ENUM.ERROR })
    })
}

/**
 * 
 * @param {string} threadid 
 * @param {HTMLButtonElement} elem 
 */
function removeStar(threadid, elem) {
    const xd = new FormData()
    xd.append("threadid", threadid)
    xd.append("method", "delete")

    fetch("api/star.php", {
        method: "POST",
        body: xd
    })
    .then(r => {
        if(r.status != 200) {
            toast("fel", "kunde inte o-spara tråden", { type: TOAST_TYPES_ENUM.ERROR })
        } else {
            const sidebarElem = document.querySelector(`li[data-starred-id='${threadid}']`)
            elem.classList.remove("starred")
                elem.onclick = () => addStar(threadid, elem)

            if(sidebarElem) {
                sidebarElem.remove()
            } 
        }
        console.log(r)
        
    })
    .catch(e => {
        toast("fel", "kunde inte o-spara tråden", { type: TOAST_TYPES_ENUM.ERROR })
    })
}

window.addStar      = addStar
window.removeStar   = removeStar