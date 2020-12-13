<template>
  <div class="loading" v-if="tableList === null">
    <sui-loader active inline>Loading</sui-loader>
  </div>
  <div v-else>
    <Modal v-if="modal" v-on:closeModal="onCloseModal"></Modal>
    <div class="header">
      <div class="header-navigation">
        <sui-button primary @click="toggleModal">Add Media</sui-button>
      </div>
      <div class="header-filter">
        <sui-input
          placeholder="Name"
          @keyup="onInputFilterName"
          v-model="inputFilterName"
        />
        <sui-dropdown text="Type" floating>
          <sui-dropdown-menu>
            <sui-dropdown-item>Image</sui-dropdown-item>
            <sui-dropdown-item>PDF</sui-dropdown-item>
            <sui-dropdown-item>Video</sui-dropdown-item>
          </sui-dropdown-menu>
        </sui-dropdown>
        <sui-dropdown text="Retired" floating>
          <sui-dropdown-menu>
            <sui-dropdown-item>True</sui-dropdown-item>
            <sui-dropdown-item>False</sui-dropdown-item>
          </sui-dropdown-menu>
        </sui-dropdown>
        <sui-input placeholder="Tags" />
      </div>
      <div class="header-icon">
        <sui-dropdown icon="eye" floating multiple>
          <sui-dropdown-menu>
            <sui-dropdown-item @click="showIdTableColumn"
              >ID
              <i
                v-if="idTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showNameTableColumn"
              >Name
              <i
                v-if="nameTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showTypeTableColumn"
              >Type
              <i
                v-if="typeTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showThumbnailTableColumn"
              >Thumbnail
              <i
                v-if="thumbnailTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showDurationTableColumn"
              >Duration
              <i
                v-if="durationTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showSizeTableColumn"
              >Size
              <i
                v-if="sizeTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showOwnerTableColumn"
              >Owner
              <i
                v-if="ownerTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showPermissionTableColumn"
              >Permission
              <i
                v-if="permissionTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showFileNameTableColumn"
              >File Name
              <i
                v-if="fileNameTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showCreatedTableColumn"
              >Created
              <i
                v-if="createdTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
            <sui-dropdown-item @click="showUpdatedTableColumn"
              >Modified
              <i
                v-if="updatedTableColumn"
                class="fa fa-check"
                style="font-size: 14px"
              ></i
            ></sui-dropdown-item>
          </sui-dropdown-menu>
        </sui-dropdown>
        <i class="fas fa-print"></i>
      </div>
    </div>
    <div class="body">
      <sui-table selectable celled>
        <sui-table-header>
          <sui-table-row>
            <sui-table-header-cell
              v-if="idTableColumn"
              @click="orderByTableListId"
              >ID
              <sui-icon
                v-if="sortTableListId"
                :name="
                  this.tableListIdASC ? 'sort amount down' : 'sort amount up'
                "
              />
            </sui-table-header-cell>
            <sui-table-header-cell
              v-if="nameTableColumn"
              @click="orderByTableListName"
              >Name
              <sui-icon
                v-if="sortTableListName"
                :name="
                  this.tableListNameASC
                    ? 'sort alphabet up'
                    : 'sort alphabet down'
                "
            /></sui-table-header-cell>
            <sui-table-header-cell
              v-if="typeTableColumn"
              @click="orderByTableListType"
              >Type
              <sui-icon
                v-if="sortTableListType"
                :name="
                  this.tableListTypeASC
                    ? 'sort alphabet up'
                    : 'sort alphabet down'
                "
            /></sui-table-header-cell>
            <sui-table-header-cell v-if="thumbnailTableColumn"
              >Thumbnail</sui-table-header-cell
            >
            <sui-table-header-cell v-if="durationTableColumn"
              >Duration</sui-table-header-cell
            >
            <sui-table-header-cell
              @click="orderByTableListSize"
              v-if="sizeTableColumn"
              >Size
              <sui-icon
                v-if="sortTableListSize"
                :name="
                  this.tableListSizeASC ? 'sort amount down' : 'sort amount up'
                "
            /></sui-table-header-cell>
            <sui-table-header-cell v-if="ownerTableColumn"
              >Owner</sui-table-header-cell
            >
            <sui-table-header-cell v-if="permissionTableColumn"
              >Permission</sui-table-header-cell
            >
            <sui-table-header-cell v-if="fileNameTableColumn"
              >File Name</sui-table-header-cell
            >
            <sui-table-header-cell v-if="createdTableColumn"
              >Created at</sui-table-header-cell
            >
            <sui-table-header-cell v-if="updatedTableColumn"
              >Modified at</sui-table-header-cell
            >
            <sui-table-header-cell></sui-table-header-cell>
          </sui-table-row>
        </sui-table-header>
        <TableRow
          v-for="list in filteredTable ? filteredTable : tableList"
          v-bind:key="list.id"
          v-bind:list="list"
          v-bind:idTableColumn="idTableColumn"
          v-bind:nameTableColumn="nameTableColumn"
          v-bind:typeTableColumn="typeTableColumn"
          v-bind:thumbnailTableColumn="thumbnailTableColumn"
          v-bind:durationTableColumn="durationTableColumn"
          v-bind:sizeTableColumn="sizeTableColumn"
          v-bind:ownerTableColumn="ownerTableColumn"
          v-bind:permissionTableColumn="permissionTableColumn"
          v-bind:fileNameTableColumn="fileNameTableColumn"
          v-bind:createdTableColumn="createdTableColumn"
          v-bind:updatedTableColumn="updatedTableColumn"
          v-on:refreshTable="onUpdate"
        ></TableRow>
      </sui-table>
    </div>
    <div class="footer">
      <sui-button color="green">With Selected</sui-button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";

