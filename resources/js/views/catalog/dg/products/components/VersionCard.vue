<template>
  <el-card class="box-card">
    <el-row type="flex" justify="end">
      <el-col :span="1">
        <el-tooltip :content="$t('actions.delete')">
          <el-button type="danger" size="small" icon="el-icon-delete" @click="handleRemove" />
        </el-tooltip>
      </el-col>
    </el-row>

    <el-form-item label="Тип торгового предложения" required>
      <el-select v-model="traidingOption.version.id" placeholder="Выберите тип" @change="$emit('changeVersion')">
        <el-option
          v-for="version in dgVersions"
          :key="version.id"
          :label="version.name"
          :value="version.id"
          :disabled="disabledDgVersionIds.includes(version.id)"
        >{{ version.name }}
        </el-option>
      </el-select>
    </el-form-item>
    <el-form-item label="Цена" required>
      <el-input-number v-model="traidingOption.price" />
    </el-form-item>
    <el-form-item label="Медиа">
      <UploadImage
        :value-collection="traidingOption.attachments"
        :multi="true"
        @input="(payload) => imgHook(payload, 'attachments')"
        @remove="(payload) => removeHook(payload,'attachments')"
        @errors="(payload) => imgError(payload, 'attachments')"
      />
    </el-form-item>
  </el-card>
</template>

<script>
import UploadImage from '@/components/Upload/UploadImage';

export default {
  name: 'VersionCard',
  // eslint-disable-next-line no-undef
  components: { UploadImage },
  props: {
    traidingOption: {
      type: Object,
      required: true,
    },
    dgVersions: {
      type: Array,
      default: () => [],
    },
    disabledDgVersionIds: {
      type: Array,
      default: () => [],
    },
  // traiding_options: {
  //   type: Array,
  //   default: () => [],
  // }
  },
  data() {
    return {
      // disabled_dg_version_ids: [],
    };
  },
  methods: {
    imgHook(payload, key) {
      // console.log('imgHook', payload);
      // console.log('payload', payload);
      // console.log('traid_option', this.traidingOption);
      this.traidingOption.attachments.push(payload);
      this.traidingOption.attachment_ids = this.traidingOption.attachments.map(item => item.id);
      // console.log(this.traidingOption.image_ids);
    },
    removeHook(id, key) {
      // console.log('removeHook', id);
      const idx = this.traidingOption.attachments.findIndex(item => item.id === id);
      this.traidingOption.attachments.splice(idx, 1);
      this.traidingOption.attachment_ids = this.traidingOption.attachments.map(item => item.id);
    },
    imgError(payload, key) {
      console.log(payload);
    },
    handleRemove() {
      this.$confirm(`Вы уверены, что хотите удалить текущий вариант исполнения?`, 'Внимание', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Отмена',
        type: 'warning',
      }).then(() => {
        this.$emit('remove', this.traidingOption.id);
      }).catch(() => {
        this.$message({
          type: 'info',
          message: 'Удаление отменено',
        });
      });
    },

  },
};
</script>

<style scoped>

</style>
