<template>
  <div class="edit-table-modal" @click.self="closeModal">
    <div class="edit-table-modal-content">
      <div class="edit-table-modal-header">
        <h1>Edit Media</h1>
      </div>
      <div class="edit-table-modal-body">
        <div class="edit-table-modal-body-1">
          <sui-input
            :placeholder="list.name"
            v-model="form.listName"
            @keydown="onChangeInput"
            class="edit-table-input-name"
          />
          <sui-input
            type="number"
            class="edit-table-input-number"
            v-model="form.duration"
            :placeholder="list.duration"
          />
        </div>
        <div class="edit-table-modal-body-2">
          <sui-input placeholder="Media Tags" fluid />
        </div>
        <div class="edit-table-modal-body-3">
          <div class="edit-table-modal-body-3-1">
            <sui-checkbox label="Retired" toggle false />
            <!-- <p>Retired</p> -->
          </div>
          <div class="edit-table-modal-body-3-2">
            <sui-checkbox
              label="Update in all layouts where they have been assigned"
              toggle
              true
            />
            <!-- <p>Update in all layouts where they have been assigned</p> -->
          </div>
        </div>
      </div>
      <div class="edit-table-modal-actions">
        <sui-button size="small" color="green" @click="formSubmit"
          >Replace</sui-button
        >
        <sui-button size="small" color="yellow">Cancel</sui-button>
        <sui-button size="small" color="teal">Help</sui-button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

import "../../css/edit-table-modal.css";

export default {
  name: "EditTableRow",
  data() {
    return {
      form: {
        listName: this.list.name,
        media_id: this.list.media_id,
        duration: this.list.duration,
      },
    };
  },
  props: {
    list: Object,
  },
  methods: {
    closeModal() {
      this.$emit("closeModal");
    },
    onChangeInput() {
      console.log(this.form.listName);
    },
    formSubmit() {
      axios
        .post("http://127.0.0.1:8000/media/edit", this.form)
        .then((res) => console.log(res.data))
        .then(() => this.tableEditBind());
    },
    tableEditBind() {
      this.$emit("updateEdit");
    },
  },
};
</script>

<style scoped>
i.icon {
  margin: 0 !important;
}
</style>
