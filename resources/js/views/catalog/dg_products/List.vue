<template>
  <div class="app-container">
    <div class="filter-container">
      <AbstractTable
        :data="data"
        :with-actions="true"
        :target="'dg/products'"
        :is-on-load="isLoad"
        :columns="[
          { name: 'ID', key: 'id', width: 65, align: 'center' },
          { name: 'Имя', key: 'name', minWidth: '100px',/* render: (banner) => banner.type.name*/ },
          { name: 'Брендированное имя', key: 'title', minWidth: '40px' },
          { name: 'Код', key: 'code', minWidth: '40px' },
          { name: 'Сортировка', key: 'sort', minWidth: '50px' },
          { name: 'Активный', key: 'is_active', minWidth: '30px', render: (item) => {return item.is_active? 'Да' : 'Нет'} },
          { name: 'Артикул Allgen', key: 'allgen_vendor_code', minWidth: '30px', },
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
  </div>
</template>

<script>
import AbstractTable from '@/components/AbstractTable/AbstractTable';
import DgProductResource from '@/api/dg_product';
import Pagination from '@/components/Pagination/index';
export default {
  name: 'DguProductList',
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
    console.log(32323232);
    this.setList();
  },
  methods: {
    async handleDelete(payload) {
      try {
        await (new DgProductResource()).destroy(payload.id);
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
      return (new DgProductResource()).list(this.query);
    },
    async setList() {
      this.isLoad = true;
      console.log(111);
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
