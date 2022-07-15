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
      :target="'catalog/dgu/properties'"
      :is-on-load="isLoad"
      :columns="[
        { name: 'ID', key: 'id', width: 65, align: 'center' },
        { name: 'Название', key: 'name', minWidth: '40px'},
        { name: 'Код', key: 'code', minWidth: '30px' },
        { name: 'Группа', key: 'group_name', minWidth: '30px', render: (property) => property.group.name,},
        { name: 'Фильтруемое', key: 'is_filterable', minWidth: '20px', editable: true, edit_type: 'checkbox' },
        { name: 'Главная в группе', key: 'is_main_in_group', minWidth: '20px', editable: true, edit_type: 'checkbox' },
        { name: 'В заголовок деталки', key: 'is_main_in_header', minWidth: '25px', editable: true, edit_type: 'checkbox' },
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
import DgPropertyResource from '@/api/catalog/dg/dg_property';
// import Loader from '@/components/Loader/Loader';

export default {
  name: 'DgPropertyList',
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
    changeValue(checked) {
      console.log(checked);
    },
    handleCreate() {

    },
    handleDelete() {},
    async updatePropertyRow(id, key, value) {
      this.isLoad = true;
      try {
        const form_data = {};
        form_data[key] = value;
        await (new DgPropertyResource()).update(id, form_data, this.query);
        // const { meta } = response;
        // this.query.page = parseInt(response.current_page);
        // this.query.total = parseInt(response.total);
        // this.query.limit = parseInt(response.per_page);

        this.getList();
      } catch (e) {
        console.log(e);
      } finally {
        this.isLoad = false;
      }
    },
    getList() {
      return (new DgPropertyResource()).list(this.query);
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
