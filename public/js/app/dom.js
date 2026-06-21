export function qs(selector, root = document) {
  return root.querySelector(selector);
}

export function qsa(selector, root = document) {
  return Array.from(root.querySelectorAll(selector));
}

export function closest(target, selector) {
  return target instanceof Element ? target.closest(selector) : null;
}

export function on(root, eventName, selector, handler) {
  root.addEventListener(eventName, function (event) {
    const match = closest(event.target, selector);

    if (!match || !root.contains(match)) {
      return;
    }

    handler(event, match);
  });
}

export function setText(element, value) {
  if (element) {
    element.textContent = value || '';
  }
}

export function setInputValue(form, name, value) {
  const field = form ? form.elements[name] : null;

  if (field) {
    field.value = value || '';
  }
}

export function setFormAction(form, action) {
  if (form && action) {
    form.setAttribute('action', action);
  }
}
