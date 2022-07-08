<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-position="top">
      <el-tabs v-model="activeTab">
        <el-tab-pane label="Главная" name="main">
          <el-form-item label="Название" :error="errors.name" required>
            <el-input v-model="form.name" @input="translateCode" />
          </el-form-item>
          <el-form-item label="Код" :error="errors.code" required>
            <el-input v-model="form.code" />
            <!--            <template>{{test_code}}</template>-->
          </el-form-item>
          <el-form-item label="Активность">
            <el-switch
              v-model="form.is_active"
              active-color="#13ce66"
              inactive-color="#ff4949"
            />
          </el-form-item>

          <el-form-item label="Брендирование">
            <el-row>
              <el-col :span="1">
                <el-switch
                  v-model="form.is_active_second_name"
                  active-color="#13ce66"
                  inactive-color="#ff4949"
                />
              </el-col>
              <el-col :span="23">
                <el-form-item :error="errors.second_name">
                  <el-input v-show="form.is_active_second_name" v-model="form.second_name" :disabled="!form.is_active_second_name" />
                </el-form-item>
              </el-col>
            </el-row>
          </el-form-item>

          <el-form-item label="Производитель" required :error="errors.manufacture_id">
            <el-select v-model="form.manufacture_id" placeholder="Выберите производителя">
              <el-option
                v-for="manufacture in dg_manufacturers"
                :key="manufacture.id"
                :label="manufacture.name"
                :value="manufacture.id"
              >{{ manufacture.name }}
              </el-option>
            </el-select>
          </el-form-item>

          <el-form-item label="Производитель двигателя" required :error="errors.engine_manufacture_id">
            <el-select v-model="form.engine_manufacture_id" placeholder="Выберите производителя">
              <el-option
                v-for="engine_manufacture in dg_engine_manufacturers"
                :key="engine_manufacture.id"
                :label="engine_manufacture.name"
                :value="engine_manufacture.id"
              >{{ engine_manufacture.name }}
              </el-option>
            </el-select>
          </el-form-item>

          <el-form-item label="Внутренний артикул">
            <el-input
              v-model="form.internal_vendor_code"
            />
          </el-form-item>

          <el-form-item label="Описание">
            <el-input
              v-model="form.description"
              type="textarea"
              :autosize="{ minRows: 2}"
              placeholder="Введите текст"
            />
          </el-form-item>

          <!--          <el-form-item>-->
          <!--            <el-button type="success" :loading="requestSend" @click="submit">{{ $t('form.save') }}</el-button>-->
          <!--          </el-form-item>-->
        </el-tab-pane>

        <el-tab-pane label="Варианты исполнения" name="options">
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
        </el-tab-pane>
        <el-tab-pane label="Характеристки" name="characteristics" />
      </el-tabs>

      <el-form-item>
        <el-button type="success" :loading="requestSend" @click="submit">{{ $t('form.save') }}</el-button>
      </el-form-item>
    </el-form>
    <Loader :active="isLoad" />
  </div>
</template>

<script>
import Loader from '@/components/Loader/Loader';
import DgManufactureResource from '@/api/dg_manufacture';
import DgEngineManufactureResource from '@/api/dg_engine_manufacture';
import DgProductResource from '@/api/dg_product';
import DgVersionResource from '@/api/dg_version';
import VersionCard from '@/views/catalog/dg/products/components/VersionCard';
import helpers from '@/vendor/helpers';
// import DgTraidingOptionResource from '@/api/dg_traiding_option';

export default {
  name: 'DguProductEdit',
  components: { VersionCard, Loader },
  data() {
    return {
      id: null,
      // allow_fields: {
      //   name:
      // },
      // test_code: '',
      activeTab: 'main',
      requestSend: false,
      wasRecentlyCreated: false,
      isLoad: false,
      dg_manufacturers: [],
      dg_engine_manufacturers: [],
      dg_versions: [],
      disabled_dg_version_ids: [],
      // dg_traiding_options: [],
      form: {
        name: '',
        code: '',
        manufacture_id: null,
        engine_manufacture_id: null,
        is_active_second_name: false,
        second_name: '',
        is_active: false,
        traiding_options: [],
        description: '',
        internal_vendor_code: '',
      },
      errors: {
        name: '',
        code: '',
        is_active: '',
        manufacture_id: '',
        engine_manufacture_id: '',
        is_active_second_name: '',
        second_name: '',
        // traiding_options: [],
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
    // showErrorTraidingOption(key) {
    //   // console.log(this.errors);
    //   return this.errors.traiding_options + '.' + key + '.version_id' ?? '';
    // },
    changeVersion() {
      this.disabled_dg_version_ids = [];
      console.log(this.form.traiding_options);
      for (const option of this.form.traiding_options) {
        this.disabled_dg_version_ids.push(option.version.id);
      }
      console.log('change_version', this.disabled_dg_version_ids);
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
      console.log(key);
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
          await (new DgProductResource()).store(this.form);
        } else {
          await (new DgProductResource()).update(this.id, this.form);
        }

        this.$router.push({ name: 'DguProducts' });
        this.$message({
          message: 'Продукт ДГУ успешно создан',
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
      // console.log(this.errors);
    },
    clearAllErrors() {
      // this.errors.title = '';
      // this.errors.title_color = '';
      // this.errors.type_id = '';
      // this.errors.background_color = '';
      // this.errors.cover_image = '';
      // this.errors.button_link = '';
      // this.errors.button_color = '';
      // this.errors.button_title = '';
      // this.errors.date = '';
      // this.errors.date_end = '';
      // this.errors.description = '';
      // this.errors.description_color = '';
      // this.errors.equipment_image = '';
      // this.errors.format_type = '';
      // this.errors.slider_btn_color = '';
      // this.errors.slider_pagination_color = '';
      // this.errors.tag = '';
      // this.errors.time = '';
    },
    async fetchDgVersions() {
      const { data } = await (new DgVersionResource()).list();
      this.dg_versions = data;
    },
    // async fetchDgTraidingOptions() {
    //   const { data } = await (new DgTraidingOptionResource()).list();
    //   this.dg_traiding_options = data;
    //   console.log(data);
    // },
    async fetchDgManufactures() {
      const { data } = await (new DgManufactureResource()).list();
      this.dg_manufacturers = data;
    },
    async fetchDgEngineManufactures() {
      const { data } = await (new DgEngineManufactureResource()).list();
      this.dg_engine_manufacturers = data;
    },
    async fetchData() {
      this.isLoad = true;
      try {
        await this.fetchDgManufactures();
        await this.fetchDgEngineManufactures();
        await this.fetchDgVersions();
        if (this.$route.params.id) {
          const { data } = await (new DgProductResource()).get(this.$route.params.id);
          this.wasRecentlyCreated = true;

          this.id = data.id;
          for (const key in this.form) {
            if (data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
          this.changeVersion();
          console.log(data);
        }
        console.log(this.form.traiding_options);
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
