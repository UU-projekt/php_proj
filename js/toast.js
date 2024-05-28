function createToastParent() {
    const element = document.createElement("div")
    element.id = "toast"
    
    return element
}

function getToastParent() {
    let parent = document.getElementById("toast")

    if(!parent) {
        parent = createToastParent()
        document.body.append(parent)
    }

    return parent
}

function createToastElement(title, body) {
    const t = document.createElement("div")
    t.classList = "toast"
    

    const head = document.createElement("h4")
    head.classList = "title"
    head.innerText = title

    const content = document.createElement("p")
    content.classList = "body"
    content.innerText = body

    t.append(head, content)

    return t
}

const TOAST_TYPES_ENUM = {
    INFO: "info",
    BRANDING: "branding",
    ERROR: "error",
    WARNING: "warning",
    TETRIARY: "tetriary"
}

const TOAST_DEFAULTS = {
    // Tiden som toasten visas i milisekunder
    dismiss: 2500, 
    // Klasser för att kunna utöka utseende
    classes: [ ],
    // Utseende
    type: TOAST_TYPES_ENUM.TETRIARY
}

function toast(title, body, options = {}) {
    const parent = getToastParent()

    const opt = { ...TOAST_DEFAULTS, ...options }

    const toast = createToastElement(title, body)

    toast.classList.add([ opt.type, ...opt.classes])

    parent.append(toast)

    const kill = () => {
        toast.style.setProperty("--toast_height", toast.offsetHeight.toString())
        toast.classList.add("toast_bye_bye")

        setTimeout(() => toast.remove(), 350)
    }

    setTimeout(kill, opt.dismiss)

    const returnObject = {
        kill
    }

    console.log(opt)

    return returnObject
}

export default toast
export { TOAST_TYPES_ENUM }