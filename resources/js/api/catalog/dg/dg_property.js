import Resource from '@/api/resource';

class DgPropertyResource extends Resource {
  constructor() {
    super('catalog/dg/properties');
  }
}

export { DgPropertyResource as default };
