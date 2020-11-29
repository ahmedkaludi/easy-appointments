class EaDataService {
  config = window.eaData || {};

  hasProp(propName) {
    return this.config[propName];
  }

  get(propName) {
    return this.config[propName] ?? null;
  }
}

export const DataService = new EaDataService();
