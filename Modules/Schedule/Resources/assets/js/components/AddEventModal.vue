<template>
  <div class="edit-display-modal" @click.self="closeAddEventModal">
    <div class="edit-display-modal-content">
      <div class="edit-display-modal-header">
        <h1>Add Event</h1>
      </div>
      <div class="edit-display-modal-body">
        <div class="edit-display-modal-body-row">
          <label>Display</label>
          <sui-input fluid />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Event Type</label>
          <sui-dropdown
            placeholder="--"
            selection
            :options="eventTypeOption"
            v-model="eventType"
          />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Layout / Campaign</label>
          <sui-input fluid />
        </div>
        <div class="edit-display-modal-body-row time">
          <label>From</label>
          <div class="time-input">
            <sui-input type="date" />
            <sui-input type="time" />
          </div>
        </div>
        <div class="edit-display-modal-body-row time">
          <label>To</label>
          <div class="time-input">
            <sui-input type="date" />
            <sui-input type="time" />
          </div>
        </div>
      </div>
      <div class="edit-display-modal-actions">
        <sui-button color="yellow">Cancel</sui-button>
        <sui-button color="green">Save</sui-button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import "../../css/AddEventModal.css";
export default {
  name: "AddEventModal",
  data() {
    return {
      eventType: 1,
      eventTypeOption: [
        {
          text: "Campaign/Layout",
          value: 1,
        },
        {
          text: "Command",
          value: 2,
        },
        {
          text: "Overlay Layout",
          value: 3,
        },
      ],
    };
  },
  methods: {
    closeAddEventModal() {
      this.$emit("closeAddEventModal");
    },
  },
};
</script>