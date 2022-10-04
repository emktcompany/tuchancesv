var apiUrl = 'admin/programs/:id?';

export default [
  {
    path: 'programas',
    name: 'admin.programs',
    component: () => import(/* webpackChunkName: "admin-programs" */ '@/pages/Admin/Programs/Index.vue'),
    meta: {
      title: 'Rubros',
      apiUrl
    }
  },
  {
    path: 'programas/crear',
    name: 'admin.programs.create',
    component: () => import(/* webpackChunkName: "admin-programs" */ '@/pages/Admin/Programs/Form.vue'),
    meta: {
      title: 'Crear',
      parent: 'admin.programs',
      apiUrl
    }
  },
  {
    path: 'programas/importar/:id',
    name: 'admin.programs.import',
    component: () => import(/* webpackChunkName: "admin-programs" */ '@/pages/Admin/Programs/Import.vue'),
    meta: {
      title: 'Importar',
      parent: 'admin.programs',
      apiUrl
    }
  },
  {
    path: 'programas/edita/:id',
    name: 'admin.programs.edit',
    component: () => import(/* webpackChunkName: "admin-programs" */ '@/pages/Admin/Programs/Form.vue'),
    meta: {
      title: 'Editar',
      parent: 'admin.programs',
      apiUrl
    }
  },
];
