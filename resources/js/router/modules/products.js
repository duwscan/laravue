import Layout from '@/layout';

const paymentRoutes = {
  path: '/products',
  component: Layout,
  redirect: '/products',
  name: 'Products',
  meta: {
    title: 'Products',
    icon: 'cash',
    permissions: [],
  },
  children: [
    {
      path: '/stripe',
      component: () => import('@/views/products/Products.vue'),
      name: 'Products',
      meta: { title: 'Products' },
    },
  ],
};
export default paymentRoutes;
