import Resource from '@/api/resource';

class DgOptionResource extends Resource {
  constructor() {
    super('catalog/dg/options');
  }
}

export { DgOptionResource as default };
