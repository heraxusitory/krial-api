import Resource from '@/api/resource';
import request from '@/utils/request';

class DgPropertyResource extends Resource {
  constructor() {
    super('catalog/dg/properties');
  }

  getFilterTypes() {
    return request({
      url: '/' + this.uri + '/' + 'filter_types',
      method: 'get',
    });
  }
}

export { DgPropertyResource as default };
