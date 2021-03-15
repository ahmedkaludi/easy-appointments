import { http } from '../services';

const vacationUrl = window?.ea_settings?.rest_url_vacation ?? '';
const nonce = window?.wpApiSettings?.nonce ?? '';

export class VacationsCommunicator {
  static url = `${vacationUrl}?_wpnonce=${nonce}`;

  static async fetch() {
    const response = await http.getJSON(this.url);
    return response;
  }

  static async save(data) {
    const response = await http.postJSON(this.url, JSON.stringify(data));
    return response;
  }
}
