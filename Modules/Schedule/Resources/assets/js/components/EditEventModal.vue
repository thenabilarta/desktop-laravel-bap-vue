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
            multiple
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
        <div class="edit-display-modal-body-row">
          <label>Dayparting</label>
          <sui-dropdown
            selection
            :options="daypartingOption"
            v-model="dayparting"
          />
        </div>
        <div class="edit-display-modal-body-row time" v-if="dayparting !== 2">
          <label>From</label>
          <div class="time-input">
            <sui-input
              type="date"
              v-model="dateFrom"
              :class="dayparting > 2 && 'full-width-input'"
            />
            <sui-input type="time" v-model="timeFrom" v-if="dayparting === 1" />
          </div>
        </div>
        <div class="edit-display-modal-body-row time" v-if="dayparting === 1">
          <label>To</label>
          <div class="time-input">
            <sui-input type="date" v-model="dateTo" />
            <sui-input type="time" v-model="timeTo" />
          </div>
        </div>
        <div class="edit-display-modal-body-row">
          <label>Display Order</label>
          <sui-input
            type="number"
            class="input-number"
            v-model="displayOrder"
          />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Priority</label>
          <sui-input type="number" class="input-number" v-model="isPriority" />
        </div>
        <div class="edit-display-modal-body-row">
          <sui-checkbox
            label="Run at CMS Time?"
            toggle
            v-model="syncTimezone"
          />
        </div>
        <div class="edit-display-modal-body-row">
          <label>Repeats</label>
          <sui-dropdown selection :options="repeatOption" v-model="repeat" />
        </div>
        <div class="edit-display-modal-body-row" v-if="repeat !== 0">
          <label>Repeat Every</label>
          <sui-input type="number" class="input-number" v-model="repeatEvery" />
        </div>
        <div class="edit-display-modal-body-row" v-if="repeat === 4">
          <label>Day</label>
          <sui-dropdown
            selection
            :options="repeatDayOption"
            v-model="repeatDay"
          />
        </div>
        <div class="edit-display-modal-body-row time" v-if="repeat !== 0">
          <label>Until</label>
          <div class="time-input">
            <sui-input type="date" v-model="dateFromUntil" />
            <sui-input type="time" v-model="timeFromUntil" />
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
      display: [],
      layout: null,
      dateFrom: "",
      dateTo: "",
      timeFrom: "",
      timeTo: "",
      eventIdAsProps: null,
      loading: false,
      isPriority: null,
      displayOrder: null,
      syncTimezone: false,
      dateFromUntil: null,
      timeFromUntil: null,
      repeat: 0,
      repeatDay: 1,
      repeatEvery: null,
      dayparting: 1,
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
      daypartingOption: [
        {
          text: "Custom",
          value: 1,
        },
        {
          text: "Always",
          value: 2,
        },
      ],
      repeatOption: [
        {
          text: "None",
          value: 0,
        },
        {
          text: "Per Minute",
          value: 1,
        },
        {
          text: "Hourly",
          value: 2,
        },
        {
          text: "Daily",
          value: 3,
        },
        {
          text: "Weekly",
          value: 4,
        },
        {
          text: "Monthly",
          value: 5,
        },
        {
          text: "Yearly",
          value: 6,
        },
      ],
      repeatDayOption: [
        {
          text: "Monday",
          value: 1,
        },
        {
          text: "Tuesday",
          value: 2,
        },
        {
          text: "Wednesday",
          value: 3,
        },
        {
          text: "Thursday",
          value: 4,
        },
        {
          text: "Friday",
          value: 5,
        },
        {
          text: "Saturday",
          value: 6,
        },
        {
          text: "Sunday",
          value: 7,
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
        axios.get("http://127.0.0.1:8000/displaygroup/data"),
        axios.get("http://127.0.0.1:8000/campaign/data"),
        axios.get("http://127.0.0.1:8000/dayparting/data"),
      ])
      .then((res) => {
        console.log(res);
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
        this.displayOption.push({
          text: "Display Group",
          disabled: true,
        });
        res[2].data.map((d) => {
          this.displayOption.push({
            text: d.displayGroup,
            value: d.displayGroupId,
          });
        });
        this.layoutOption.push({
          text: "Campaign",
          disabled: true,
        });
        res[3].data.map((c) => {
          this.layoutOption.push({
            text: c.campaign,
            value: c.campaignId,
          });
        });
        res[4].data.map((daypart) => {
          if (daypart.dayPartId > 2) {
            this.daypartingOption.push({
              text: daypart.name,
              value: daypart.dayPartId,
            });
          }
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
          console.log(res);
          res.data.result.map((r) => {
            if (r.id === this.idWhenEditEventModalIsOpen) {
              /* Display Group belom di debug buat edit event */
              r.event.displayGroups.map((d) => {
                this.display.push(d.displayGroupId);
              });
              this.layout = r.event.campaignId;
              this.dateFrom = dayjs(r.start).format("YYYY-MM-DD");
              this.dateTo = dayjs(r.end).format("YYYY-MM-DD");
              this.timeFrom = dayjs(r.start).format("HH:mm");
              this.timeTo = dayjs(r.end).format("HH:mm");
              this.isPriority = r.event.isPriority;
              this.displayOrder = r.event.displayOrder;
              this.syncTimezone = r.event.syncTimezone === 1 ? true : false;
              switch (r.event.recurrenceType) {
                case "Year":
                  this.repeat = 6;
                  break;
                case "Month":
                  this.repeat = 5;
                  break;
                case "Week":
                  this.repeat = 4;
                  break;
                case "Day":
                  this.repeat = 3;
                  break;
                case "Hour":
                  this.repeat = 2;
                  break;
                case "Minute":
                  this.repeat = 1;
                  break;
                default:
                  null;
              }
              this.repeatEvery = r.event.recurrenceDetail;
              this.dateFromUntil = dayjs
                .unix(r.event.recurrenceRange)
                .format("YYYY-MM-DD");
              this.timeFromUntil = dayjs
                .unix(r.event.recurrenceRange)
                .format("HH:mm");
              this.repeatDay = r.event.recurrenceRepeatsOn;
              this.dayparting = r.event.dayPartId;
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
          isPriority: this.isPriority,
          displayOrder: this.displayOrder,
          syncTimezone: this.syncTimezone === true ? "on" : "off",
          repeat: this.repeat,
          repeatEvery: this.repeatEvery,
          repeatDay: this.repeatDay,
          dateFromUntil: this.dateFromUntil,
          timeFromUntil: this.repeatUntil,
          dayparting: this.dayparting,
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
      if (this.timeFrom) {
        let newTimeFrom = this.timeFrom.split(":");
        let tFrom = newTimeFrom.join("%3A") + "%3A00";
        return tFrom;
      } else {
        return this.timeFrom;
      }
    },
    computedTimeTo() {
      if (this.timeTo) {
        let newTimeFrom = this.timeTo.split(":");
        let tTo = newTimeFrom.join("%3A") + "%3A00";
        return tTo;
      } else {
        return this.timeTo;
      }
    },
    repeatUntil() {
      if (this.timeFromUntil) {
        let newRepeatUntil = this.timeFromUntil.split(":");
        let repeatTo = newRepeatUntil.join("%3A") + "%3A00";
        return repeatTo;
      } else {
        return this.timeFromUntil;
      }
    },
  },
};
</script>