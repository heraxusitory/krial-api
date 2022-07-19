<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-position="top">
      <el-form-item label="Название" :error="errors.name" required>
        <el-input v-model="form.name" @input="translateCode" />
      </el-form-item>
      <el-form-item label="Код" :error="errors.code" required>
        <el-input v-model="form.code" />
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
import helpers from '@/vendor/helpers';
import DgOptionGroupResource from '@/api/catalog/dg/dg_option_group';

export default {
  name: 'OptionGroupEdit',
  components: { Loader },
  data() {
    return {
      id: null,
      requestSend: false,
      isLoad: false,
      form: {
        name: '',
        code: '',
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
    async submit() {
      this.requestSend = !this.requestSend;
      this.isLoad = true;
      this.clearAllErrors();

      try {
        if (!this.wasRecentlyCreated) {
          await (new DgOptionGroupResource()).store(this.form);
        } else {
          await (new DgOptionGroupResource()).update(this.id, this.form);
        }

        this.$router.push({ name: 'DguOptionGroups' });
        this.$message({
          message: 'Группа опций успешно сохранена',
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
      this.errors.name = '';
      this.errors.code = '';
    },
    async fetchData() {
      this.isLoad = true;
      try {
        // await this.fetchDgManufactures();
        // await this.fetchDgProperties();
        if (this.$route.params.id) {
          const { data } = await (new DgOptionGroupResource()).get(this.$route.params.id);
          this.wasRecentlyCreated = true;

          this.id = data.id;
          for (const key in this.form) {
            if (data.hasOwnProperty(key)) {
              this.form[key] = data[key];
            }
          }
          // this.form.property_groups = _.groupBy(data.properties, (item) => {
          //   if (item.group.id === 46) {
          //     console.log(46);
          //   }
          //   return item.group.name;
          // });
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
