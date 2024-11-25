import './bootstrap.js';
import './styles/app.css';
import { openModal, closeModal } from './components/modal.js';
import { bookShow, bookEdit, bookAskConfirmation } from './controllers/book_controller.js';
import { authorShow, authorEdit, authorAskConfirmation } from './controllers/author_controller.js';
import { hiddeMessageFlash } from './components/flash.js';

window.openModal = openModal;
window.closeModal = closeModal;

window.bookEdit = bookEdit;
window.bookShow = bookShow;
window.bookAskConfirmation = bookAskConfirmation;

window.authorEdit = authorEdit;
window.authorShow = authorShow;
window.authorAskConfirmation = authorAskConfirmation;

window.hiddeMessageFlash = hiddeMessageFlash;