import Modal from "./components/Modal";
import TableRow from "./components/TableRow";
import "../css/index.css";

export default {
  mounted() {
    axios
      .get("http://127.0.0.1:8000/media/data")
      .then((res) => (this.tableList = res.data))
      .then(() => this.orderByTableListId());
  },
  components: {
    Modal: Modal,
    TableRow: TableRow,
  },
  data() {
    return {
      // Listing what to show
      idTableColumn: true,
      nameTableColumn: true,
      typeTableColumn: false,
      thumbnailTableColumn: true,
      durationTableColumn: true,
      sizeTableColumn: true,
      ownerTableColumn: true,
      permissionTableColumn: true,
      fileNameTableColumn: true,
      createdTableColumn: false,
      updatedTableColumn: false,

      // Data from database
      tableList: null,

      // Show Modal
      modal: false,

      // Sorting table
      sortTableListId: true,
      sortTableListName: false,
      sortTableListSize: false,
      tableListIdASC: false,
      tableListNameASC: true,
      tableListSizeASC: true,

      // filter
      inputFilterName: "",
    };
  },
  computed: {
    filteredTable: function() {
      return this.tableList.filter((t) => {
        return t.name.match(this.inputFilterName);
      });
    },
  },
  methods: {
    showIdTableColumn() {
      this.idTableColumn = !this.idTableColumn;
    },
    showNameTableColumn() {
      this.nameTableColumn = !this.nameTableColumn;
    },
    showTypeTableColumn() {
      this.typeTableColumn = !this.typeTableColumn;
    },
    showThumbnailTableColumn() {
      this.thumbnailTableColumn = !this.thumbnailTableColumn;
    },
    showDurationTableColumn() {
      this.durationTableColumn = !this.durationTableColumn;
    },
    showSizeTableColumn() {
      this.sizeTableColumn = !this.sizeTableColumn;
    },
    showOwnerTableColumn() {
      this.ownerTableColumn = !this.ownerTableColumn;
    },
    showPermissionTableColumn() {
      this.permissionTableColumn = !this.permissionTableColumn;
    },
    showFileNameTableColumn() {
      this.fileNameTableColumn = !this.fileNameTableColumn;
    },
    showCreatedTableColumn() {
      this.createdTableColumn = !this.createdTableColumn;
    },
    showUpdatedTableColumn() {
      this.updatedTableColumn = !this.updatedTableColumn;
    },
    toggleModal() {
      this.modal = !this.modal;
    },
    onCloseModal() {
      this.modal = false;
      axios
        .get("http://127.0.0.1:8000/media/data")
        .then((res) => (this.tableList = res.data))
        .then(() => {
          this.tableListIdASC = false;
          this.orderByTableListId();
        });
    },
    onUpdate() {
      this.modal = false;
      axios
        .get("http://127.0.0.1:8000/media/data")
        .then((res) => (this.tableList = res.data));
    },
    onInputFilterName() {
      console.log(this.inputFilterName);
    },
    orderByTableListId() {
      this.sortTableListName = false;
      this.sortTableListType = false;
      this.sortTableListSize = false;
      this.sortTableListId = true;
      let parsedId = _.forEach(this.tableList, (val) => {
        let number = parseInt(val.media_id);
        val.media_id = number;
      });
      if (this.tableListIdASC) {
        let sortedMediaByIdASC = _.orderBy(parsedId, "media_id", "asc");
        this.tableList = sortedMediaByIdASC;
        this.tableListIdASC = false;
      } else {
        let sortedMediaByIdDESC = _.orderBy(parsedId, "media_id", "desc");
        this.tableList = sortedMediaByIdDESC;
        this.tableListIdASC = true;
      }
    },
    orderByTableListName() {
      this.sortTableListId = false;
      this.sortTableListType = false;
      this.sortTableListSize = false;
      this.sortTableListName = true;
      if (this.tableListNameASC) {
        let sortedTableListByNameASC = _.orderBy(
          this.tableList,
          ["name"],
          "asc"
        );
        this.tableList = sortedTableListByNameASC;
        this.tableListNameASC = false;
      } else {
        let sortedTableListByNameDESC = _.orderBy(
          this.tableList,
          ["name"],
          "desc"
        );
        this.tableList = sortedTableListByNameDESC;
        this.tableListNameASC = true;
      }
    },
    orderByTableListType() {
      this.sortTableListSize = false;
      this.sortTableListId = false;
      this.sortTableListName = false;
      this.sortTableListType = true;
      if (this.tableListTypeASC) {
        let sortedTableListByTypeASC = _.orderBy(
          this.tableList,
          ["type"],
          "asc"
        );
        this.tableList = sortedTableListByTypeASC;
        this.tableListTypeASC = false;
      } else {
        let sortedTableListByTypeDESC = _.orderBy(
          this.tableList,
          ["type"],
          "desc"
        );
        this.tableList = sortedTableListByTypeDESC;
        this.tableListTypeASC = true;
      }
    },
    orderByTableListSize() {
      this.sortTableListId = false;
      this.sortTableListName = false;
      this.sortTableListType = false;
      this.sortTableListSize = true;
      let parsedSize = _.forEach(this.tableList, (val) => {
        let number = parseInt(val.size);
        val.size = number;
      });
      if (this.tableListSizeASC) {
        let sortedMediaBySizeASC = _.orderBy(parsedSize, "size", "asc");
        this.tableList = sortedMediaBySizeASC;
        this.tableListSizeASC = false;
      } else {
        let sortedMediaBySizeDESC = _.orderBy(parsedSize, "size", "desc");
        this.tableList = sortedMediaBySizeDESC;
        this.tableListSizeASC = true;
      }
    },
  },
};
</script>

<style scoped>
.ui.multiple.dropdown {
  padding: 0 !important;
}
</style>
