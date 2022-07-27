<template>
  <div class="abstract-table">
    <el-table
      ref="multipleTable"
      v-loading="isOnLoad"
      :data="data"
      row-key="id"
      border
      fit
      highlight-current-row
      style="width: 100%"
      @selection-change="handleSelectionChange"
      @sort-change="sortChange"
    >
      <el-table-column
        v-if="multipleSelect"
        type="selection"
        width="40"
      />
      <el-table-column
        v-for="(column, index) in columns"
        :key="index"
        :prop="column.key"
        :label="column.name"
        :width="column.width"
        :align="column.align"
        :min-width="column.minWidth"
        :sortable="column.sortable"
      >
        <!--        <template slot="header">-->
        <!--          {{ column.name }}-->
        <!--          <el-dropdown>-->
        <!--            <span class="el-dropdown-link">-->
        <!--              <i class="el-icon-arrow-down el-icon&#45;&#45;right" />-->
        <!--            </span>-->
        <!--            <el-select-->
        <!--              v-model="value"-->
        <!--              multiple-->
        <!--              filterable-->
        <!--              remote-->
        <!--              reserve-keyword-->
        <!--              placeholder="Please enter a keyword"-->
        <!--              :remote-method="remoteMethod"-->
        <!--              :loading="loading"-->
        <!--            >-->
        <!--              <el-option-->
        <!--                v-for="item in [{value: 1, label: 'fdfsf'}]"-->
        <!--                :key="item.value"-->
        <!--                :label="item.label"-->
        <!--                :value="item.value"-->
        <!--              />-->
        <!--            </el-select>-->
        <!--          </el-dropdown>-->
        <!--        </template>-->
        <template slot-scope="scope">
          <el-checkbox v-if="column.editable && column.edit_type === 'checkbox'" v-model="scope.row[column.key]" @change="$emit('updateRow', scope.row.id, column.key, scope.row[column.key], )" />
          <el-input v-else-if="column.render && column.editable && column.edit_type === 'input'" :value="column.render(scope.row)" />
          <el-select v-else-if="column.custom && column.editable && column.edit_type === 'select'" v-model="scope.row[column.key]" placeholder="Выберите" @change="$emit('updateRow', scope.row.id, column.key, scope.row[column.key])">
            <el-option
              v-for="(item, key) in column.options"
              :key="key"
              :label="item"
              :value="key"
            />
          </el-select>
          <el-select v-else-if="column.editable && column.edit_type === 'select'" v-model="scope.row[column.key]" clearable placeholder="Выберите" @change="$emit('updateRow', scope.row.id, column.key, scope.row[column.key])">
            <el-option
              v-for="item in column.options"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
          <!--          <el-input v-else-if="column.editable && column.edit_type === 'textarea'" v-model="scope.row[column.key]" type="textarea" />-->
          <!--          <el-input v-else-if="column.editable && column.edit_type === 'input'" v-model="scope.row[column.key]"></el-input>-->
          <template v-else>
            <span v-if="column.render">{{ column.render(scope.row) }}</span>
            <span v-else-if="column.renderAsHTML" v-html="column.renderAsHTML(scope.row)" />
            <span v-else>{{ scope.row[column.key] }}</span>
          </template>

        </template>
      </el-table-column>
      <el-table-column v-if="withActions" width="120px" :label="$t('table.actions')" align="right">
        <template slot-scope="scope">
          <router-link v-if="actions.findIndex(a => a === action_edit) >= 0" :to="`/${target}/edit/${scope.row.id}`">
            <el-tooltip :content="$t('actions.edit')">
              <el-button type="primary" size="small" icon="el-icon-edit" />
            </el-tooltip>
          </router-link>
          <el-tooltip v-if="actions.findIndex(a => a === action_delete) >= 0" :content="$t('actions.delete')">
            <el-button type="danger" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id);" />
          </el-tooltip>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  name: 'AbstractTable',
  props: {
    data: { type: Array, required: true },
    withActions: { type: Boolean, required: false, default: false },
    actions: { type: Array, required: false, default: () => ['edit', 'delete'] },
    isOnLoad: { type: Boolean, required: false, default: false },
    columns: { type: Array, required: true },
    target: { type: String, required: true },
    multipleSelect: { type: Boolean, required: false, default: () => false },
  },
  data() {
    return {
      value: [],
      action_edit: 'edit',
      action_delete: 'delete',
      selectValue: null,
    };
  },
  methods: {
    sortChange(column) {
      this.$emit('sort', column);
    },
    handleSelectionChange(rows) {
      this.$emit('multipleSelect', rows);
    },
    clearSelection() {
      this.$refs.multipleTable.clearSelection();
    },
    handleDelete(id) {
      this.$confirm(`Вы уверены, что хотите позицию с ID: ${id}?`, 'Внимание', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Отмена',
        type: 'warning',
      }).then(() => {
        this.$emit('delete', { id });
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
