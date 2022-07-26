import Resource from '@/api/resource';
import request from '@/utils/request';

class DgProductResource extends Resource {
  constructor() {
    super('catalog/dg/products');
  }

  changeActive(resource) {
    return request({
      url: '/' + this.uri + '/' + 'change_active',
      method: 'post',
      data: resource,
    });
  }

  setSeries(resource) {
    return request({
      url: '/' + this.uri + '/' + 'set_series',
      method: 'post',
      data: resource,
    });
  }

  updateProductRow(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id + '/update_product_row',
      method: 'put',
      data: resource,
    });
  }
}

export { DgProductResource as default };
