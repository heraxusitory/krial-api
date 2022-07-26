<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
        {{ $t('table.add') }}
      </el-button>
    </div>
    <AbstractTable
      :data="data"
      :with-actions="true"
      :target="'catalog/dgu/series'"
      :is-on-load="isLoad"
      :columns="[
        { name: 'ID', key: 'id', width: 65, align: 'center' },
        { name: 'Название', key: 'name', minWidth: '50px', },
        { name: 'Код', key: 'code', minWidth: '40px' },
      ]"
      @delete="handleDelete"
    />
    <pagination
      v-show="query.total>0"
      :total="query.total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      @pagination="setList"
    />
  </div>
</template>
<script>
import AbstractTable from '@/components/AbstractTable/AbstractTable';
import Pagination from '@/components/Pagination';
import DgSeriesResource from '@/api/catalog/dg/dg_series';

export default {
  name: 'SeriesList',
  components: { AbstractTable, Pagination },
  data() {
    return {
      data: [],
      isLoad: true,
      query: {
        title: '',
        total: 0,
        page: 1,
        limit: 10,
      },
    };
  },
  created() {
    this.setList();
  },
  methods: {
    handleCreate() {
      this.$router.push({ name: 'DguSeriesEdit' });
    },
    async handleDelete(payload) {
      try {
        await (new DgSeriesResource()).destroy(payload.id);
        this.$message({
          message: 'Товар успешно удален',
          type: 'success',
          duration: 3 * 1000,
        });
      } catch (e) {
        console.log(e);
      } finally {
        await this.setList();
      }
    },
    getList() {
      return (new DgSeriesResource()).list(this.query);
    },
    async setList() {
      this.isLoad = true;
      try {
        const response = await this.getList();
        // const { meta } = response;
        this.query.page = parseInt(response.meta.current_page);
        this.query.total = parseInt(response.meta.total);
        this.query.limit = parseInt(response.meta.per_page);

        const { data } = response;
        this.data = data;
      } catch (e) {
        console.log(e);
      } finally {
        this.isLoad = false;
      }
    },
  },
};
</script>

<style scoped>

</style>
