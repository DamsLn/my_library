export const bookEdit = (bookId) => {
    fetch(`/ajax/book/${bookId}/edit`)
        .then(response => response.json())
        .then(html => {
            openModal('editBookModal');
            document.getElementById('editBookForm').innerHTML = html;
        });
}