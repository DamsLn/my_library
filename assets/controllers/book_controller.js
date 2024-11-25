export const bookShow = (bookId) => {
    fetch(`/ajax/book/${bookId}/show`)
        .then(response => response.json())
        .then(html => {
            openModal('showBookModal');
            document.getElementById('bookInfo').innerHTML = html;
        });
}

export const bookEdit = (bookId) => {
    fetch(`/ajax/admin/book/${bookId}/edit`)
        .then(response => response.json())
        .then(html => {
            openModal('editBookModal');
            document.getElementById('editBookForm').innerHTML = html;
        });
}

export const bookAskConfirmation = (event, actionType) => {
    let message = "Êtes-vous sûr de vouloir ";

    switch (actionType) {
        case 'remove':
            message += "supprimer ce livre ?";
            break;
        case 'remove':
            message += "supprimer ce livre ?";
            break;
        default:
            message += "réaliser cette action ?";
            break;
    }

    if (!window.confirm(message)) {
        event.preventDefault();
        event.stopPropagation();
    }
}