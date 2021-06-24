import { forEach } from 'lodash';
import { http } from '../services';

const fullcalendarApi = window?.ea_settings?.rest_url_fullcalendar ?? '';
// const ajaxUrl = window?.ajaxurl ?? '';
const nonce = window?.wpApiSettings?.nonce ?? '';

export class FullCalendarCommunicator {
  static url = `${fullcalendarApi}?_wpnonce=${nonce}`;

  /**
   * Fetch all events or all free slots if service, worker, location is selected
   * @param start
   * @param end
   * @param label
   * @param filters
   * @returns {Promise<*>}
   */
  static async fetchEvents(start, end, label, filters = {}) {
    const fromTo = `&start=${start}&end=${end}`;
    let filtersParams = '';

    forEach(filters, (filter, key) => {
      filtersParams += `&${key}=${filter.value}`;
    });

    const response = await http.getJSON(
      `${this.url}${fromTo}&title_field=${label}${filtersParams}`
    );

    // Simple mapping for Events in order to show it inside Calendar
    return response.map(event => {
      const mappedEvent = { ...event };

      mappedEvent.start = new Date(event.start);
      mappedEvent.end = new Date(event.end);

      return mappedEvent;
    });
  }
}
