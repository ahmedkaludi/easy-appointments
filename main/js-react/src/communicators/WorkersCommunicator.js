import { http } from '../services';

const ajaxUrl = window?.ajaxurl ?? '';
const nonce = window?.wpApiSettings?.nonce ?? '';

/**
 * Workers
 */
export class WorkersCommunicator {
  static url = `${ajaxUrl}?&_wpnonce=${nonce}`;

  /**
   * Fetch all services
   *
   * @returns {Promise<EAWorker[]>}
   */
  static async fetchAll() {
    const url = `${this.url}&action=ea_workers`;

    return http.getJSON(url);
  }

  static async isProExist() {
    const url = `${this.url}&action=ea_is_pro_exist`;

    return http.getJSON(url);
  }

  /**
   * Save entity NEW / UPDATE
   *
   * @param {EAWorker} data
   * @returns {Promise<{ id: string}>}
   */
  static async save(data) {
    let url = `${this.url}&action=ea_worker`;

    // update case
    if (data.id) {
      url = `${url}&id=${data.id}&_method=PUT`;
    }

    return await http.postJSON(url, JSON.stringify(data));
  }

  /**
   * Delete EAWorker
   *
   * @param {String} id
   * @returns {Promise<{Boolean}>}
   */
  static async delete(id) {
    const url = `${this.url}&action=ea_worker&_method=DELETE&id=${id}`;

    const response = await http.post(url);

    return response === 1;
  }

  static async googleSignOut(id) {
    const url = `${this.url}&action=ea_remove_google_calendar&_method=DELETE&id=${id}`;

    const response = await http.post(url);

    return response;
  }

  static async checkGoogleToken(id) {
    const url = `${this.url}&action=ea_check_google_calendar_token&id=${id}`;

    const response = await http.getJSON(url);
    return response;
  }
}
