export const getSettings = (key, defaultValue) => {
  return window?.ea_settings[key] ?? defaultValue;
};

export const setSettings = (key, value) => {
  window.ea_settings[key] = value;
};
