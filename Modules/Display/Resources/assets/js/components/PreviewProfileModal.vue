<template>
  <div class="preview-profile-modal" @click.self="closeModalPreviewProfile">
    <div class="preview-profile-modal-content">
      <div class="preview-profile-modal-header">
        <h1>Preview Profile</h1>
      </div>
      <div class="preview-profile-modal-body">
        <div class="loading" v-if="previewProfileData === null">
          <sui-loader active inline />
        </div>
        <sui-table selectable celled v-else>
          <sui-table-header>
            <sui-table-row>
              <sui-table-header-cell>Setting</sui-table-header-cell>
              <sui-table-header-cell>Value</sui-table-header-cell>
            </sui-table-row>
          </sui-table-header>
          <sui-table-body>
            <sui-table-row>
              <sui-table-cell>Display Profile Id</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.displayProfileId
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Display Profile Name</sui-table-cell>
              <sui-table-cell>{{ previewProfileData.name }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Display Profile Type</sui-table-cell>
              <sui-table-cell>{{ previewProfileData.type }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Collect Interval</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[0].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Download Window Start Time</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[1].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Download Window End Time</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[2].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Enable Power Point</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[3].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Enable Stats</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[4].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Xmr Network Address</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[5].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Size X</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[6].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Size Y</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[7].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Offset X</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[8].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Offset Y</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[9].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Ctrl Key Show Client Info</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[10].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Client Info Key Code</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[11].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Log Level</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[12].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Log to Disk Location</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[13].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Show In Taskbar</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[14].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Cursor Start Position</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[15].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Double Buffering</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[16].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Empty Layout Duration</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[17].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Enable Mouse</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[18].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Enable Shell Command</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[19].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Expire Modified Layout</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[20].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Max Concurrent Downloads</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[21].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Shell Command Allow List</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[22].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Use Cef Web Browser</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[23].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell
                >Send Current Layout as Status Update</sui-table-cell
              >
              <sui-table-cell>{{
                previewProfileData.config[24].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Screenshot Request Interval</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[25].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Screenshot Size</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[26].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Max Log File Uploads</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[27].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Embedded Server Port</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[28].value
              }}</sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell>Prevent Sleep</sui-table-cell>
              <sui-table-cell>{{
                previewProfileData.config[29].value === 1 ? "true" : "false"
              }}</sui-table-cell>
            </sui-table-row>
          </sui-table-body>
        </sui-table>
      </div>
      <div class="preview-profile-modal-actions">
        <sui-button positive content="Close" @click="closeModalButton" />
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";

import "../../css/PreviewProfileModal.css";

export default {
  name: "PreviewProfileModal",
  data() {
    return {
      dataTableList: {},
      previewProfileData: null,
    };
  },
  props: {
    tableList: Array,
    previewProfileModalOpenId: Number,
  },
  mounted() {
    console.log(this.tableList);
    let data = _.filter(this.tableList, (t) => {
      return t.displayId === this.previewProfileModalOpenId;
    });
    this.dataTableList = data[0];
    console.log(this.previewProfileModalOpenId);

    axios
      .post("http://127.0.0.1:8000/display/previewProfileData", {
        displayID: this.tableList.displayProfileId,
      })
      .then((res) => {
        console.log(res.data);
        this.previewProfileData = res.data[0];
      });
  },
  methods: {
    closeModalPreviewProfile() {
      this.$emit("closeModalPreviewProfile");
    },
    closeModalButton() {
      this.$emit("closeModalPreviewProfile");
    },
  },
};
</script>
