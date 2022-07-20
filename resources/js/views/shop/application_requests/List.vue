<template>
  <div class="app-container">
    <!--    <div class="filter-container">-->
    <!--      <el-button class="filter-item" style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">-->
    <!--        {{ $t('table.add') }}-->
    <!--      </el-button>-->
    <!--    </div>-->
    <AbstractTable
      :data="data"
      :with-actions="true"
      :target="'shop/application_requests'"
      :is-on-load="isLoad"
      :actions="['edit']"
      :columns="[
        { name: 'ID', key: 'id', width: 65, align: 'center' },
        { name: 'Имя', key: 'name', minWidth: '50px',/* render: (banner) => banner.type.name*/ },
        { name: 'Телефон', key: 'phone', minWidth: '40px' },
        { name: 'Почта', key: 'email', minWidth: '40px' },
        { name: 'Сортировка', key: 'sort', minWidth: '20px' },
      ]"
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
import ApplicationRequestResource from '@/api/shop/application_request';
export default {
  name: 'ApplicationRequestList',
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
    getList() {
      return (new ApplicationRequestResource()).list(this.query);
    },
    async setList() {
      this.isLoad = true;
      try {
        const response = await this.getList();
        const { meta } = response;
        this.query.page = parseInt(meta.current_page);
        this.query.total = parseInt(meta.total);
        this.query.limit = parseInt(meta.per_page);

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
