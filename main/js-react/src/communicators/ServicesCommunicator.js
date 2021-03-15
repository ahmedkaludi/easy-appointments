import { http } from '../services';

const ajaxUrl = window?.ajaxurl ?? '';
const nonce = window?.wpApiSettings?.nonce ?? '';

/**
 * Services
 */
export class ServicesCommunicator {
  static url = `${ajaxUrl}?&_wpnonce=${nonce}`;

  /**
   * Fetch all services
   *
   * @returns {Promise<EAService[]>}
   */
  static async fetchAll() {
    const url = `${this.url}&action=ea_services`;

    return http.getJSON(url);
  }

  /**
   * Save entity NEW / UPDATE
   *
   * @param {EAService} data
   * @returns {Promise<{ id: string}>}
   */
  static async save(data) {
    let url = `${this.url}&action=ea_service`;

    // update case
    if (data.id) {
      url = `${url}&id=${data.id}&_method=PUT`;
    }

    data.price = data.price.replaceAll(',', '');

    return await http.postJSON(url, JSON.stringify(data));
  }

  /**
   * Delete EAService
   *
   * @param {String} id
   * @returns {Promise<{Boolean}>}
   */
  static async delete(id) {
    const url = `${this.url}&action=ea_service&_method=DELETE&id=${id}`;

    const response = await http.post(url);

    return response === 1;
  }
}
