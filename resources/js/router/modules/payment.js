import Layout from '@/layout';

const paymentRoutes = {
  path: '/payment',
  component: Layout,
  redirect: '/payment',
  name: 'Payment',
  meta: {
    title: 'payment',
    icon: 'cash',
    permissions: [],
  },
  children: [
    {
      path: '/stripe',
      component: () => import('@/views/payment/Payment.vue'),
      name: 'PaymentStripe',
      meta: { title: 'Stripe Payment' },
    },
  ],
};
export default paymentRoutes;
