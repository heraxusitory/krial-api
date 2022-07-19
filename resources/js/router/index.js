import Vue from 'vue';
import Router from 'vue-router';

/**
 * Layzloading will create many files and slow on compiling, so best not to use lazyloading on devlopment.
 * The syntax is lazyloading, but we convert to proper require() with babel-plugin-syntax-dynamic-import
 * @see https://doc.laravue.dev/guide/advanced/lazy-loading.html
 */

Vue.use(Router);

/* Layout */
import Layout from '@/layout';

/* Router for modules */
// import adminRoutes from './modules/admin';
import errorRoutes from './modules/error';
// import nestedRoutes from './modules/nested';
// import permissionRoutes from './modules/permission';

/**
 * Sub-menu only appear when children.length>=1
 * @see https://doc.laravue.dev/guide/essentials/router-and-nav.html
 **/

/**
* hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
* alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
*                                if not set alwaysShow, only more than one route under the children
*                                it will becomes nested mode, otherwise not show the root menu
* redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
* name:'router-name'             the name is used by <keep-alive> (must set!!!)
* meta : {
    roles: ['admin', 'editor']   Visible for these roles only
    permissions: ['view menu zip', 'manage user'] Visible for these permissions only
    title: 'title'               the name show in sub-menu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if true, the page will no be cached(default is false)
    breadcrumb: false            if false, the item will hidden in breadcrumb (default is true)
    affix: true                  if true, the tag will affix in the tags-view
  }
**/

export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        component: () => import('@/views/redirect/index'),
      },
    ],
  },
  {
    path: '/login',
    component: () => import('@/views/login/index'),
    hidden: true,
  },
  {
    path: '/auth-redirect',
    component: () => import('@/views/login/AuthRedirect'),
    hidden: true,
  },
  {
    path: '/404',
    redirect: { name: 'Page404' },
    component: () => import('@/views/error-page/404'),
    hidden: true,
  },
  {
    path: '/401',
    component: () => import('@/views/error-page/401'),
    hidden: true,
  },
  {
    path: '',
    component: Layout,
    redirect: 'dashboard',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index'),
        name: 'Dashboard',
        meta: { title: 'dashboard', icon: 'dashboard', noCache: false },
      },
    ],
  },
  {
    path: '/catalog',
    component: Layout,
    alwaysShow: true,
    // redirect: 'noredirect',
    name: 'Catalog',
    meta: { title: 'catalog', icon: 'excel', noCache: false },
    children: [
      {
        path: 'dgu',
        name: 'Dgu',
        // redirect: 'noredirect',
        meta: { title: 'dgu', icon: 'excel', noCache: false },
        alwaysShow: true,
        // component: () => import('@/views/catalog/dg/products/List'),
        component: () => import('@/views/catalog/dg/Index'),
        children: [
          {
            path: 'products',
            name: 'DguProducts',
            component: () => import('@/views/catalog/dg/products/List'),
            meta: { title: 'dg_products', icon: 'excel', noCache: false },
          },
          {
            path: 'products/edit/:id?',
            name: 'DguProductEdit',
            component: () => import('@/views/catalog/dg/products/Edit'),
            meta: { title: 'dg_product_edit', icon: 'excel', noCache: false },
            hidden: true,
          },
          {
            path: 'property_groups',
            name: 'DguPropertyGroups',
            component: () => import('@/views/catalog/dg/property_groups/List'),
            meta: { title: 'dg_property_groups', icon: 'excel', noCache: false },
          },
          {
            path: 'properties',
            name: 'DguProperties',
            component: () => import('@/views/catalog/dg/properties/List'),
            meta: { title: 'dg_properties', icon: 'excel', noCache: false },
          },
          {
            path: 'option_groups',
            name: 'DguOptionGroups',
            component: () => import('@/views/catalog/dg/option_groups/List'),
            meta: { title: 'dg_option_groups', icon: 'excel', noCache: false },
          },
          {
            path: 'options',
            name: 'DguOptions',
            component: () => import('@/views/catalog/dg/options/List'),
            meta: { title: 'dg_options', icon: 'excel', noCache: false },
          },
          // {
          //   path: 'products2',
          //   name: 'DguProducts2',
          //   component: () => import('@/views/catalog/dg/products/List'),
          //   meta: { title: 'products2', icon: 'excel', noCache: false },
          // },
        ],
      },
      // {
      //   path: 'dgu2',
      //   name: 'Dgu2',
      //   redirect: 'noredirect',
      //   meta: { title: 'dgu2', icon: 'excel', noCache: false },
      //   // alwaysShow: true,
      //   // component: () => import('@/views/catalog/dg/products/List'),
      //   // component: () => import('@/views/catalog/dg/Index'),
      // },
    ],
  },
  // {
  //   path: '/catalog',
  //   component: Layout,
  //   meta: { title: 'catalog', icon: 'excel', noCache: false },
  //   children: [
  //     {
  //       path: 'dgu',
  //       meta: { title: 'dgu', icon: 'excel', noCache: false },
  //       children: [
  //         {
  //           path: 'products',
  //           children: [
  //             {
  //               path: '',
  //               meta: { title: 'dg_products', icon: 'excel', noCache: false },
  //             },
  //           ],
  //         },
  //         {
  //           path: 'properties',
  //           children: [
  //             {
  //               path: '',
  //               meta: { title: 'dg_properties', icon: 'excel', noCache: false },
  //             },
  //           ],
  //         },
  //         {
  //           path: 'property_groups',
  //           children: [
  //             {
  //               path: '',
  //               meta: { title: 'dg_property_groups', icon: 'excel', noCache: false },
  //             },
  //           ],
  //         },
  //         {
  //           path: 'versions',
  //           children: [
  //             {
  //               path: '',
  //               meta: { title: 'dg_versions', icon: 'excel', noCache: false },
  //             },
  //           ],
  //         },
  //         {
  //           path: 'manufacturers',
  //           children: [
  //             {
  //               path: '',
  //               meta: { title: 'dg_manufacturers', icon: 'excel', noCache: false },
  //             },
  //           ],
  //         },
  //         {
  //           path: 'engine_manufacturers',
  //           children: [
  //             {
  //               path: '',
  //               meta: { title: 'dg_engine_manufacturers', icon: 'excel', noCache: false },
  //             },
  //           ],
  //         },
  //       ],
  //     },
  //   ],
  // },
  {
    path: '/profile',
    component: Layout,
    redirect: '/profile/edit',
    children: [
      {
        path: 'edit',
        component: () => import('@/views/users/SelfProfile'),
        name: 'SelfProfile',
        meta: { title: 'userProfile', icon: 'user', noCache: true },
      },
    ],
    hidden: true,
  },
];

export const asyncRoutes = [
  // permissionRoutes, TODO: пока что не нужны
  // adminRoutes, // TODO: пока что не нужен
  // nestedRoutes,
  errorRoutes,
  { path: '*', redirect: '/404', hidden: true },
];

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  base: process.env.MIX_LARAVUE_PATH,
  routes: constantRoutes,
});

const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher; // reset router
}

export default router;
