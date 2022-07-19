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
      :target="'catalog/dgu/options'"
      :is-on-load="isLoad"
      :columns="[
        { name: 'ID', key: 'id', width: 65, align: 'center' },
        { name: 'Название', key: 'name', minWidth: '40px'},
        { name: 'Цена', key: 'price', minWidth: '40px'},
        { name: 'Группа', key: 'group_name', minWidth: '30px', render: (property) => property.group.name,},
        { name: 'Медиа', key: 'image_url', minWidth: '40px'},
        { name: 'Описание', key: 'description', minWidth: '40px', editable: true, edit_type: 'textarea' },
      ]"
      @delete="handleDelete"
      @updateRow="updatePropertyRow"
    />
    <pagination
      v-show="query.total>0"
      :total="query.total"
      :page.sync="query.page"
      :limit.sync="query.limit"
      @pagination="setList"
    />
    <!--    <Loader :active="isLoad" />-->
  </div>
</template>

<script>
import AbstractTable from '@/components/AbstractTable/AbstractTable';
import Pagination from '@/components/Pagination';
import DgOptionResource from '@/api/catalog/dg/dg_option';

export default {
  name: 'OptionList',
  components: { AbstractTable, Pagination },
  data() {
    return {
      data: [],
      filterTypes: [],
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
    changeValue(checked) {
      console.log(checked);
    },
    handleCreate() {

    },
    getList() {
      return (new DgOptionResource()).list(this.query);
    },
    async setList() {
      this.isLoad = true;
      try {
        const response = await this.getList();
        this.filterTypes = await this.getFilterTypes();
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
