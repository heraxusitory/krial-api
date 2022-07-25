<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-position="top">
      <el-form-item label="Название" :error="errors.name" required>
        <el-input v-model="form.name" @input="translateCode" />
      </el-form-item>
      <el-form-item label="Код" :error="errors.code">
        <el-input v-model="form.code" />
      </el-form-item>
      <el-form-item label="Сортировка" required :error="errors.sort">
        <el-input
          v-model="form.sort"
        />
      </el-form-item>
      <el-form-item label="Активность">
        <el-switch
          v-model="form.is_active"
          active-color="#13ce66"
          inactive-color="#ff4949"
        />
      </el-form-item>
      <el-form-item label="Описание" required :error="errors.description">
        <el-input
          v-model="form.description"
          type="textarea"
          :autosize="{ minRows: 2}"
          placeholder="Введите текст"
        />
      </el-form-item>
      <el-form-item label="Категория" required>
        <el-select v-model="form.category_ids" multiple placeholder="Выберите категорию">
          <el-option
            v-for="category in form.categories"
            :key="category.id"
            :label="category.name"
            :value="category.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Медиа">
        <UploadImage
          :value-collection="form.image_urls"
          :multi="false"
          @input="(payload) => imgHook(payload, 'attachments')"
          @remove="(payload) => removeHook(payload,'attachments')"
          @errors="(payload) => imgError(payload, 'attachments')"
        />
      </el-form-item>

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
import helpers from '@/vendor/helpers';
import BenefitResource from '@/api/catalog/marketing/benefit';
import CategoryResource from '@/api/catalog/category/category';

export default {
  name: 'BenefitEdit',
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
        description: '',
        is_active: false,
        category_ids: [],
        categories: [],
        image_url_ids: null,
        image_urls: [],
      },
      errors: {
        name: '',
        code: '',
        description: '',
        sort: '',
        is_active: '',
        image_url_ids: '',
      },
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
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
    translateCode() {
      this.form.code = helpers.string.slug(this.form.name);
    },
    async submit() {
      this.requestSend = !this.requestSend;
      this.isLoad = true;
      this.clearAllErrors();

      try {
        if (!this.wasRecentlyCreated) {
          await (new BenefitResource()).store(this.form);
        } else {
          await (new BenefitResource()).update(this.id, this.form);
        }

        this.$router.push({ name: 'Benefits' });
        this.$message({
          message: 'Преимущество успешно сохранено',
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
      for (const key in errors) {
        if (this.errors.hasOwnProperty(key)) {
          this.errors[key] = errors[key][0];
        }
      }
    },
    clearAllErrors() {
      this.errors.name = '';
      this.errors.code = '';
      this.errors.sort = '';
      this.errors.is_active = '';
      this.errors.description = '';
      this.errors.image_url_ids = '';
    },
    async fetchCategories() {
      const { data } = await (new CategoryResource().list());
      this.form.categories = data;
    },
    async fetchData() {
      this.isLoad = true;
      try {
        await this.fetchCategories();
        if (this.$route.params.id) {
          const { data } = await (new BenefitResource()).get(this.$route.params.id);
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
