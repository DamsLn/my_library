export const authorShow = (authorId) => {
    fetch(`/ajax/author/${authorId}/show`)
        .then(response => response.json())
        .then(html => {
            openModal('showAuthorModal');
            document.getElementById('authorInfo').innerHTML = html;
        });
}

export const authorEdit = (authorId) => {
    fetch(`/ajax/admin/author/${authorId}/edit`)
        .then(response => response.json())
        .then(html => {
            openModal('editAuthorModal');
            document.getElementById('editAuthorForm').innerHTML = html;
        });
}

export const authorAskConfirmation = (event, actionType) => {
    let message = "Êtes-vous sûr de vouloir ";

    switch (actionType) {
        case 'remove':
            message += "supprimer cet auteur/autrice ?";
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