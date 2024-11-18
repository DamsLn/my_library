export const bookShow = (bookId) => {
    fetch(`/ajax/book/${bookId}/show`)
        .then(response => response.json())
        .then(html => {
            openModal('showBookModal');
            document.getElementById('bookInfo').innerHTML = html;
        });
}

export const bookEdit = (bookId) => {
    fetch(`/ajax/book/${bookId}/edit`)
        .then(response => response.json())
        .then(html => {
            openModal('editBookModal');
            document.getElementById('editBookForm').innerHTML = html;
        });
}