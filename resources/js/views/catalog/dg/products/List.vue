<template>
  <div class="app-container">
    <el-row type="flex" style="margin-bottom: 20px">
      <div class="header-buttons">
        <el-button style="margin-left: 10px;" type="primary" icon="el-icon-edit" @click="handleCreate">
          {{ $t('table.add') }}
        </el-button>
      </div>
      <template v-if="multipleSelectionRows.length > 0">
        <div style="margin-right: 10px">
          <el-select v-model="actualActionId" placeholder="Выбрать действие">
            <el-option
              v-for="(action, key) in actions"
              :key="key"
              :label="action.name"
              :value="key"
            />
          </el-select>
          <el-select v-if="actions[actualActionId] && actions[actualActionId].name === 'Указать серию'" v-model="actualSeriesId" filterable placeholder="Выбрать серию">
            <el-option
              v-for="series_item in series"
              :key="series_item.id"
              :label="series_item.name"
              :value="series_item.id"
            />
          </el-select>
        </div>
        <div v-if="isActiveSuccessBtn" style="margin-right: 5px">
          <el-button type="success" icon="el-icon-check" size="small" @click="submit" />
        </div>
        <div style="margin-right: 5px">
          <el-button type="danger" size="small" @click="handleReset">Сброс</el-button>
        </div>
      </template>
    </el-row>
    <AbstractTable
      ref="productsTable"
      :data="data"
      :with-actions="true"
      :target="'catalog/dgu/products'"
      :is-on-load="isLoad"
      :multiple-select="true"

      :columns="[
        { name: 'ID', key: 'id', width: 65, align: 'center' },
        { name: 'Имя', key: 'name', minWidth: '50px',/* render: (banner) => banner.type.name*/ },
        { name: 'Производитель', key: 'manufacture_name', minWidth: '40px' },
        { name: 'Двигатель', key: 'engine_manufacture_name', minWidth: '20px' },
        { name: 'Серия', key: 'series_id', minWidth: '20px', editable: true, edit_type: 'select', render: (product) => product.series ? product.series.name : '', options: series },
        { name: 'Бренд', key: 'title', minWidth: '20px' },
        { name: 'Код', key: 'code', minWidth: '40px' },
        { name: 'Сортировка', key: 'sort', minWidth: '25px' },
        { name: 'Актив', key: 'is_active', minWidth: '20px', render: (item) => {return item.is_active? 'Да' : 'Нет'} },
        { name: 'Артикул Allgen', key: 'allgen_vendor_code', minWidth: '30px', },
      ]"
      @delete="handleDelete"
      @multipleSelect="handleMultipleSelect"
      @updateRow="updatePropertyRow"
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
import DgProductResource from '@/api/catalog/dg/dg_product';
import Pagination from '@/components/Pagination';
import DgSeriesResource from '@/api/catalog/dg/dg_series';
export default {
  name: 'DguProductList',
  components: { AbstractTable, Pagination },
  data() {
    return {
      data: [],
      isLoad: true,
      requestSend: false,
      query: {
        title: '',
        total: 0,
        page: 1,
        limit: 10,
      },
      series: [],
      actualActionId: null,
      actualSeriesId: null,
      multipleSelectionRows: [],
      form: {
        dg_products: [],
        is_active: false,
        series_id: null,
      },
      actions: [
        { name: 'Активировать', action: 'activate' },
        { name: 'Деактивировать', action: 'deactivate' },
        { name: 'Указать серию', action: 'set_series' },
        { name: 'Убрать серию', action: 'unset_series' },
      ],
    };
  },
  computed: {
    isActiveSuccessBtn: function() {
      if (this.actions[this.actualActionId] &&
        this.actions[this.actualActionId].action === 'set_series' &&
        this.actualSeriesId) {
        return true;
      } else if (this.actions[this.actualActionId] && this.actions[this.actualActionId].action !== 'set_series') {
        return true;
      }
      return false;
    },
  },
  created() {
    this.setList();
  },
  methods: {
    async updatePropertyRow(id, key, value) {
      this.isLoad = true;
      try {
        const form_data = {};
        form_data[key] = value;
        await (new DgProductResource()).updateProductRow(id, form_data);
        this.getList();
      } catch (e) {
        console.log(e);
      } finally {
        this.isLoad = false;
      }
    },
    async submit() {
      this.requestSend = !this.requestSend;
      this.isLoad = true;

      try {
        this.form.dg_products = this.multipleSelectionRows;
        this.form.series_id = null;
        if (this.actions[this.actualActionId].action === 'set_series') {
          this.form.series_id = this.actualSeriesId;
          await (new DgProductResource()).setSeries(this.form);
        } else if (this.actions[this.actualActionId].action === 'unset_series') {
          await (new DgProductResource()).setSeries(this.form);
        } else if (this.actions[this.actualActionId].action === 'activate') {
          this.form.is_active = true;
          await (new DgProductResource()).changeActive(this.form);
        } else if (this.actions[this.actualActionId].action === 'deactivate') {
          this.form.is_active = false;
          await (new DgProductResource()).changeActive(this.form);
        }
        await this.setList();
        // if (!this.wasRecentlyCreated) {
        //   await (new DgProductResource()).store(this.form);
        // } else {
        //   await (new DgProductResource()).update(this.id, this.form);
        // }

        // this.$router.push({ name: 'DguProducts' });
        this.$message({
          message: 'Успешно',
          type: 'success',
          duration: 5 * 1000,
        });
      } catch ({ response }) {
        this.showResponseErrors(response);
      } finally {
        this.requestSend = false;
        this.isLoad = false;
      }
    },
    showResponseErrors(response) {
      const { errors } = response.data;
      console.log(errors);
      // for (const key in errors) {
      //   if (this.errors.hasOwnProperty(key)) {
      //     //   console.log(key);
      //     this.errors[key] = errors[key][0];
      //   }
      // }
    },
    handleMultipleSelect(rows) {
      this.multipleSelectionRows = rows;
    },
    handleReset() {
      this.$refs.productsTable.clearSelection();
    },
    handleCreate() {
      this.$router.push({ name: 'DguProductEdit' });
    },
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
    async fetchSeries() {
      return await (new DgSeriesResource()).list();
    },
    async setList() {
      this.isLoad = true;
      try {
        const series_data = await this.fetchSeries();
        this.series = series_data.data;
        const response = await this.getList();
        const { meta } = response;
        this.query.page = parseInt(meta.pagination.current_page);
        this.query.total = parseInt(meta.pagination.total);
        this.query.limit = parseInt(meta.pagination.per_page);

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
.header-buttons {
  margin-right: 20px;
}
</style>
