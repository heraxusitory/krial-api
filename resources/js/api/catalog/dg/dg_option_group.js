import Resource from '@/api/resource';

class DgOptionGroupResource extends Resource {
  constructor() {
    super('catalog/dg/option_groups');
  }
}

export { DgOptionGroupResource as default };
