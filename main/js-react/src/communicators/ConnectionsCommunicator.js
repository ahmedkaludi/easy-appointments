import { http } from '../services';

const ajaxUrl = window?.ajaxurl ?? '';
const nonce = window?.wpApiSettings?.nonce ?? '';
const extendConnections = window?.ea_settings?.rest_url_extend_connections;

/**
 * Locations
 */
export class ConnectionsCommunicator {
  static url = `${ajaxUrl}?&_wpnonce=${nonce}`;

  /**
   * Fetch all connections
   *
   * @returns {Promise<EAConnection[]>}
   */
  static async fetchAll() {
    const url = `${this.url}&action=ea_connections`;

    return http.getJSON(url);
  }

  /**
   * Save entity NEW / UPDATE
   *
   * @param {EAConnection} data
   * @returns {Promise<{ id: string}>}
   */
  static async save(data) {
    let url = `${this.url}&action=ea_connection`;

    // update case
    if (data.id) {
      url = `${url}&id=${data.id}&_method=PUT`;
    }

    return await http.postJSON(url, JSON.stringify(data));
  }

  /**
   * Delete EAConnection
   *
   * @param {String} id
   * @returns {Promise<{Boolean}>}
   */
  static async delete(id) {
    const url = `${this.url}&action=ea_connection&_method=DELETE&id=${id}`;

    const response = await http.post(url);

    return response === 1;
  }

  /**
   * Extend connections
   */
  static async extendConnection() {
    const url = `${extendConnections}?&_wpnonce=${nonce}`;

    const response = await http.post(url);

    return response;
  }
}
