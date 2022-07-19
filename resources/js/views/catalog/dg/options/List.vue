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
      :target="'catalog/dgu/options'"
      :is-on-load="isLoad"
      :columns="[
        { name: 'ID', key: 'id', width: 65, align: 'center' },
        { name: 'Название', key: 'name', minWidth: '40px'},
        { name: 'Цена', key: 'price', minWidth: '40px'},
        { name: 'Группа', key: 'group_name', minWidth: '30px', render: (option) => option.group.name,},
        // { name: 'Медиа', key: 'image_url', minWidth:'40px'},
        { name: 'Описание', key: 'description', minWidth: '40px', editable: true, edit_type: 'textarea' },
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
import DgOptionResource from '@/api/catalog/dg/dg_option';

export default {
  name: 'DgOptionList',
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
    getList() {
      return (new DgOptionResource()).list(this.query);
    },
    handleCreate() {
      this.$router.push({ name: 'DguOptionEdit' });
    },
    async handleDelete(payload) {
      try {
        await (new DgOptionResource()).destroy(payload.id);
        this.$message({
          message: 'Опция успешно удалена',
          type: 'success',
          duration: 3 * 1000,
        });
      } catch (e) {
        console.log(e);
      } finally {
        await this.setList();
      }
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
        console.log(this.data);
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
