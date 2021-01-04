<template>
  <div class="loading" v-if="tableList === null">
    <sui-loader active inline>Loading</sui-loader>
  </div>
  <div v-else>
    <Modal v-if="modal" v-on:closeModal="onCloseModal"></Modal>
    <div class="header" @click.self="onClickHeader">
      <div class="header-navigation">
        <sui-button primary @click="toggleModal">Add Media</sui-button>
      </div>
      <div class="header-filter">
        <sui-input
          placeholder="Name"
          @keyup="onInputFilterName"
          v-model="inputFilterName"
        />
        <sui-dropdown
          multiple
          placeholder="Tags"
          search
          selection
          allow-additions
          v-model="inputTagName"
          :options="tagFilterOption"
          id="inputTags"
        />
        <sui-dropdown
          text="Type"
          floating
          v-model="mediaType"
          :options="mediaTypeOption"
        />
        <sui-dropdown
          text="Retired"
          floating
          v-model="retiredMediaModel"
          :options="retiredFilterOption"
        />
        <sui-dropdown floating v-model="pageSize" :options="pageOption" />
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
            <sui-dropdown-item @click="showTagsTableColumn"
              >Tags
              <i
                v-if="tagsTableColumn"
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
        <sui-dropdown icon="print" floating multiple>
          <sui-dropdown-menu>
            <sui-dropdown-item @click="createPDF">PDF</sui-dropdown-item>
            <sui-dropdown-item @click="createCSV(csvData)"
              >CSV</sui-dropdown-item
            >
          </sui-dropdown-menu>
        </sui-dropdown>
      </div>
    </div>
    <div class="body" @click.self="onClickBody">
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
            <sui-table-header-cell v-if="tagsTableColumn"
              >Tags</sui-table-header-cell
            >
            <sui-table-header-cell
              @click="orderByTableListDuration"
              v-if="durationTableColumn"
              >Duration
              <sui-icon
                v-if="sortTableListDuration"
                :name="
                  this.tableListDurationASC
                    ? 'sort amount down'
                    : 'sort amount up'
                "
            /></sui-table-header-cell>
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
          v-for="list in tableListComputed"
          v-bind:key="list.id"
          v-bind:list="list"
          v-bind:idTableColumn="idTableColumn"
          v-bind:nameTableColumn="nameTableColumn"
          v-bind:typeTableColumn="typeTableColumn"
          v-bind:thumbnailTableColumn="thumbnailTableColumn"
          v-bind:durationTableColumn="durationTableColumn"
          v-bind:tagsTableColumn="tagsTableColumn"
          v-bind:sizeTableColumn="sizeTableColumn"
          v-bind:ownerTableColumn="ownerTableColumn"
          v-bind:permissionTableColumn="permissionTableColumn"
          v-bind:fileNameTableColumn="fileNameTableColumn"
          v-bind:createdTableColumn="createdTableColumn"
          v-bind:updatedTableColumn="updatedTableColumn"
          v-bind:isActiveTableRow="isActiveTableRow"
          v-bind:isActiveProp="isActiveProp"
          v-on:onUpdate="onUpdate"
          v-on:refreshTable="onUpdate"
        ></TableRow>
      </sui-table>
    </div>
    <div class="footer" @click.self="onClickFooter">
      <sui-dropdown
        button
        text="With Selected"
        :disabled="isActiveTableRow.length > 0 ? false : true"
      >
        <sui-dropdown-menu>
          <sui-dropdown-item @click="onClickWithSelected"
            >Delete</sui-dropdown-item
          >
        </sui-dropdown-menu>
      </sui-dropdown>
      <div class="pagination">
        <sui-button
          @click="onClickIconLeftArrow"
          icon="left arrow"
          :disabled="pageNumber === 0 ? true : false"
        />
        <span
          v-for="p in pageCount"
          id="pageNumberFooter"
          :key="p"
          :style="
            p === pageNumber + 1 ? 'font-weight:bold; font-size: 15px' : ''
          "
          @click="onClickPageNumber(p)"
          >{{ p }}</span
        >
        <sui-button
          @click="onClickIconRightArrow"
          icon="right arrow"
          :disabled="pageNumber + 1 === pageCount ? true : false"
        />
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import swal from "sweetalert";
import _ from "lodash";
import pdfMake from "pdfmake";
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
      tagsTableColumn: false,
      sizeTableColumn: true,
      ownerTableColumn: true,
      permissionTableColumn: true,
      fileNameTableColumn: true,
      createdTableColumn: false,
      updatedTableColumn: false,
      // Data from database
      tableList: {},
      // Show Modal
      modal: false,
      // Sorting table
      sortTableListId: true,
      sortTableListName: false,
      sortTableListSize: false,
      sortTableListDuration: false,
      tableListIdASC: false,
      tableListNameASC: true,
      tableListSizeASC: true,
      tableListDurationASC: false,
      // filter
      inputFilterName: "",
      inputFilterNameActive: true,
      inputTagName: [],
      mediaType: "all",
      mediaTypeActive: true,
      mediaTypeOption: [
        {
          text: "All",
          value: "all",
        },
        {
          text: "Image",
          value: "image",
        },
        {
          text: "Video",
          value: "video",
        },
      ],
      tagFilterOption: [],
      mediaTagActive: true,
      retiredMediaModel: 0,
      retiredFilterOption: [
        {
          text: "Not Retired",
          value: 0,
        },
        {
          text: "Retired",
          value: 1,
        },
      ],
      // table Row
      isActiveTableRow: [],
      isActiveProp: false,
      // pagination
      pageNumber: 0,
      pageSize: 25,
      pageCount: [],
      pageOption: [
        {
          text: "5",
          value: 5,
        },
        {
          text: "10",
          value: 10,
        },
        {
          text: "25",
          value: 25,
        },
        {
          text: "50",
          value: 50,
        },
        {
          text: "100",
          value: 100,
        },
      ],
    };
  },
  watch: {
    pageSize: function () {
      this.pageNumber = 0;
    },
    inputTagName: function () {
      console.log(this.inputTagName);
    },
    pageNumber: function () {
      console.log(this.pageNumber + "Page number");
    },
    inputFilterName: function () {
      if (this.inputFilterName === "") {
        this.inputFilterNameActive = true;
      }
    },
    inputTagName: function () {
      console.log(this.inputTagName);
      if (this.inputTagName.length === 0) {
        this.mediaTagActive = true;
      }
    },
    mediaType: function () {
      this.mediaTypeActive = true;
    },
  },
  computed: {
    tableListComputed() {
      let originalTableList = this.tableList;
      let mediaTypeArray;
      let arrayOfTags = [];

      if (originalTableList.length > 0) {
        originalTableList.map((o) => {
          if (o.tags) {
            let arrayTags = o.tags.split(",");
            arrayTags.map((arr) => {
              if (_.indexOf(arrayOfTags, arr) < 0) {
                arrayOfTags.push({
                  text: arr,
                  value: arr,
                });
              }
            });
          }
        });

        this.tagFilterOption = _.uniqWith(arrayOfTags, _.isEqual);
      }

      if (originalTableList.length > 0) {
        let retiredMediaArray;
        if (this.retiredMediaModel === 0) {
          originalTableList.map((org) => {
            retiredMediaArray = _.filter(originalTableList, ["retired", "0"]);
            originalTableList = retiredMediaArray;
          });
        } else if (this.retiredMediaModel === 1) {
          originalTableList.map((org) => {
            retiredMediaArray = _.filter(originalTableList, ["retired", "1"]);
            originalTableList = retiredMediaArray;
          });
        }
      }

      if (this.inputTagName.length > 0) {
        if (this.mediaTagActive) {
          this.pageNumber = 0;
        }
        this.mediaTagActive = false;
        let newOriginalArray = [];
        originalTableList.map((or) => {
          let tableTagArray = or.tags.split(",");
          if (this.inputTagName.every((val) => tableTagArray.includes(val))) {
            newOriginalArray.push(or);
          }
        });

        originalTableList = _.uniqWith(newOriginalArray, _.isEqual);
      }

      switch (this.mediaType) {
        case "image":
          if (this.mediaTypeActive) {
            this.pageNumber = 0;
          }
          this.mediaTypeActive = false;
          mediaTypeArray = _.filter(originalTableList, ["type", "jpg"]);
          originalTableList = mediaTypeArray;
          break;
        case "video":
          if (this.mediaTypeActive) {
            this.pageNumber = 0;
          }
          this.mediaTypeActive = false;
          mediaTypeArray = _.filter(originalTableList, ["type", "mp4"]);
          originalTableList = mediaTypeArray;
          break;
        default:
          originalTableList;
      }

      if (this.inputFilterName) {
        if (this.inputFilterNameActive) {
          this.pageNumber = 0;
        }
        this.inputFilterNameActive = false;
        originalTableList = originalTableList.filter((t) => {
          return t.name.match(this.inputFilterName);
        });
      }

      const start = this.pageNumber * this.pageSize;
      const end = start + this.pageSize;

      let tableLength = this.tableList.length > 0 && originalTableList.length;

      let count = Math.ceil(tableLength / this.pageSize);

      this.pageCount = count;

      if (this.tableList.length > 0) {
        originalTableList = originalTableList.slice(start, end);
      }

      return originalTableList;
    },
    filteredTableByName: function () {
      return this.tableList.filter((t) => {
        return t.name.match(this.inputFilterName);
      });
    },
    filteredTableByTag: function () {
      return this.tableList.filter((t) => {
        return t.tags.includes(this.inputTagName);
      });
    },
    csvData() {
      return this.tableList.map((item) => ({
        Media_ID: item.media_id,
        Name: item.name,
        Duration: item.duration,
        Type: item.type,
        Size: (item.size / 1000).toFixed(1) + " kb",
        File_name: item.file_name,
        Retired: item.retired === "0" ? "false" : "true",
        Created_at: item.created_at,
        Updated_at: item.updated_at,
      }));
    },
  },
  methods: {
    onClickPageNumber(p) {
      this.pageNumber = p - 1;
    },
    onClickIconLeftArrow() {
      this.pageNumber--;
      console.log(this.pageNumber);
    },
    onClickIconRightArrow() {
      this.pageNumber++;
      console.log(this.pageNumber);
    },
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
    showTagsTableColumn() {
      this.tagsTableColumn = !this.tagsTableColumn;
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
    onClickFooter() {
      console.log("Footer");
      this.isActiveProp = !this.isActiveProp;
      this.isActiveTableRow = [];
    },
    onClickHeader() {
      console.log("Header");
      this.isActiveProp = !this.isActiveProp;
      this.isActiveTableRow = [];
    },
    onClickBody() {
      console.log("Body");
      this.isActiveProp = !this.isActiveProp;
      this.isActiveTableRow = [];
    },
    onClickWithSelected() {
      console.log(this.isActiveTableRow);
      swal(
        "Do you want to delete media with ID " +
          [...this.isActiveTableRow].join(", ") +
          " ?",
        {
          buttons: {
            Cancel: true,
            Delete: {
              value: "delete",
            },
          },
        }
      ).then(async (value) => {
        switch (value) {
          case "delete":
            console.log("DELETED GAN");
            await this.isActiveTableRow.map((a) => {
              axios
                .get("http://127.0.0.1:8000/media/delete/" + a)
                .then((res) => console.log(res));
            });
            await this.onUpdate();
            break;
          default:
            console.log("Cancelled");
        }
      });
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
        .then((res) => (this.tableList = res.data))
        .then(() => {
          this.tableListIdASC = false;
          this.orderByTableListId();
        });
      this.isActiveTableRow = [];
    },
    onInputFilterName() {
      console.log(this.inputFilterName);
    },
    orderByTableListId() {
      this.sortTableListName = false;
      this.sortTableListType = false;
      this.sortTableListSize = false;
      this.sortTableListDuration = false;
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
      this.sortTableListDuration = false;
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
      this.sortTableListDuration = false;
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
      this.sortTableListDuration = false;
      this.sortTableListSize = true;
      let parsedSize = _.forEach(this.tableList, (val) => {
        let number = parseInt(val.size);
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
    orderByTableListDuration() {
      this.sortTableListId = false;
      this.sortTableListName = false;
      this.sortTableListType = false;
      this.sortTableListSize = false;
      this.sortTableListDuration = true;
      let parsedDuration = _.forEach(this.tableList, (val) => {
        let number = parseInt(val.duration);
      });
      if (this.tableListDurationASC) {
        let sortedMediaByDurationASC = _.orderBy(
          parsedDuration,
          "duration",
          "asc"
        );
        this.tableList = sortedMediaByDurationASC;
        this.tableListDurationASC = false;
      } else {
        let sortedMediaByDurationDESC = _.orderBy(
          parsedDuration,
          "duration",
          "desc"
        );
        this.tableList = sortedMediaByDurationDESC;
        this.tableListDurationASC = true;
      }
    },
    createPDF() {
      const docDefinition = {
        pageOrientation: "landscape",
        content: [
          { text: "Tables", style: "header" },
          "Official documentation is in progress, this document is just a glimpse of what is possible with pdfmake and its layout engine.",
          { text: "A simple table", style: "subheader" },
          "The following table has nothing more than a body array",
          {
            style: "tableExample",
            table: {
              widths: [30, "*", "*", "*", 50, "*", "*"],
              heights: [0],
              body: [
                [
                  "ID",
                  { text: "Media Name", style: "tableHeader" },
                  "Type",
                  "Thumbnail",
                  "Duration",
                  "Size (kb)",
                  "Filename",
                ],
              ],
            },
          },
        ],
        styles: {
          header: {
            fontSize: 18,
            bold: true,
            margin: [0, 0, 0, 10],
          },
          subheader: {
            fontSize: 16,
            bold: true,
            margin: [0, 10, 0, 5],
          },
          tableExample: {
            margin: [0, 5, 0, 15],
            alignment: "center",
          },
          tableHeader: {
            bold: true,
            fontSize: 13,
            color: "black",
            alignment: "center",
          },
        },
      };

      console.log(docDefinition);

      for (let i = 0; i < this.tableList.length; i++) {
        function toDataURL(url, callback) {
          var xhr = new XMLHttpRequest();
          xhr.onload = function () {
            var reader = new FileReader();
            reader.onloadend = () => {
              callback(reader.result);
            };
            reader.readAsDataURL(xhr.response);
          };
          xhr.open("GET", url);
          xhr.responseType = "blob";
          xhr.send();
        }

        toDataURL(
          "/storage/uploads/" + this.tableList[i].file_name,
          (dataUrl) => {
            if (this.tableList[i].type === "jpg") {
              docDefinition.content[4].table.body.push([
                { text: this.tableList[i].media_id },
                { text: Object.values(this.tableList[i].name) },
                { text: Object.values(this.tableList[i].type) },
                { image: dataUrl, fit: [25, 25] },
                { text: Object.values(this.tableList[i].duration) },
                { text: Object.values(this.tableList[i].size) },
                { text: Object.values(this.tableList[i].file_name) },
              ]);
            } else {
              docDefinition.content[4].table.body.push([
                { text: this.tableList[i].media_id },
                { text: Object.values(this.tableList[i].name) },
                { text: Object.values(this.tableList[i].type) },
                { text: "" },
                { text: Object.values(this.tableList[i].duration) },
                { text: Object.values(this.tableList[i].size) },
                { text: Object.values(this.tableList[i].file_name) },
              ]);
            }

            if (i === this.tableList.length - 1) {
              pdfMake.createPdf(docDefinition).open();
            }
          }
        );
      }
    },
    createCSV(arrData) {
      let csvContent = "data:text/csv;charset=utf-8,";
      csvContent += [
        Object.keys(arrData[0]).join(","),
        ...arrData.map((item) => Object.values(item).join(",")),
      ]
        .join("\n")
        .replace(/(^\[)|(\]$)/gm, "");

      const data = encodeURI(csvContent);
      const link = document.createElement("a");
      link.setAttribute("href", data);
      link.setAttribute("download", Math.floor(Math.random() * 1000) + ".csv");
      link.click();
    },
  },
};
</script>

