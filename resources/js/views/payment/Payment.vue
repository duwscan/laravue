<template>
  <div>
    <stripe-element-card
      ref="elementRef"
      :pk="publishableKey"
      @token="tokenCreated"
    />
    <button @click="submit">Generate token</button>
  </div>
</template>

<script>
import { StripeElementCard } from '@vue-stripe/vue-stripe';
import Resource from '@/api/resource';
const paymentResource = new Resource('payment-stripe');
export default {
  components: {
    StripeElementCard,
  },
  data() {
    this.publishableKey =
      'pk_test_51MCmggA1QCzj1sCUPbEVhtTjwZdhPmBSP8jRO9ucDdMdInFSa0HEMYouqd2LUJX3ZFkUgCk9Jt6ljzl8u3KERCc000HZXZtUa0';
    return {
      token: null,
    };
  },
  methods: {
    submit() {
      // this will trigger the process
      this.$refs.elementRef.submit();
    },
    async tokenCreated(token) {
      console.log(token);
      // default buy post 52;
      paymentResource.uri = `${paymentResource.uri}`;
      const { data, message, error } = await paymentResource.store({
        token: token.id,
      });
      console.log(data);
      if (message) {
        console.log(message);
      }
      if (error) {
        console.log(error);
      }
    },
  },
};
</script>
