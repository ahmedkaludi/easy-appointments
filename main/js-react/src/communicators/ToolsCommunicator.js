import { http } from '../services';

const ajaxUrl = window?.ajaxurl ?? '';
const removeLogUrl = window?.ea_settings?.rest_url_clear_log;
const nonce = window?.wpApiSettings?.nonce ?? '';

/**
 * Locations
 */
export class ToolsCommunicator {
  static url = `${ajaxUrl}?&_wpnonce=${nonce}`;

  /**
   * Fetch all errors
   *
   * @returns {Promise<[]>}
   */
  static async fetchAllErrors() {
    const url = `${ToolsCommunicator.url}&action=ea_errors`;

    return http.getJSON(url);
  }

  /**

   */
  static async testEmail(address, native = '0') {
    let url = `${ToolsCommunicator.url}&action=ea_test_wp_mail`;

    return await http.get(url, { address, native });
  }

  static clearLogs() {
    let url = `${removeLogUrl}?&_wpnonce=${nonce}`;

    return http.delete(url);
  }
}
