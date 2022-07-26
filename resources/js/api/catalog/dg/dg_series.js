import Resource from '@/api/resource';

class DgSeriesResource extends Resource {
  constructor() {
    super('catalog/dg/series');
  }
}

export { DgSeriesResource as default };
