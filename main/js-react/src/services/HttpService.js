const jQuery = window.jQuery;

class HttpService {
  get(url, data) {
    return jQuery.get(url, data);
  }

  getJSON(url) {
    return jQuery.getJSON(url);
  }

  post(url, data = {}) {
    return jQuery.post(url, data);
  }
}

export const http = new HttpService();
