export const hiddeMessageFlash = (messageFlashId) => {
    document.getElementById(messageFlashId).remove();
}

const flashes = document.getElementsByClassName('flash');

for (let i = 0; i < flashes.length; i++) {
    setTimeout(() => {
        hiddeMessageFlash(flashes[i].id);
    }, 5000);
}