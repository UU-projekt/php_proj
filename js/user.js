import toast, { TOAST_TYPES_ENUM } from "./toast.js"

async function changePassword() {
    const code = await (await fetch("api/get_psw_reset_link.php", { method: "POST" })).json()

    if(code.code === 200) {
        window.location.replace(`auth/set_password.php?t=${code.data}`)
    } else {
        toast("Fel", "det går för tillfället inte att byta ditt lösenord.", { dismiss: 5000, type: TOAST_TYPES_ENUM.ERROR })
    }
}

window.changePassword = changePassword