(function () {
  function showModal(selector) {
    var element = document.querySelector(selector);

    if (!element || !window.bootstrap || !window.bootstrap.Modal) {
      return;
    }

    window.bootstrap.Modal.getOrCreateInstance(element).show();
  }

  function setValue(selector, value) {
    var element = document.querySelector(selector);

    if (element) {
      element.value = value || '';
    }
  }

  function setStaffFormData(data) {
    setValue('[name="first_name"]', data.firstName);
    setValue('[name="last_name"]', data.lastName);
    setValue('[name="email"]', data.email);
    setValue('[name="address"]', data.address);
    setValue('[name="phone"]', data.phone);
  }

  function cleanupBootstrapModals() {
    document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
      backdrop.remove();
    });

    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
  }

  document.addEventListener('click', function (event) {
    var confirmButton = event.target.closest('.hasConfirmation');

    if (confirmButton) {
      event.preventDefault();

      var confirmModal = document.querySelector('#confirmModal');
      var confirmForm = confirmModal && confirmModal.querySelector('form');

      if (confirmForm) {
        confirmForm.setAttribute('action', confirmButton.dataset.url || '');
      }

      showModal('#confirmModal');
      return;
    }

    var addButton = event.target.closest('.addBtn');

    if (addButton) {
      event.preventDefault();

      var addModal = document.querySelector('#editModal');
      var addForm = addModal && addModal.querySelector('form');
      var addTitle = addModal && addModal.querySelector('.modal-title');

      if (addForm) {
        addForm.setAttribute('action', addForm.dataset.createUrl || addForm.getAttribute('action'));
      }

      if (addTitle) {
        addTitle.textContent = 'Add New Staff';
      }

      setStaffFormData({});
      showModal('#editModal');
      return;
    }

    var editButton = event.target.closest('.btnEdit');

    if (editButton) {
      event.preventDefault();

      var editModal = document.querySelector('#editModal');
      var editForm = editModal && editModal.querySelector('form');
      var editTitle = editModal && editModal.querySelector('.modal-title');

      if (editForm) {
        editForm.setAttribute('action', editButton.dataset.url || '');
      }

      setStaffFormData({
        firstName: editButton.dataset.firstName,
        lastName: editButton.dataset.lastName,
        email: editButton.dataset.email,
        address: editButton.dataset.address,
        phone: editButton.dataset.phone
      });

      if (editTitle) {
        editTitle.textContent = 'Editing data for ' + (editButton.dataset.firstName || 'staff');
      }

      showModal('#editModal');
    }
  });

  if (!window.up) {
    return;
  }

  up.link.config.followSelectors.push(
    'a[href]:not([href^="#"]):not([target]):not([download]):not([up-follow=false]):not([data-no-up])'
  );

  up.form.config.submitSelectors.push(
    'form[method]:not([up-submit=false]):not([data-no-up])'
  );

  if (up.link.config.preloadSelectors) {
    up.link.config.preloadSelectors.push(
      'a[href][data-up-preload]'
    );
  }

  up.on('up:fragment:inserted', function () {
    cleanupBootstrapModals();

    document.querySelectorAll('.alert[data-auto-dismiss]').forEach(function (alert) {
      window.setTimeout(function () {
        alert.remove();
      }, 4000);
    });
  });
})();
