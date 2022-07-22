import Resource from '@/api/resource';
import request from '@/utils/request';

class MarketingBannerResource extends Resource {
  constructor() {
    super('catalog/marketing/banners');
  }

  getTypes() {
    return request({
      url: '/' + this.uri + '/' + 'types',
      method: 'get',
    });
  }
}

export { MarketingBannerResource as default };
