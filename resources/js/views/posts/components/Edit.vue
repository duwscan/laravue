<!-- eslint-disable indent -->
<!-- eslint-disable vue/max-attributes-per-line -->
<template>
  <div class="edit-post">
    <el-button
      type="primary"
      icon="el-icon-edit"
      circle
      @click="dialogFormVisible = true"
    />
    <el-dialog title="Shipping address" :visible.sync="dialogFormVisible">
      <el-form :model="form">
        <el-form-item label="Post title">
          <el-input v-model="form.title" autocomplete="off" />
        </el-form-item>
        <el-form-item label="Post content">
          <el-input v-model="form.content" type="textarea" autocomplete="off" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false">Cancel</el-button>
        <el-button type="primary" @click="updatePost">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import request from '@/utils/request';
export default {
  props: {
    post: {
      type: Object,
      required: true,
      default: () => ({ title: '', content: '' }),
    },
  },
  data() {
    return {
      dialogTableVisible: false,
      dialogFormVisible: false,
      form: {
        title: this.post.title,
        content: this.post.content,
      },
    };
  },
  methods: {
    async updatePost() {
      const h = this.$createElement;
      try {
        const data = await request({
          url: '/update-post/' + this.post.id,
          method: 'post',
          data: this.form,
        });
        if (data.message) {
          this.$emit('update-complete', data.post);
          this.$message({
            message: h('p', null, [
              h('span', null, 'Success'),
              h('i', { style: 'color: green' }),
            ]),
          });
        }
      } catch (error) {
        this.$message({
          message: h('p', null, [
            h('span', null, 'Error'),
            h('i', { style: 'color: red' }),
          ]),
        });
      }
      this.dialogFormVisible = false;
    },
  },
};
</script>

<style></style>
