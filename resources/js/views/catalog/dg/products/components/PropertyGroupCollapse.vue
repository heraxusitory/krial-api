<template>
  <el-collapse>
    <el-collapse-item v-for="group in propertyGroups" :key="group.id" :name="group.name">
      <template slot="title"><h3>{{ group.name }}</h3></template>
      <PropertyRow v-for="property in group.properties" :key="property.id" :property="property" />
    </el-collapse-item>
  </el-collapse>
</template>

<script>
import PropertyRow from './PropertyRow';
export default {
  name: 'PropertyGroupCollapse',
  components: { PropertyRow },
  props: {
    propertyGroups: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {};
  },
  created() {
    // console.log(this.propertyGroups);
    // for ()
  },
};

</script>

<style scoped>

</style>
