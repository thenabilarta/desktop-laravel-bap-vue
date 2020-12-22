<template>
  <sui-table-body>
    <sui-table-row
      v-if="list"
      @click="onClickSingleTableRow(list.media_id)"
      :active="isActive"
    >
      <sui-table-cell v-if="idTableColumn">{{ list.media_id }}</sui-table-cell>
      <sui-table-cell v-if="nameTableColumn">{{ list.name }}</sui-table-cell>
      <sui-table-cell v-if="typeTableColumn">{{ list.type }}</sui-table-cell>
      <sui-table-cell class="td-table-image" v-if="thumbnailTableColumn">
        <img
          class="table-image"
          :src="`/storage/uploads/${list.file_name}`"
          alt=""
        />
      </sui-table-cell>
      <sui-table-cell v-if="durationTableColumn">{{
        list.duration
      }}</sui-table-cell>
      <sui-table-cell v-if="sizeTableColumn">{{ list.size }}</sui-table-cell>
      <sui-table-cell v-if="ownerTableColumn">Admin</sui-table-cell>
      <sui-table-cell v-if="permissionTableColumn">Null</sui-table-cell>
      <sui-table-cell v-if="fileNameTableColumn">{{
        list.file_name
      }}</sui-table-cell>
      <sui-table-cell v-if="createdTableColumn">{{
        list.created_at
      }}</sui-table-cell>
      <sui-table-cell v-if="updatedTableColumn">{{
        list.updated_at
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
    list: function () {
      console.log("Changed");
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
    sizeTableColumn: Boolean,
    ownerTableColumn: Boolean,
    permissionTableColumn: Boolean,
    fileNameTableColumn: Boolean,
    createdTableColumn: Boolean,
    updatedTableColumn: Boolean,
    isActiveTableRow: Array,
  },
  data() {
    return {
      isEditing: false,
      isActive: false,
    };
  },
  methods: {
    toDelete() {
      console.log(this.list.media_id);
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
              .get("http://127.0.0.1:8000/media/delete/" + this.list.media_id)
              .then((res) => console.log(res))
              .then(() => this.refreshTable());
            break;
          default:
            console.log("Cancelled");
        }
      });
    },
    toEdit() {
      this.isEditing = true;
    },
    onCloseModal() {
      this.isEditing = false;
    },
    refreshTable() {
      this.$emit("refreshTable");
      this.isEditing = false;
    },
    onClickSingleTableRow(id) {
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
