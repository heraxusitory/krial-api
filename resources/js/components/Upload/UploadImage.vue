<template>
  <div class="upload-container">
    <el-upload
      :data="{
        _token: csrf.token,
      }"
      :multiple="multi"
      :on-preview="handlePreview"
      :show-file-list="multi"
      :file-list="valueCollection"
      :on-remove="handleRemove"
      :on-success="handleImageSuccess"
      :on-error="handleError"
      class="image-uploader"
      list-type="picture"
      drag
      :action="actionUrl"
    >
      <i class="el-icon-upload" />
      <div class="el-upload__text">
        Drag files here or <em>Click to upload</em>
      </div>
    </el-upload>
    <div v-if="!multi && valueCollection.length > 0" class="image-preview image-app-preview">
      <div class="image-preview-wrapper">
        <img :src="valueCollection[0].url" alt="">
        <div class="image-preview-action">
          <i class="el-icon-delete" @click="rmImage" />
        </div>
      </div>
    </div>
    <!--    <div v-if="true && valueCollection.length > 0" class="image-preview image-app-preview">-->
    <!--      <div v-for="(value, key) in valueCollection" :key="key" class="image-preview-wrapper">-->
    <!--        <img :src="value.url" alt="">-->
    <!--        <div class="image-preview-action">-->
    <!--          <i class="el-icon-delete" @click="rmImage" />-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
    <el-dialog
      :visible.sync="isImgSelected"
      width="60%"
    >
      <img :src="currentImgUrl" alt="" width="100%">
    </el-dialog>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'ImageUpload3',
  props: {
    valueCollection: {
      type: Array,
      default: () => [],
    },
    multi: {
      type: Boolean,
      default: false,
    },
    // actionUrl: {
    //   type: String,
    //   default: '/api/attachments/save',
    // },
  },
  data() {
    return {
      tempUrl: '',
      additionalData: {},
      currentImgUrl: '',
      isImgSelected: false,
    };
  },
  computed: {
    ...mapGetters(['csrf']),
    isMulti() {
      return this.multi;
    },
    actionUrl() {
      return '/api/attachments/save';
    },
  },
  methods: {
    handlePreview(file) {
      // window.open(file.url, '_blank');
      this.currentImgUrl = file.url;
      this.isImgSelected = true;
    },
    rmImage(id) {
      this.$emit('remove', id);
    },
    emitInput(val) {
      this.$emit('input', val);
    },
    handleImageSuccess(file) {
      this.emitInput(file.files[0]);
    },
    handleError({ message }) {
      const errors = JSON.parse(message);
      this.$emit('errors', errors);
    },
    handleRemove(file) {
      this.rmImage(file.id);
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import "~@/styles/mixin.scss";
.upload-container {
  width: 100%;
  position: relative;
  @include clearfix;
  .image-uploader {
    width: 50vw;
    //float: left;
  }
  .image-preview {
    width: 200px;
    //height: 200px;
    position: relative;
    border: 1px dashed #d9d9d9;
    float: left;
    //margin-left: 50px;
    .image-preview-wrapper {
      position: relative;
      width: 100%;
      height: 100%;
      img {
        width: 100%;
        height: 100%;
      }
    }
    .image-preview-action {
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      cursor: default;
      text-align: center;
      color: #fff;
      opacity: 0;
      font-size: 20px;
      background-color: rgba(0, 0, 0, .5);
      transition: opacity .3s;
      cursor: pointer;
      text-align: center;
      line-height: 200px;
      .el-icon-delete {
        font-size: 36px;
      }
    }
    &:hover {
      .image-preview-action {
        opacity: 1;
      }
    }
  }
  .image-app-preview {
    width: 320px;
    //height: 180px;
    position: relative;
    border: 1px dashed #d9d9d9;
    float: left;
    //margin-left: 50px;
    .app-fake-conver {
      height: 44px;
      position: absolute;
      width: 100%; // background: rgba(0, 0, 0, .1);
      text-align: center;
      line-height: 64px;
      color: #fff;
    }
  }
}
</style>
