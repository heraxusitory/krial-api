import Resource from '@/api/resource';

class DgProductResource extends Resource {
  constructor() {
    super('catalog/dg/products');
  }
}

export { DgProductResource as default };
