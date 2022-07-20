import Resource from '@/api/resource';

class ApplicationRequestResource extends Resource {
  constructor() {
    super('shop/application_requests');
  }
}

export { ApplicationRequestResource as default };
