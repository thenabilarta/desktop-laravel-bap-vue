<template>
  <sui-table-body>
    <sui-table-row
      v-if="list"
      @click="onClickSingleTableRow(list.mediaId)"
      :active="isActive"
    >
      <sui-table-cell v-if="idTableColumn">{{ list.mediaId }}</sui-table-cell>
      <sui-table-cell v-if="nameTableColumn">{{ list.name }}</sui-table-cell>
      <sui-table-cell v-if="typeTableColumn">{{
        list.mediaType
      }}</sui-table-cell>
      <sui-table-cell class="td-table-image" v-if="thumbnailTableColumn">
        <img
          v-if="list.mediaType === 'image'"
          class="table-image"
          :src="`http://localhost/xibo-data/${list.storedAs}`"
          alt=""
        />
        <p v-else>N/A</p>
      </sui-table-cell>
      <sui-table-cell v-if="tagsTableColumn"
        >{{ list.tags ? list.tags.split(",").join(", ") : "" }}
      </sui-table-cell>
      <sui-table-cell v-if="durationTableColumn">{{
        list.duration
      }}</sui-table-cell>
      <sui-table-cell v-if="sizeTableColumn">{{
        (list.fileSize / 1000).toFixed(1) + " kb"
      }}</sui-table-cell>
      <sui-table-cell v-if="ownerTableColumn">Admin</sui-table-cell>
      <sui-table-cell v-if="permissionTableColumn">Null</sui-table-cell>
      <sui-table-cell v-if="fileNameTableColumn">{{
        list.fileName
      }}</sui-table-cell>
      <sui-table-cell v-if="createdTableColumn">{{
        list.createdDt
      }}</sui-table-cell>
      <sui-table-cell v-if="updatedTableColumn">{{
        list.modifiedDt
      }}</sui-table-cell>
      <sui-table-cell>
        <sui-dropdown floating>
          <sui-dropdown-menu>
            <sui-dropdown-item @click="toEdit">Edit</sui-dropdown-item>
            <sui-dropdown-item @click="toDelete">Delete</sui-dropdown-item>
          </sui-dropdown-menu>
        </sui-dropdown>
      </sui-table-cell>
      <EditTableRow
        v-on:closeModal="onCloseModal"
        v-if="isEditing"
        v-bind:list="list"
        v-on:updateEdit="refreshTable"
      ></EditTableRow>
    </sui-table-row>
  </sui-table-body>
</template>

<script>
import axios from "axios";
import _ from "lodash";
import swal from "sweetalert";

import EditTableRow from "./EditTableRow";
import "../../css/table-row.css";

export default {
  components: {
    EditTableRow: EditTableRow,
  },
  watch: {
    isActiveProp: function () {
      this.isActive = false;
    },
  },
  name: "TableRow",
  props: {
    list: Object,
    index: Number,
    idTableColumn: Boolean,
    nameTableColumn: Boolean,
    typeTableColumn: Boolean,
    thumbnailTableColumn: Boolean,
    durationTableColumn: Boolean,
    tagsTableColumn: Boolean,
    sizeTableColumn: Boolean,
    ownerTableColumn: Boolean,
    permissionTableColumn: Boolean,
    fileNameTableColumn: Boolean,
    createdTableColumn: Boolean,
    updatedTableColumn: Boolean,
    isActiveTableRow: Array,
    isActiveProp: Boolean,
  },
  data() {
    return {
      isEditing: false,
      isActive: false,
    };
  },
  methods: {
    toDelete() {
      console.log(this.list.mediaId);
      swal("Do you want to delete this media?", {
        buttons: {
          Cancel: true,
          Delete: {
            value: "delete",
          },
        },
      }).then(async (value) => {
        switch (value) {
          case "delete":
            await axios
              .get("http://127.0.0.1:8000/mediax/delete/" + this.list.mediaId)
              .then((res) => console.log(res))
              .then(() => this.refreshTable());
            break;
          default:
            console.log("Cancelled");
        }
      });
    },
    toRemoveSelectedRow() {
      console.log("To remove selected Row");
      // this.isActive = false;
      // this.isActiveTableRow = [];
    },
    toEdit() {
      this.isEditing = true;
    },
    onCloseModal() {
      this.$emit("refreshTable");
      this.refreshTable();
    },
    refreshTable() {
      this.$emit("refreshTable");
      this.isEditing = false;
    },
    onClickSingleTableRow(id) {
      console.log(id);
      this.isActive = !this.isActive;
      if (this.isActive === true) {
        this.isActiveTableRow.push(id);
      } else if (this.isActive === false) {
        _.remove(this.isActiveTableRow, function (n) {
          return n === id;
        });
      }
    },
  },
};
</script>
