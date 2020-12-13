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
            <sui-table-header-cell>Layout ID</sui-table-header-cell>
            <sui-table-header-cell>Name</sui-table-header-cell>
            <sui-table-header-cell>Duration</sui-table-header-cell>
            <sui-table-header-cell>Dimension</sui-table-header-cell>
            <sui-table-header-cell>Owner</sui-table-header-cell>
            <sui-table-header-cell>Status</sui-table-header-cell>
            <sui-table-header-cell></sui-table-header-cell>
          </sui-table-row>
        </sui-table-header>
        <sui-table-body>
          <sui-table-row v-for="list in tableList" :key="list.layoutId">
            <sui-table-cell>{{ list.layoutId }}</sui-table-cell>
            <sui-table-cell>{{ list.layout }}</sui-table-cell>
            <sui-table-cell>{{ list.duration }}</sui-table-cell>
            <sui-table-cell
              >{{ list.width }} x {{ list.height }}</sui-table-cell
            >
            <sui-table-cell>{{ list.owner }}</sui-table-cell>
            <sui-table-cell>{{
              list.status === 1 ? "Ready" : "Not build yet"
            }}</sui-table-cell>
            <sui-table-cell class="dropdown-table-cell">
              <sui-dropdown floating pointing="top right">
                <sui-dropdown-menu>
                  <sui-dropdown-item>Edit</sui-dropdown-item>
                  <sui-dropdown-item @click="deleteLayout(list.layoutId)"
                    >Delete</sui-dropdown-item
                  >
                  <sui-dropdown-divider />
                  <sui-dropdown-item>Permission</sui-dropdown-item>
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
    axios.get("http://127.0.0.1:8000/layout/data").then((res) => {
      console.log(res.data);
      this.tableList = res.data;
    });
  },
  methods: {
    deleteLayout(id) {
      axios
        .post("http://127.0.0.1:8000/layout/delete", { layoutId: id })
        .then((res) => console.log(res.data))
        .then(() => this.refreshPage());
    },
    refreshPage() {
      axios.get("http://127.0.0.1:8000/layout/data").then((res) => {
        console.log(res.data);
        this.tableList = res.data;
      });
    },
  },
};
</script>
