export default [
  {
    path: '/tools',
    children: [
      {
        path: '',
        name: 'ListTool',
        component: () => import('../views/tools/ListToolView.vue'),
      },
      {
        path: 'placehold-images',
        name: 'PlaceholdImage',
        component: () => import('../views/tools/PlaceholdImageView.vue'),
      }
    ]
  },
];
