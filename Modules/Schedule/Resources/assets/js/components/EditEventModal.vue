<template>
  <div class="edit-display-modal" @click.self="closeEditEventModal">
    <div class="edit-display-modal-content">
      <sui-loader v-if="loading" active size="massive" />
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
        <sui-button color="orange" @click="onDeleteEventClick"
          >Delete</sui-button
        >
        <sui-button color="blue" @click="onEditEventClick"
          >Edit Event</sui-button
        >
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import dayjs from "dayjs";

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
      eventIdAsProps: null,
      loading: false,
      eventTypeOption: [
        {
          text: "Campaign/Layout",
          value: 1,
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
  props: {
    idWhenEditEventModalIsOpen: Number,
    showTable: String,
    currentDate: String,
  },
  watch: {
    displayOption: function () {
      console.log(this.displayOption);
    },
    display: function () {
      console.log(this.display);
    },
  },
  mounted() {
    console.log(this.idWhenEditEventModalIsOpen);
    console.log(this.showTable);
    this.loading = true;
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

        res[1].data.map((l) => {
          this.layoutOption.push({
            text: l.layout,
            value: l.layoutId,
          });
        });
      })
      .then(() => (this.loading = false));
    this.getStartOfTheMonthUnix();
  },
  methods: {
    closeEditEventModal() {
      this.$emit("closeEditEventModal");
    },
    getStartOfTheMonthUnix() {
      let getStartOfMonth = dayjs(this.currentDate).startOf("month").valueOf();
      let getEndOfMonth = dayjs(this.currentDate).endOf("month").valueOf();
      axios
        .post("http://127.0.0.1:8000/schedule/data", {
          dateFrom: getStartOfMonth,
          dateTo: getEndOfMonth,
        })
        .then((res) => {
          res.data.result.map((r) => {
            if (r.id === this.idWhenEditEventModalIsOpen) {
              /* Display Group belom di debug buat edit event */
              console.log(r);
              this.display = r.event.displayGroups[0].displayGroupId;
              this.layout = r.event.campaignId;
              this.dateFrom = dayjs(r.start).format("YYYY-MM-DD");
              this.dateTo = dayjs(r.end).format("YYYY-MM-DD");
              this.timeFrom = dayjs(r.start).format("HH:mm");
              this.timeTo = dayjs(r.end).format("HH:mm");
            }
          });
        });
    },
    onEditEventClick() {
      axios
        .post("http://127.0.0.1:8000/schedule/edit", {
          display: this.display,
          eventType: this.eventType,
          layout: this.layout,
          dateFrom: this.dateFrom,
          dateTo: this.dateTo,
          timeFrom: this.computedTimeFrom,
          timeTo: this.computedTimeTo,
          id: this.idWhenEditEventModalIsOpen,
        })
        .then((res) => console.log(res.data));
    },
    onDeleteEventClick() {
      axios
        .post("http://127.0.0.1:8000/schedule/delete", {
          id: this.idWhenEditEventModalIsOpen,
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