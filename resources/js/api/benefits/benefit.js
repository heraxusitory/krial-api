import Resource from '@/api/resource';

class BenefitResource extends Resource {
  constructor() {
    super('catalog/benefits');
  }
}

export { BenefitResource as default };
