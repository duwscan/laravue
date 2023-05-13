<template>
  <el-card shadow="always">
    <h5>{{ product.name }}</h5>
    <h6>{{ product.price }}</h6>
    <el-button type="text" @click="buyItem">Buy</el-button>
  </el-card>
</template>

<script>
export default {
  name: 'CardItem',
  components: {},
  props: {
    product: {
      type: Object,
      required: true,
      default: () => ({ name: '', price: '', id: 0 }),
    },
  },
  methods: {
    buyItem() {
      this.$emit('on-buy', this.product);
    },
  },
};
</script>

<style></style>
