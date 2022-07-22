<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-position="top">
      <el-form-item label="Тип баннера" required :error="errors.type">
        <el-select v-model="form.type" placeholder="Выберите тип">
          <el-option
            v-for="(type, key) in types"
            :key="key"
            :label="type"
            :value="key"
          >
            {{ type }}
          </el-option>
        </el-select>
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
      <el-form-item label="Заголовок" :error="errors.header" required>
        <el-input v-model="form.header" />
      </el-form-item>
      <el-form-item label="Описание" required :error="errors.description">
        <el-input
          v-model="form.description"
          type="textarea"
          :autosize="{ minRows: 2}"
          placeholder="Введите текст"
        />
      </el-form-item>
      <el-form-item v-show="form.type === 'form'" label="Текст кнопки" :error="errors.button_text" required>
        <el-input v-model="form.button_text" />
      </el-form-item>
      <el-form-item v-show="form.type === 'information'" label="URL" :error="errors.url" required>
        <el-input v-model="form.url" />
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
import MarketingBannerResource from '@/api/catalog/marketing/banner';
import CategoryResource from '@/api/catalog/category/category';

export default {
  name: 'MarketingBannerEdit',
  components: { Loader, UploadImage },
  data() {
    return {
      id: null,
      requestSend: false,
      isLoad: false,
      types: [],
      form: {
        is_active: false,
        sort: 100,
        type: '',
        header: '',
        button_text: '',
        url: '',
        description: '',
        category_ids: [],
        categories: [],
        image_url_ids: null,
        image_urls: [],
      },
      errors: {
        is_active: '',
        sort: '',
        type: '',
        header: '',
        button_text: '',
        url: '',
        description: '',
        image_url_ids: '',
        image_urls: '',
      },
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    imgHook(payload, key) {
      this.form.image_urls = [payload];
      this.form.image_url_ids = [payload.id];
    },
    removeHook(id, key) {
      this.form.image_urls = [];
      this.form.image_url_ids = null;
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
          await (new MarketingBannerResource()).store(this.form);
        } else {
          await (new MarketingBannerResource()).update(this.id, this.form);
        }

        this.$router.push({ name: 'MarketingBanners' });
        this.$message({
          message: 'Маркетинговый баннер успешно сохранен',
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
      this.errors.is_active = '';
      this.errors.sort = '';
      this.errors.type = '';
      this.errors.header = '';
      this.errors.button_text = '';
      this.errors.url = '';
      this.errors.description = '';
      this.errors.image_url_ids = '';
      this.errors.image_urls = '';
    },
    async fetchTypes() {
      this.types = await (new MarketingBannerResource().getTypes());
    },
    async fetchCategories() {
      const { data } = await (new CategoryResource().list());
      this.form.categories = data;
    },
    async fetchData() {
      this.isLoad = true;
      try {
        await this.fetchTypes();
        await this.fetchCategories();
        if (this.$route.params.id) {
          const { data } = await (new MarketingBannerResource()).get(this.$route.params.id);
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
