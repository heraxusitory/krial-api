<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-position="top">
      <el-form-item label="ФИО" :error="errors.name" required>
        <el-input v-model="form.name" />
      </el-form-item>
      <el-form-item label="Почта" required :error="errors.email">
        <el-input v-model="form.email" />
      </el-form-item>
      <el-form-item label="Телефон" required :error="errors.phone">
        <el-input v-model="form.phone" />
      </el-form-item>
      <el-form-item label="Сортировка" required :error="errors.sort">
        <el-input v-model="form.sort" />
      </el-form-item>
      <el-form-item style="margin-top: 15px;">
        <el-button type="success" :loading="requestSend" @click="submit">{{ $t('form.save') }}</el-button>
      </el-form-item>
      <Loader :active="isLoad" />
    </el-form>
  </div>
</template>

<script>
import Loader from '@/components/Loader/Loader';
import ApplicationRequestResource from '@/api/shop/application_request';

export default {
  name: 'ApplicationRequestEdit',
  components: { Loader },
  data() {
    return {
      id: null,
      requestSend: false,
      isLoad: false,
      form: {
        name: '',
        phone: '',
        email: '',
        sort: 100,
      },
      errors: {
        name: '',
        phone: '',
        email: '',
        sort: '',
      },
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData() {
      this.isLoad = true;
      try {
        if (this.$route.params.id) {
          const { data } = await (new ApplicationRequestResource()).get(this.$route.params.id);
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
    async submit() {
      this.requestSend = !this.requestSend;
      this.isLoad = true;
      this.clearAllErrors();

      try {
        if (!this.wasRecentlyCreated) {
          await (new ApplicationRequestResource()).store(this.form);
        } else {
          await (new ApplicationRequestResource()).update(this.id, this.form);
        }

        this.$router.push({ name: 'ApplicationRequests' });
        this.$message({
          message: 'Заявка успешно сохранена',
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
      this.errors.phone = '';
      this.errors.sort = '';
      this.errors.email = '';
    },
  },
};
</script>

<style scoped>

</style>
