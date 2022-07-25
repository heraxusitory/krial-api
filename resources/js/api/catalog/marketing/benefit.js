import Resource from '@/api/resource';

class BenefitResource extends Resource {
  constructor() {
    super('catalog/marketing/benefits');
  }
}

export { BenefitResource as default };
