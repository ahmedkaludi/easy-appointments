import axios from 'axios';

const jQuery = window.jQuery;

class HttpService {
  get(url, data) {
    return jQuery.get(url, data);
  }

  getJSON(url) {
    return axios.get(url).then(response => response.data);
  }

  getBlob(url) {
    return axios
      .get(url, { responseType: 'blob' })
      .then(response => response.data);
  }

  postJSON(url, data = {}) {
    return axios
      .post(url, data, {
        headers: {
          'content-type': 'application/json'
        }
      })
      .then(response => response.data);
  }

  post(url, data = {}) {
    return axios.post(url, data).then(response => response.data);
  }

  delete(url) {
    return axios.delete(url).then(response => response.data);
  }
}

export const http = new HttpService();
