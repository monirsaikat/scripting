import { on, qs, setFormAction, setInputValue, setText } from './dom.js';
import { showModal } from './bootstrap-modals.js';

const selectors = {
  confirmButton: '.hasConfirmation',
  confirmModal: '#confirmModal',
  editButton: '.btnEdit',
  addButton: '.addBtn',
  editModal: '#editModal'
};

export function initStaffActions() {
  on(document, 'click', selectors.confirmButton, openConfirmationModal);
  on(document, 'click', selectors.addButton, openCreateStaffModal);
  on(document, 'click', selectors.editButton, openEditStaffModal);
}

function openConfirmationModal(event, button) {
  event.preventDefault();

  const modal = qs(selectors.confirmModal);
  const form = modal && qs('form', modal);

  setFormAction(form, button.dataset.url);
  showModal(selectors.confirmModal);
}

function openCreateStaffModal(event) {
  event.preventDefault();

  const modal = qs(selectors.editModal);
  const form = modal && qs('form', modal);

  setFormAction(form, form && form.dataset.createUrl);
  setText(qs('.modal-title', modal), 'Add New Staff');
  setStaffFormValues(form, {});
  showModal(selectors.editModal);
}

function openEditStaffModal(event, button) {
  event.preventDefault();

  const modal = qs(selectors.editModal);
  const form = modal && qs('form', modal);
  const firstName = button.dataset.firstName || '';

  setFormAction(form, button.dataset.url);
  setStaffFormValues(form, {
    first_name: firstName,
    last_name: button.dataset.lastName,
    email: button.dataset.email,
    phone: button.dataset.phone,
    address: button.dataset.address
  });
  setText(qs('.modal-title', modal), 'Editing data for ' + (firstName || 'staff'));
  showModal(selectors.editModal);
}

function setStaffFormValues(form, values) {
  setInputValue(form, 'first_name', values.first_name);
  setInputValue(form, 'last_name', values.last_name);
  setInputValue(form, 'email', values.email);
  setInputValue(form, 'phone', values.phone);
  setInputValue(form, 'address', values.address);
}
