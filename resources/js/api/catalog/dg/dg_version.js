import Resource from '@/api/resource';

class DgVersionResource extends Resource {
  constructor() {
    super('catalog/dg/versions');
  }
}

export { DgVersionResource as default };
