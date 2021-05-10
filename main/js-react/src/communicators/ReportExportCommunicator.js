import { http } from '../services';

const ajaxUrl = window?.ajaxurl ?? '';
const nonce = window?.wpApiSettings?.nonce ?? '';

export class ReportExportCommunicator {
  static url = `${ajaxUrl}?&_wpnonce=${nonce}`;

  /**
   * Save column settings
   */
  static async saveExportColumns(columnsFields = '') {
    let url = `${ReportExportCommunicator.url}`;

    let formData = new FormData();
    formData.append('fields', columnsFields);
    formData.append('action', 'ea_save_custom_columns');

    return await http.post(url, formData);
  }

  /**
   * Fetch export file
   */
  static async getExportFile(from, to) {
    let url = `${ReportExportCommunicator.url}&action=ea_export&ea-export-from=${from}&ea-export-to=${to}`;

    return await http.get(url);
  }
}
