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
      :target="'catalog/marketing/banners'"
      :is-on-load="isLoad"
      :columns="[
        { name: 'ID', key: 'id', width: 65, align: 'center' },
        { name: 'Заголовок', key: 'header', minWidth: '40px' },
        { name: 'Описание', key: 'description', minWidth: '40px' },
        { name: 'Тип баннера', key: 'type', minWidth: '40px', render: (banner) => {
          if (banner.type === 'information') {
            return 'Информационный';
          } else if (banner.type === 'form') {
            return 'Заявка';
          }
          else return '';
        }},
        { name: 'Активный', key: 'is_active', minWidth: '50px', },
        { name: 'Сортировка', key: 'sort', minWidth: '40px' },
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
import MarketingBannerResource from '@/api/catalog/marketing/banner';

export default {
  name: 'MarketingBannerList',
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
      types: [],
    };
  },
  created() {
    this.setList();
  },
  methods: {
    handleCreate() {
      this.$router.push({ name: 'MarketingBannerEdit' });
    },
    async handleDelete(payload) {
      try {
        await (new MarketingBannerResource()).destroy(payload.id);
        this.$message({
          message: 'Маркетинговый баннер успешно удален',
          type: 'success',
          duration: 3 * 1000,
        });
      } catch (e) {
        console.log(e);
      } finally {
        await this.setList();
      }
    },
    async fetchTypes() {
      this.types = await (new MarketingBannerResource().getTypes());
    },
    getList() {
      return (new MarketingBannerResource()).list(this.query);
    },
    async setList() {
      this.isLoad = true;
      try {
        const response = await this.getList();
        this.types = await this.fetchTypes();
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
