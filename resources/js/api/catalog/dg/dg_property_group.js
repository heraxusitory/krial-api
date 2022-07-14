import Resource from '@/api/resource';

class DgPropertyGroupResource extends Resource {
  constructor() {
    super('catalog/dg/property_groups');
  }
}

export { DgPropertyGroupResource as default };
