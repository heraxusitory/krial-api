<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-position="top">
      <el-form-item label="Название" :error="errors.name" required>
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="Описание" required :error="errors.description">
        <el-input
          v-model="form.description"
          type="textarea"
          :autosize="{ minRows: 2}"
          placeholder="Введите текст"
        />
      </el-form-item>
      <el-form-item label="Группа опций" required :error="errors.dg_option_group_id">
        <el-select v-model="form.dg_option_group_id" placeholder="Выберите группу">
          <el-option
            v-for="group in dg_option_groups"
            :key="group.id"
            :label="group.name"
            :value="group.id"
          >{{ group.name }}
          </el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Цена" required :error="errors.price">
        <el-input-number v-model="form.price" />
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
import DgOptionResource from '@/api/catalog/dg/dg_option';
import UploadImage from '@/components/Upload/UploadImage';
import DgOptionGroupResource from '@/api/catalog/dg/dg_option_group';

export default {
  name: 'DgOptionEdit',
  components: { Loader, UploadImage },
  data() {
    return {
      id: null,
      requestSend: false,
      isLoad: false,
      dg_option_groups: [],
      form: {
        name: '',
        description: '',
        price: '',
        image_url_ids: null,
        dg_option_group_id: null,
        image_urls: [],
      },
      errors: {
        name: '',
        description: '',
        price: '',
        image_url_ids: '',
        dg_option_group_id: '',
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
    async submit() {
      this.requestSend = !this.requestSend;
      this.isLoad = true;
      this.clearAllErrors();

      try {
        if (!this.wasRecentlyCreated) {
          await (new DgOptionResource()).store(this.form);
        } else {
          await (new DgOptionResource()).update(this.id, this.form);
        }

        this.$router.push({ name: 'DguOptions' });
        this.$message({
          message: 'Опция успешно сохранена',
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
      this.errors.description = '';
      this.errors.price = '';
      this.errors.image_url_ids = '';
      this.errors.dg_option_group_id = '';
    },
    async fetchDgOptionGroups() {
      const { data } = await (new DgOptionGroupResource()).list();
      this.dg_option_groups = data;
    },
    async fetchData() {
      this.isLoad = true;
      try {
        await this.fetchDgOptionGroups();
        if (this.$route.params.id) {
          const { data } = await (new DgOptionResource()).get(this.$route.params.id);
          this.wasRecentlyCreated = true;

          this.id = data.id;
          for (const key in this.form) {
            if (data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
          // this.image_urls = [data];
          // this.form.image_url_ids = [payload.id];
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
