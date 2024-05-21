function validateImage(e) {
    console.log(e)
}

/**
 * 
 * @param {Event} e 
 * @param {HTMLDivElement} ctx 
 */
function validateFileInput(e, ctx) {
    const image = e.target.files[0]

    const preview = ctx.querySelector("img")
    if(preview) {
        const reader = new FileReader()
        reader.readAsDataURL(image)
        reader.onload = () => {
            preview.src = reader.result
            preview.classList.add("preview")
        }
    }
    console.log(image)
}

function bindValidators() {
    const e = document.querySelectorAll(".input_group")

    for(const elem of Array.from(e.values())) {
        console.log(elem)
        const input = elem.querySelector("input") ?? elem.querySelector("textarea")
        
        switch(input.type) {
            case "file":
                input.onchange = (e) => validateFileInput(e, elem)
            break;
        }
    }
}

document.addEventListener("DOMContentLoaded", () => {
    bindValidators()
})