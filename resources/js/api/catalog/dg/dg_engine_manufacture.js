import Resource from '@/api/resource';

class DgEngineManufactureResource extends Resource {
  constructor() {
    super('catalog/dg/engine_manufacturers');
  }
}

export { DgEngineManufactureResource as default };
