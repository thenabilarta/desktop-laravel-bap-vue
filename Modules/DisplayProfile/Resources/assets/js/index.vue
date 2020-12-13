<template>
  <div>
    <div class="header">
      <div class="header-icon">
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
    <div class="body">
      <div class="loading" v-if="tableList === null">
        <sui-loader active inline />
      </div>
      <sui-table selectable celled v-else>
        <sui-table-header>
          <sui-table-row>
            <sui-table-header-cell>Name</sui-table-header-cell>
            <sui-table-header-cell>Type</sui-table-header-cell>
            <sui-table-header-cell>Default</sui-table-header-cell>
            <sui-table-header-cell
              class="dropdown-table-cell"
            ></sui-table-header-cell>
          </sui-table-row>
        </sui-table-header>
        <sui-table-body>
          <sui-table-row v-for="list in tableList" :key="list.displayProfileId">
            <sui-table-cell>{{ list.name }}</sui-table-cell>
            <sui-table-cell>{{ list.type }}</sui-table-cell>
            <sui-table-cell>{{
              list.isDefault === 1 ? "true" : "false"
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
import pdfMake from "pdfmake";

export default {
  data() {
    return {
      tableList: null,
    };
  },
  mounted() {
    axios.get("http://127.0.0.1:8000/displayprofile/data").then((res) => {
      console.log(res.data);
      this.tableList = res.data;
    });
  },
  computed: {
    csvData() {
      return this.tableList.map((item) => ({
        Name: item.name,
        Type: item.type,
        Default: item.isDefault === 1 ? "true" : "false",
      }));
    },
  },
  methods: {
    createPDF() {
      const docDefinition = {
        content: [
          { text: "Display Profile Report", style: "header" },
          { text: "List of Display Profile", style: "normalText" },
          { text: "Date : ", style: "normalText" },
          {
            style: "tableExample",
            table: {
              widths: ["*", "*", "*"],
              body: [
                [
                  { text: "Name", style: "tableHeader" },
                  { text: "Type", style: "tableHeader" },
                  { text: "Default", style: "tableHeader" },
                ],
              ],
            },
          },
        ],
        styles: {
          header: {
            fontSize: 20,
            bold: true,
            margin: [0, 0, 0, 20],
          },
          subheader: {
            fontSize: 16,
            bold: true,
            margin: [0, 10, 0, 5],
          },
          normalText: {
            fontSize: 14,
            margin: [0, 0, 0, 10],
          },
          tableText: {
            fontSize: 14,
            margin: [0, 2, 0, 2],
          },
          tableExample: {
            margin: [0, 15, 0, 15],
          },
          tableHeader: {
            bold: true,
            fontSize: 16,
            color: "black",
            margin: [0, 2, 0, 2],
          },
        },
      };
      for (let i = 0; i < this.tableList.length; i++) {
        docDefinition.content[3].table.body.push(
          // Object.values(this.tableList[i])
          [
            { text: Object.values(this.tableList[i].name), style: "tableText" },
            { text: Object.values(this.tableList[i].type), style: "tableText" },
            {
              text: Object.values(
                this.tableList[i].isDefault === 1 ? "true" : "false"
              ),
              style: "tableText",
              italics: true,
            },
          ]
        );
      }
      pdfMake.createPdf(docDefinition).open();
    },
    createCSV(arrData) {
      let csvContent = "data:text/csv;charset=utf-8,";
      csvContent += [
        Object.keys(arrData[0]).join(";"),
        ...arrData.map((item) => Object.values(item).join(";")),
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
