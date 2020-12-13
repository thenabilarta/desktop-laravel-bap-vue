<template>
  <div class="edit-display-modal" @click.self="closeModalDisplayEdit">
    <div class="edit-display-modal-content">
      <div class="edit-display-modal-header">
        <h1>Display Edit</h1>
      </div>
      <div class="edit-display-modal-body">
        <div class="edit-display-modal-body-row">
          <label>Display Name</label>
          <sui-input fluid v-model="chosedTable.displayName" />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Display Key</label>
          <sui-input fluid v-model="chosedTable.displayKey" />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Description</label>
          <sui-input fluid v-model="chosedTable.description" />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Tags</label>
          <sui-input fluid v-model="chosedTable.tags" />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Default Layout</label>
          <sui-dropdown
            placeholder="--"
            selection
            :options="propsLayoutData"
            v-model="currentDefaultLayoutValue"
          />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Display Profile</label>
          <sui-dropdown placeholder="--" selection />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Authorize</label>
          <sui-dropdown
            placeholder="--"
            selection
            :options="authorizedOptions"
            v-model="currentAuthorizedValue"
          />
        </div>
      </div>
      <div class="edit-display-modal-actions">
        <sui-button color="yellow" @click="closeEditDisplay">Cancel</sui-button>
        <sui-button color="green" @click="saveEditDisplay">Save</sui-button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";

import "../../css/EditDisplayModal.css";

export default {
  name: "EditDisplayModal",
  props: {
    tableList: Array,
    editDisplayModalOpenId: Number,
    layoutData: Array,
  },
  data() {
    return {
      chosedTable: {
        displayName: "",
        displayKey: "",
        description: "",
        tags: "",
        defaultLayout: "",
        displayProfile: "",
        authorized: null,
      },
      propsLayoutData: [],
      currentDefaultLayoutValue: null,
      currentAuthorizedValue: null,
      authorizedOptions: [
        {
          text: "False",
          value: 0,
        },
        {
          text: "True",
          value: 1,
        },
      ],
    };
  },
  watch: {
    currentDefaultLayoutValue: function() {
      console.log(this.currentDefaultLayoutValue);
    },
  },
  methods: {
    closeEditDisplay() {
      this.closeModalDisplayEdit();
    },
    closeModalDisplayEdit() {
      this.$emit("closeModalDisplayEdit");
    },
    saveEditDisplay() {
      axios
        .post("http://127.0.0.1:8000/display/edit", {
          content: this.chosedTable,
          defaultLayout: this.currentDefaultLayoutValue,
          authorizedValue: this.currentAuthorizedValue,
          displayId: this.editDisplayModalOpenId,
        })
        .then((res) => {
          if (res.data.status === "ok") {
            swal("Saved!", "You've changed the Display properties!", "success");
          }
        })
        .then((res) => this.$emit("fetchedData"));
    },
  },
  mounted() {
    let data = _.filter(this.tableList, (t) => {
      return t.displayId === this.editDisplayModalOpenId;
    });
    this.chosedTable.displayName = data[0].display;
    this.chosedTable.displayKey = data[0].license;
    this.chosedTable.description = data[0].description;
    this.chosedTable.tags = data[0].tags;
    this.chosedTable.defaultLayout = data[0].defaultLayout;
    this.chosedTable.displayProfile = data[0].displayProfileId;
    this.chosedTable.authorized = data[0].licensed;

    this.layoutData.forEach((l) => {
      let oneLayoutData = {
        text: l.layout,
        value: l.layoutId,
      };
      this.propsLayoutData.push(oneLayoutData);

      if (l.layout === data[0].defaultLayout) {
        console.log(l.layout);
        this.currentDefaultLayoutValue = l.layoutId;
      }
    });

    this.currentAuthorizedValue = data[0].licensed;
  },
};
</script>
