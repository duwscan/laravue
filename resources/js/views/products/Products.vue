<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <el-row v-if="!isLoading" :gutter="12">
      <el-col v-for="item in list" :key="item.id" :span="8">
        <CardItem :product="item" @on-buy="buyThis" />
      </el-col>
    </el-row>
    <el-dialog title="Shipping address" :visible.sync="dialogFormVisible">
      <h2>{{ product.name || '' }}</h2>
      <h3>{{ product.price || '' }}</h3>
      <div>
        <stripe-element-card
          ref="elementRef"
          :pk="publishableKey"
          @token="tokenCreated"
        />
      </div>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button type="primary" @click="submit">Buy</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import { StripeElementCard } from '@vue-stripe/vue-stripe';
import CardItem from './components/CardItem.vue';
import Resource from '@/api/resource';
const paymentResource = new Resource('payment-stripe');
export default {
  components: {
    StripeElementCard,
    CardItem,
  },
  data() {
    const isLoading = true;
    this.publishableKey =
      'pk_test_51MCmggA1QCzj1sCUPbEVhtTjwZdhPmBSP8jRO9ucDdMdInFSa0HEMYouqd2LUJX3ZFkUgCk9Jt6ljzl8u3KERCc000HZXZtUa0';
    return {
      token: null,
      list: null,
      product: {
        name: '',
        price: '',
      },
      isLoading,
      dialogFormVisible: false,
    };
  },
  created() {
    this.getListProduct();
  },
  mounted() {
    console.log(this.list);
  },
  methods: {
    submit() {
      // this will trigger the process
      this.$refs.elementRef.submit();
    },
    async getListProduct() {
      const productResource = new Resource('products');
      const data = await productResource.list();
      this.list = data.data;
      this.isLoading = false;
      console.log(data);
    },
    async tokenCreated(token) {
      console.log(token);
      console.log(this.product.id);
      const { message, error } = await paymentResource.store({
        token: token.id,
        product_id: this.product.id,
      });
      if (message) {
        this.$message({
          message: message,
          type: 'success',
        });
        this.dialogFormVisible = false;
        this.$ref.elementRef.clear();
      }
      if (error) {
        this.$message({
          message: error,
          type: 'warning',
        });
      }
    },
    buyThis(data) {
      this.dialogFormVisible = true;
      this.product = data;
    },
  },
};
</script>
