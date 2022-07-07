import Resource from '@/api/resource';

class DgManufactureResource extends Resource {
  constructor() {
    super('catalog/dg/manufacturers');
  }
}

export { DgManufactureResource as default };
