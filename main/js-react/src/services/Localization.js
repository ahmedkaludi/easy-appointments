const i18n = window?.wp?.i18n;

export function __(text, domain) {
  if (!i18n) {
    return text;
  }

  return i18n.__(text, domain);
}

export function _x(text, context, domain) {
  if (!i18n) {
    return text;
  }

  return i18n._x(text, context, domain);
}
