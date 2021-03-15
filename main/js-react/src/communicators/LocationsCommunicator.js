import { http } from '../services';

const ajaxUrl = window?.ajaxurl ?? '';
const nonce = window?.wpApiSettings?.nonce ?? '';

/**
 * Locations
 */
export class LocationsCommunicator {
  static url = `${ajaxUrl}?&_wpnonce=${nonce}`;

  /**
   * Fetch all locations
   *
   * @returns {Promise<EALocation[]>}
   */
  static async fetchAll() {
    const url = `${this.url}&action=ea_locations`;

    return http.getJSON(url);
  }

  /**
   * Save entity NEW / UPDATE
   *
   * @param {EALocation} data
   * @returns {Promise<{ id: string}>}
   */
  static async save(data) {
    let url = `${this.url}&action=ea_location`;

    // update case
    if (data.id) {
      url = `${url}&id=${data.id}&_method=PUT`;
    }

    return await http.postJSON(url, JSON.stringify(data));
  }

  /**
   * Delete EALocation
   *
   * @param {String} id
   * @returns {Promise<{Boolean}>}
   */
  static async delete(id) {
    const url = `${this.url}&action=ea_location&_method=DELETE&id=${id}`;

    const response = await http.post(url);

    return response === 1;
  }
}
