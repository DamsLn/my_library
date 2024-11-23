import './bootstrap.js';
import './styles/app.css';
import { openModal, closeModal } from './components/modal.js';
import { bookShow, bookEdit, askConfirmation } from './controllers/book_controller.js';
import { hiddeMessageFlash } from './components/flash.js';

window.openModal = openModal;
window.closeModal = closeModal;

window.bookEdit = bookEdit;
window.bookShow = bookShow;
window.askConfirmation = askConfirmation;

window.hiddeMessageFlash = hiddeMessageFlash;