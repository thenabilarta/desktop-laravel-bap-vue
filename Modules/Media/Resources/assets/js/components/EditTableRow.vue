<template>
  <div class="edit-table-modal" @click.self="closeModal">
    <div class="edit-table-modal-content" @click.self="modalContent">
      <div class="edit-table-modal-header">
        <h1>Edit Media</h1>
      </div>
      <div class="edit-table-modal-body">
        <div class="edit-table-modal-body-1">
          <div class="edit-table-modal-body-input-name">
            <sui-input
              placeholder="name"
              v-model="form.listName"
              @keydown="onChangeInput"
              class="edit-table-input-name"
              :error="
                form.listName === '' || form.listName.split('').length > 50
              "
            />
            <p v-if="form.listName === ''">Nama harus diisi</p>
            <p v-if="form.listName.split('').length > 50">
              Maksimum nama 50 karakter
            </p>
          </div>
          <div class="edit-table-modal-body-input-number">
            <sui-input
              type="number"
              class="edit-table-input-number"
              v-model="form.duration"
              placeholder="duration"
              :error="form.duration <= 0"
            />
            <p v-if="form.duration <= 0">Durasi harus diisi</p>
          </div>
        </div>
        <div class="edit-table-modal-body-2">
          <sui-dropdown
            multiple
            placeholder="Tags"
            fluid
            search
            selection
            allow-additions
            v-model="form.tags"
            id="inputTags"
            @keyup="onChangeInputTags"
          />
        </div>
        <div class="edit-table-modal-body-3">
          <div class="edit-table-modal-body-3-1">
            <sui-checkbox
              label="Retired"
              toggle
              @change="toggleRetired"
              v-model="form.retired"
            />
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
        <sui-button
          size="small"
          color="green"
          @click="formSubmit"
          :disabled="
            form.listName === '' ||
            form.duration <= 0 ||
            form.listName.split('').length > 50
          "
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
        tags: this.list.tags === "" ? null : this.list.tags.split(","),
        retired: this.list.retired === "0" ? false : true,
      },
    };
  },
  props: {
    list: Object,
  },
  watch: {
    form: function () {
      console.log(this.form.tags);
    },
  },
  mounted() {
    console.log(this.list);
  },
  methods: {
    toggleRetired() {
      console.log(this.form.retired);
    },
    closeModal() {
      this.$emit("closeModal");
    },
    modalContent() {
      console.log("Modal content");
    },
    onChangeInput() {
      console.log(this.form.listName);
    },
    onChangeInputTags() {
      console.log(this.form.tags);
    },
    formSubmit() {
      if (this.form.tags !== null && this.form.tags.length > 0) {
        this.form.tags = this.form.tags.join();
      }
      if (this.form.retired === false) {
        this.form.retired === "0";
      } else {
        this.form.retired === "1";
      }
      axios
        .post("http://127.0.0.1:8000/media/edit", this.form)
        .then((res) => console.log(res.data))
        .then(() => this.closeModal());
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
