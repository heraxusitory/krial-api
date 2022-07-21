<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-position="top">
      <el-card style="margin-bottom: 10px;">
        <div slot="header">
          <span>Основное</span>
        </div>
        <el-form-item label="Название" :error="errors.name" required>
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item label="Код" :error="errors.code">
          <el-input v-model="form.code" disabled />
        </el-form-item>
        <el-form-item v-if="!form.is_root" label="Сортировка" required :error="errors.sort">
          <el-input
            v-model="form.sort"
          />
          <el-form-item style="margin-top:20px;">
            <el-checkbox v-model="form.is_root" disabled>Корневой каталог</el-checkbox>
            <el-checkbox v-model="form.is_active">Активный</el-checkbox>
          </el-form-item>
        </el-form-item>
        <el-form-item v-if="!form.is_root" label="Медиа">
          <UploadImage
            :value-collection="form.image_urls"
            :multi="false"
            @input="(payload) => imgHook(payload, 'attachments')"
            @remove="(payload) => removeHook(payload,'attachments')"
            @errors="(payload) => imgError(payload, 'attachments')"
          />
        </el-form-item>
      </el-card>

      <el-card v-if="!form.is_root" style="margin-bottom: 10px;">
        <div slot="header" style="display: flex; justify-content: space-between">
          <div><span>Подборки</span></div>
          <el-button type="success" @click="addCompilation">Добавить</el-button>
        </div>
        <el-row v-if="form.compilations.length" :gutter="20">
          <el-col :span="8">
            <el-form-item label="Название" style="margin-bottom: 0;" />
          </el-col>
          <el-col :span="8">
            <el-form-item label="URL" style="margin-bottom: 0" />
          </el-col>
          <el-col :span="4" />
        </el-row>
        <el-row v-for="(compilation, key) in form.compilations" :key="key" :gutter="20">
          <el-col :span="8">
            <el-form-item>
              <el-input v-model="compilation.name" />
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item>
              <el-input v-model="compilation.url" />
            </el-form-item>
          </el-col>
          <el-col :span="4">
            <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDeleteCompilation(key)" />
          </el-col>
        </el-row>
      </el-card>

      <el-card>
        <div slot="header">
          <div><span>SEO</span></div>
        </div>
        <el-form-item label="title">
          <el-input v-model="form.seo_title" />
        </el-form-item>
        <el-form-item label="description">
          <el-input v-model="form.seo_description" />
        </el-form-item>
        <el-form-item label="h1">
          <el-input v-model="form.seo_h1" />
        </el-form-item>
      </el-card>

      <el-form-item style="margin-top: 15px;">
        <el-button type="success" :loading="requestSend" @click="submit">{{ $t('form.save') }}</el-button>
      </el-form-item>
    </el-form>
    <Loader :active="isLoad" />
  </div>
</template>

<script>
import Loader from '@/components/Loader/Loader';
import UploadImage from '@/components/Upload/UploadImage';
import CategoryResource from '../../../api/catalog/category/category';

export default {
  name: 'CategoryEdit',
  components: { Loader, UploadImage },
  data() {
    return {
      id: null,
      requestSend: false,
      isLoad: false,
      form: {
        name: '',
        code: '',
        sort: 100,
        is_root: false,
        is_active: false,
        seo_title: '',
        seo_description: '',
        seo_h1: '',
        compilations: [],
        image_url_ids: null,
        image_urls: [],
      },
      errors: {
        name: '',
        code: '',
        sort: '',
        is_root: '',
        is_active: '',
        seo_title: '',
        seo_description: '',
        seo_h1: '',
        image_url_ids: '',
      },
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    addCompilation() {
      this.form.compilations.push({
        name: '',
        url: '',
      });
    },
    handleDeleteCompilation(id) {
      const idx = this.form.compilations.findIndex(item => item.id === id);
      this.form.compilations.splice(idx, 1);
    },
    imgHook(payload, key) {
      console.log(payload);
      this.form.image_urls = [payload];
      this.form.image_url_ids = [payload.id];
      // this.traidingOption.attachments.push(payload);
      // this.traidingOption.attachment_ids = this.traidingOption.attachments.map(item => item.id);
    },
    removeHook(id, key) {
      this.form.image_urls = [];
      this.form.image_url_ids = null;
      // const idx = this.traidingOption.attachments.findIndex(item => item.id === id);
      // this.traidingOption.attachments.splice(idx, 1);
      // this.traidingOption.attachment_ids = this.traidingOption.attachments.map(item => item.id);
    },
    imgError(payload, key) {
      console.log(payload);
    },
    async submit() {
      this.requestSend = !this.requestSend;
      this.isLoad = true;
      this.clearAllErrors();

      try {
        if (!this.wasRecentlyCreated) {
          await (new CategoryResource()).store(this.form);
        } else {
          await (new CategoryResource()).update(this.id, this.form);
        }

        this.$router.push({ name: 'Categories' });
        this.$message({
          message: 'Категория успешно сохранена',
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
      for (const key in errors) {
        if (this.errors.hasOwnProperty(key)) {
          //   console.log(key);
          this.errors[key] = errors[key][0];
        }
      }
    },
    clearAllErrors() {
      this.errors.name = '';
      this.errors.code = '';
      this.errors.sort = '';
      this.errors.is_root = '';
      this.errors.is_active = '';
      this.errors.seo_title = '';
      this.errors.seo_description = '';
      this.errors.seo_h1 = '';
      this.errors.image_url_ids = '';
    },
    async fetchData() {
      this.isLoad = true;
      try {
        if (this.$route.params.id) {
          const { data } = await (new CategoryResource()).get(this.$route.params.id);
          this.wasRecentlyCreated = true;

          this.id = data.id;
          for (const key in this.form) {
            if (data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
        }
      } catch (e) {
        console.log(e);
        this.$router.push({ name: 'Page404' });
      } finally {
        setTimeout(() => {
          this.isLoad = false;
        }, 300);
      }
    },
  },
};
</script>

<style scoped>

</style>
