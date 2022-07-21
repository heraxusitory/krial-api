import Resource from '@/api/resource';

class CategoryResource extends Resource {
  constructor() {
    super('catalog/categories');
  }
}

export { CategoryResource as default };
