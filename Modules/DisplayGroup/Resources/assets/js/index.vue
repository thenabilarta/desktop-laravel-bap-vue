<template>
  <div>
    <div class="header">
      <div class="header-icon">
        <sui-dropdown icon="print" floating multiple>
          <sui-dropdown-menu>
            <sui-dropdown-item>PDF</sui-dropdown-item>
            <sui-dropdown-item>CSV</sui-dropdown-item>
          </sui-dropdown-menu>
        </sui-dropdown>
      </div>
    </div>
    <div class="body">
      <div class="loading" v-if="tableList === null">
        <sui-loader active inline />
      </div>
      <sui-table selectable celled v-else>
        <sui-table-header>
          <sui-table-row>
            <sui-table-header-cell>Name</sui-table-header-cell>
            <sui-table-header-cell>Description</sui-table-header-cell>
            <sui-table-header-cell>Dynamic</sui-table-header-cell>
            <sui-table-header-cell
              class="dropdown-table-cell"
            ></sui-table-header-cell>
          </sui-table-row>
        </sui-table-header>
        <sui-table-body>
          <sui-table-row v-for="(t, index) in tableList" :key="index">
            <sui-table-cell>{{ t.displayGroup }}</sui-table-cell>
            <sui-table-cell>{{ t.description }}</sui-table-cell>
            <sui-table-cell>{{
              t.isDynamic === 1 ? "true" : "false"
            }}</sui-table-cell>
            <sui-table-cell class="dropdown-table-cell">
              <sui-dropdown floating pointing="top right">
                <sui-dropdown-menu>
                  <sui-dropdown-item>Edit</sui-dropdown-item>
                  <sui-dropdown-item>Delete</sui-dropdown-item>
                  <sui-dropdown-divider />
                  <sui-dropdown-item>Default Layout</sui-dropdown-item>
                  <sui-dropdown-divider />
                  <sui-dropdown-item>Preview Profile</sui-dropdown-item>
                </sui-dropdown-menu>
              </sui-dropdown>
            </sui-table-cell>
          </sui-table-row>
        </sui-table-body>
      </sui-table>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import "../css/index.css";
export default {
  data() {
    return {
      tableList: null,
    };
  },
  mounted() {
    axios.get("http://127.0.0.1:8000/displaygroup/data").then((res) => {
      console.log(res.data);
      this.tableList = res.data;
    });
  },
};
</script>