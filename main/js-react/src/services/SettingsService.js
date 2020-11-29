export const getSettings = (key, defaultValue) => {
  return window?.ea_settings[key] ?? defaultValue;
};
