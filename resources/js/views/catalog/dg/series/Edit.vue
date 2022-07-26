<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-position="top">
      <el-form-item label="Название" :error="errors.name" required>
        <el-input v-model="form.name" @input="translateCode" />
      </el-form-item>
      <el-form-item label="Код" :error="errors.code" required>
        <el-input v-model="form.code" />
      </el-form-item>

      <el-form-item>
        <el-button icon="el-icon-circle-plus" type="success" @click="addNewCard">
          Добавить
        </el-button>
      </el-form-item>

      <VersionCard
        v-for="option in form.traiding_options"
        :key="option.id"
        :traiding-option="option"
        :dg-versions="dg_versions"
        :disabled-dg-version-ids="disabled_dg_version_ids"
        @changeVersion="changeVersion"
        @remove="removeCard"
      />
      <el-form-item style="margin-top: 20px;">
        <el-button type="success" :loading="requestSend" @click="submit">{{ $t('form.save') }}</el-button>
      </el-form-item>
    </el-form>
    <Loader :active="isLoad" />
  </div>
</template>

<script>
import VersionCard from '@/views/catalog/dg/series/components/VersionCard';
import Loader from '@/components/Loader/Loader';
import helpers from '../../../../vendor/helpers';
import DgSeriesResource from '@/api/catalog/dg/dg_series';
import DgVersionResource from '@/api/catalog/dg/dg_version';

export default {
  name: 'SeriesEdit',
  components: { VersionCard, Loader },
  data() {
    return {
      id: null,
      activeTab: 'main',
      requestSend: false,
      wasRecentlyCreated: false,
      isLoad: false,
      dg_engine_manufacturers: [],
      dg_versions: [],
      disabled_dg_version_ids: [],
      form: {
        name: '',
        code: '',
        traiding_options: [],
      },
      errors: {
        name: '',
        code: '',
      },
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    translateCode() {
      this.form.code = helpers.string.slug(this.form.name);
    },
    changeVersion() {
      this.disabled_dg_version_ids = [];
      for (const option of this.form.traiding_options) {
        this.disabled_dg_version_ids.push(option.version.id);
      }
    },
    addNewCard() {
      if (this.form.traiding_options.length < this.dg_versions.length) {
        this.form.traiding_options.unshift({
          price: '',
          attachments: [],
          version: {
            id: null,
          },
        });
      }
    },
    removeCard(key) {
      const idx = this.form.traiding_options.findIndex(item => item.id === key);
      this.form.traiding_options.splice(idx, 1);
      this.changeVersion();
    },
    async submit() {
      this.requestSend = !this.requestSend;
      this.isLoad = true;
      this.clearAllErrors();

      try {
        if (!this.wasRecentlyCreated) {
          await (new DgSeriesResource()).store(this.form);
        } else {
          await (new DgSeriesResource()).update(this.id, this.form);
        }

        this.$router.push({ name: 'DguSeries' });
        this.$message({
          message: 'Серия успешно создана',
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
          //   console.log(key);
          this.errors[key] = errors[key][0];
        }
      }
    },
    clearAllErrors() {
      this.errors.name = '';
      this.errors.code = '';
    },
    async fetchDgVersions() {
      const { data } = await (new DgVersionResource()).list();
      this.dg_versions = data;
    },
    async fetchData() {
      this.isLoad = true;
      try {
        await this.fetchDgVersions();
        // await this.fetchDgProperties();
        if (this.$route.params.id) {
          const { data } = await (new DgSeriesResource()).get(this.$route.params.id);
          this.wasRecentlyCreated = true;

          this.id = data.id;
          for (const key in this.form) {
            if (data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
          this.changeVersion();
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
