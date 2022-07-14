<template>
  <!--  <el-form-item label="Мощность">-->
  <el-row type="flex">
    <el-col :span="6"><span>{{ property.name }}</span></el-col>
    <el-col :span="6">
      <el-form-item>
        <el-input
          v-model="property.value"
        />
      </el-form-item>
    </el-col>
  </el-row>
<!--  </el-form-item>-->
</template>

<script>
export default {
  name: 'PropertyRow',
  props: {
    property: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {

    };
  },
};
</script>

<style scoped>

</style>
