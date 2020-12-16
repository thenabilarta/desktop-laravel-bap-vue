<template>
  <div class="edit-display-modal" @click.self="closeAddEventModal">
    <div class="edit-display-modal-content">
      <div class="edit-display-modal-header">
        <h1>Edit Event</h1>
      </div>
      <div class="edit-display-modal-body">
        <div class="edit-display-modal-body-row">
          <label>Display / Display Group</label>
          <sui-dropdown
            selection
            search
            :options="displayOption"
            v-model="display"
          />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Event Type</label>
          <sui-dropdown
            selection
            :options="eventTypeOption"
            v-model="eventType"
          />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Layout / Campaign</label>
          <sui-dropdown
            selection
            search
            :options="layoutOption"
            v-model="layout"
          />
        </div>
        <div class="edit-display-modal-body-row time">
          <label>From</label>
          <div class="time-input">
            <sui-input type="date" v-model="dateFrom" />
            <sui-input type="time" v-model="timeFrom" />
          </div>
        </div>
        <div class="edit-display-modal-body-row time">
          <label>To</label>
          <div class="time-input">
            <sui-input type="date" v-model="dateTo" />
            <sui-input type="time" v-model="timeTo" />
          </div>
        </div>
      </div>
      <div class="edit-display-modal-actions">
        <sui-button>Cancel</sui-button>
        <sui-button color="green" @click="onAddEventClick"
          >Edit Event</sui-button
        >
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

import "../../css/EditEventModal.css";

export default {
  name: "EditEventModal",
  data() {
    return {
      eventType: 1,
      display: null,
      layout: null,
      dateFrom: "",
      dateTo: "",
      timeFrom: "",
      timeTo: "",
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
      displayOption: [
        {
          text: "Display",
          disabled: true,
        },
      ],
      layoutOption: [
        {
          text: "Layouts",
          disabled: true,
        },
      ],
    };
  },
  watch: {
    timeFrom: function () {
      console.log(this.timeFrom);
    },
  },
  mounted() {
    axios
      .all([
        axios.get("http://127.0.0.1:8000/display/data"),
        axios.get("http://127.0.0.1:8000/layout/data"),
      ])
      .then((res) => {
        console.log(res[0].data);
        res[0].data.map((d) => {
          this.displayOption.push({
            text: d.display,
            value: d.displayId,
          });
        });
        console.log(res[1].data);
        res[1].data.map((l) => {
          this.layoutOption.push({
            text: l.layout,
            value: l.layoutId,
          });
        });
      });
  },
  methods: {
    closeAddEventModal() {
      this.$emit("closeAddEventModal");
    },
    onAddEventClick() {
      axios
        .post("http://127.0.0.1:8000/schedule", {
          display: this.display,
          eventType: this.eventType,
          layout: this.layout,
          dateFrom: this.dateFrom,
          dateTo: this.dateTo,
          timeFrom: this.computedTimeFrom,
          timeTo: this.computedTimeTo,
        })
        .then((res) => console.log(res.data));
    },
  },
  computed: {
    computedTimeFrom() {
      let newTimeFrom = this.timeFrom.split(":");
      let tFrom = newTimeFrom.join("%3A") + "%3A00";

      return tFrom;
    },
    computedTimeTo() {
      let newTimeFrom = this.timeTo.split(":");
      let tTo = newTimeFrom.join("%3A") + "%3A00";

      return tTo;
    },
  },
};
</script>