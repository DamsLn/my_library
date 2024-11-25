export const openModal = (modalId) => {
    document.getElementById(modalId).classList.remove('invisible');
    document.getElementById(modalId).classList.add('flex');
}

export const closeModal = (modalId) => {
    document.getElementById(modalId).classList.add('invisible');
}