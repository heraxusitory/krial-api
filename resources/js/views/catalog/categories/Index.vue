<template>
  <div class="app-container">
    <AbstractTable
      :data="data"
      :with-actions="true"
      :target="'catalog/categories'"
      :is-on-load="isLoad"
      :actions="['edit']"
      :columns="[
        { name: 'ID', key: 'id', width: 65, align: 'center' },
        { name: 'Название', key: 'name', minWidth: '50px',},
        { name: 'Сортировка', key: 'sort', minWidth: '40px' },
        { name: 'Корневой каталог', key: 'is_root', minWidth: '40px', render: (category) => category.is_root ? 'Да' : 'Нет'},
        { name: 'Активный', key: 'is_active', minWidth: '40px', render: (category) => category.is_active ? 'Да' : 'Нет' },
        { name: 'SEO title', key: 'seo_title', minWidth: '40px' },
        { name: 'SEO description', key: 'seo_description', minWidth: '40px' },
        { name: 'SEO h1', key: 'seo_h1', minWidth: '40px' },
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
import CategoryResource from '@/api/catalog/category/category';
export default {
  name: 'CategoryList',
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
      return (new CategoryResource()).list(this.query);
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
