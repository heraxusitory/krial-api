<template>
  <div class="app-container">
    <div class="filter-container">
      <!--      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">-->
      <!--        {{ $t('table.add') }}-->
      <!--      </el-button>-->
    </div>
    <AbstractTable
      :data="data"
      :with-actions="false"
      :target="'catalog/dgu/property_groups'"
      :is-on-load="isLoad"
      :columns="[
        { name: 'ID', key: 'id', width: 65, align: 'center' },
        { name: 'Название', key: 'name', minWidth: '50px',/* render: (banner) => banner.type.name*/ },
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
import DgOptionGroupResource from '@/api/catalog/dg/dg_option_group';

export default {
  name: 'OptionGroupList',
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

    },
    handleDelete() {},
    getList() {
      return (new DgOptionGroupResource()).list(this.query);
    },
    async setList() {
      this.isLoad = true;
      try {
        const response = await this.getList();
        // const { meta } = response;
        this.query.page = parseInt(response.current_page);
        this.query.total = parseInt(response.total);
        this.query.limit = parseInt(response.per_page);

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
