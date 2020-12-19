import { http } from '../services';

const ajaxUrl = window?.ajaxurl ?? '';
const nonce = window?.wpApiSettings?.nonce ?? '';

/**
 * Workers
 */
export class SortCommunicator {
  static url = `${ajaxUrl}?&_wpnonce=${nonce}&action=ea_setting`;

  /**
   *
   * @param {string} key
   * @param {string} value
   * @returns {Promise<boolean>}
   */
  static async save(key, value) {
    const data = {
      ea_key: key,
      ea_value: value,
      type: 'json'
    };

    return await http.post(SortCommunicator.url, JSON.stringify(data));
  }

  /**
   *
   * @param {string} column
   * @param {"ASC"|"DESC"} order
   * @returns {Promise<boolean>}
   */
  static async saveSortLocations(column, order) {
    const saveKey = await SortCommunicator.save('sort.locations-by', column);
    const saveOrder = await SortCommunicator.save('order.locations-by', order);

    return saveKey === saveOrder;
  }

  /**
   *
   * @param {string} column
   * @param {"ASC"|"DESC"} order
   * @returns {Promise<boolean>}
   */
  static async saveSortWorkers(column, order) {
    const saveKey = await SortCommunicator.save('sort.workers-by', column);
    const saveOrder = await SortCommunicator.save('order.workers-by', order);

    return saveKey === saveOrder;
  }

  /**
   *
   * @param {string} column
   * @param {"ASC"|"DESC"} order
   * @returns {Promise<boolean>}
   */
  static async saveSortServices(column, order) {
    const saveKey = await SortCommunicator.save('sort.services-by', column);
    const saveOrder = await SortCommunicator.save('order.services-by', order);

    return saveKey === saveOrder;
  }
}
