<template>
  <div>
    <el-table
      v-loading="listLoading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%"
    >
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column width="180px" align="center" label="Created At">
        <template slot-scope="scope">
          <span>{{
            scope.row.created_at | parseTime('{y}-{m}-{d} {h}:{i}')
          }}</span>
        </template>
      </el-table-column>

      <el-table-column width="200px" align="center" label="Author">
        <template slot-scope="scope">
          <span>{{ scope.row.user.name }}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="200px" label="Title">
        <template slot-scope="{ row }">
          <el-collapse accordion>
            <el-collapse-item :title="row.title">
              <div>
                {{ row.content }}
              </div>
            </el-collapse-item>
          </el-collapse>
          <!-- <span>{{ row.title }}</span> -->
        </template>
      </el-table-column>
      <el-table-column align="center" label="Actions" width="120">
        <template slot-scope="scope">
          <div v-permission="['manage post']" class="action-to-posts">
            <Edit :post="scope.row" @update-complete="updateList" />
            <el-button
              icon="el-icon-delete"
              circle
              type="danger"
              @click="deleteList(scope.row.id)"
            />
          </div>
        </template>
      </el-table-column>
    </el-table>
    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getList"
    />
  </div>
</template>

<script>
import Edit from './components/Edit.vue';
import Pagination from '@/components/Pagination';
import Resource from '@/api/resource';
const postsResource = new Resource('posts');
import permission from '@/directive/permission/index.js';
export default {
  name: 'PostList',
  components: { Pagination, Edit },
  directives: { permission },
  data() {
    return {
      list: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data } = await postsResource.list(this.listQuery);
      this.list = data.data;
      this.total = data.total;
      this.listLoading = false;
      console.log(this.total);
    },
    updateList(updatedPost) {
      this.getList();
    },
    async deleteList(id) {
      const h = this.$createElement;
      try {
        const data = await postsResource.destroy(id);
        if (data.message) {
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

      this.getList();
    },
  },
};
</script>

<style>
.action-to-posts {
  display: flex;
  gap: 10%;
}
</style>
