<template>
  <div id="app">
    <add-event-modal
      v-if="addEventModalIsOpen"
      v-on:closeAddEventModal="closeAddEventModal"
    ></add-event-modal>
    <div class="navigation">
      <h2>{{ selectedDate }} {{ selectedMonth }} {{ selectedYear }}</h2>
      <sui-button @click="openAddEventModal">Add Event</sui-button>
      <sui-dropdown
        placeholder="Select Display"
        selection
        :options="displayList"
        v-model="current"
      />
    </div>
    <div class="date-input">
      <sui-input
        placeholder="Search..."
        type="date"
        v-model="currentDate"
        @change="changeDate"
      />
      <sui-button-group>
        <sui-button content="Prev" @click="onClickPrev" />
        <sui-button content="Today" @click="onClickToday" />
        <sui-button content="Next" @click="onClickNext" />
      </sui-button-group>
      <sui-button-group>
        <sui-button
          :class="buttonFilterActive === 'year' && 'button-active'"
          @click="changeToYear"
          >Year</sui-button
        >
        <sui-button
          :class="buttonFilterActive === 'month' && 'button-active'"
          @click="changeToMonth"
          >Month</sui-button
        >
        <sui-button
          :class="buttonFilterActive === 'day' && 'button-active'"
          @click="changeToDay"
          >Day</sui-button
        >
      </sui-button-group>
    </div>
    <main>
      <div v-if="showTable === 'year'" class="year">
        <ol class="month-of-year">
          <li
            class="month-list"
            v-for="(m, index) in monthsListComputed"
            :key="index"
          >
            <span @click="onClickMonthOfYear(index)">{{ m.month }}</span>
            <i class="fas fa-desktop" v-if="m.display"></i>
          </li>
        </ol>
      </div>
      <div v-if="showTable === 'month'">
        <ol class="day-of-week" id="days-of-week">
          <li class="day-list" v-for="(w, index) in weekdays" :key="index">
            {{ w }}
          </li>
        </ol>
        <ol class="days-grid" id="calendar-days">
          <li
            class="calendar-day"
            v-for="(cal, index) in calendarList"
            :class="cal.isCurrentMonth ? '' : 'not-current'"
            :key="index"
          >
            <div
              class="display-icon"
              v-for="(d, index) in cal.displayProperty"
              :key="index"
            >
              <div class="icon-and-popup">
                <i
                  @mouseover="onShowPopUp(cal.date, index)"
                  @mouseleave="offShowPopUp"
                  class="fas fa-desktop"
                ></i>
                <span
                  class="popup"
                  v-if="
                    showPopUpNumber === cal.date + '-' + index ? true : false
                  "
                >
                  {{ d.title }}
                </span>
              </div>
            </div>
            <span @click="onClickDateNumber(cal.date)" class="dayNumber"
              >{{ cal.dayOfMonth }}
            </span>
          </li>
        </ol>
      </div>
      <div class="day" v-if="showTable === 'day'">
        <sui-table striped>
          <sui-table-header>
            <sui-table-row>
              <sui-table-header-cell class="time">Time</sui-table-header-cell>
              <sui-table-header-cell class="events"
                >Events</sui-table-header-cell
              >
            </sui-table-row>
          </sui-table-header>
          <sui-table-body>
            <sui-table-row
              v-for="(h, index) in hourAndEventOfTheDay"
              :key="index"
            >
              <sui-table-cell class="time">{{ h.time }}</sui-table-cell>
              <sui-table-cell>
                <p v-for="(e, index) in h.event" :key="index">{{ e }}</p>
              </sui-table-cell>
            </sui-table-row>
          </sui-table-body>
        </sui-table>
      </div>
    </main>
  </div>
</template>

<script>
import axios from "axios";
import dayjs from "dayjs";
import weekday from "dayjs/plugin/weekday";
import weekOfYear from "dayjs/plugin/weekOfYear";
import isBetween from "dayjs/plugin/isBetween";

import AddEventModal from "./components/AddEventModal";
import "../css/index.css";

dayjs.extend(weekday);
dayjs.extend(weekOfYear);
dayjs.extend(isBetween);

console.log(dayjs().isSame("2020-12-15", "day"));

export default {
  name: "App",
  components: {
    AddEventModal: AddEventModal,
  },
  data() {
    return {
      currentDate: dayjs().format("YYYY-MM-DD"),
      displayForCurrentDay: false,
      showTable: "month",
      weekdays: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      current: 1,
      buttonFilterActive: "month",
      displaySchedule: [],
      showPopUpNumber: null,
      addEventModalIsOpen: false,
      monthsList: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ],
      displayList: [
        {
          text: "All Display",
          value: 1,
        },
        {
          text: "Xiaomi",
          value: 2,
        },
        {
          text: "Dell",
          value: 3,
        },
        {
          text: "Samsung",
          value: 4,
        },
      ],
    };
  },
  watch: {
    currentDate: function () {
      switch (this.showTable) {
        case "year":
          let startYear = dayjs(this.currentDate).startOf("year").valueOf();
          let endYear = dayjs(this.currentDate).endOf("year").valueOf();
          axios
            .post("http://127.0.0.1:8000/schedule/data", {
              dateFrom: startYear,
              dateTo: endYear,
            })
            .then((res) => {
              let arrayOfData = [];
              res.data.result.map((r) => {
                let data = {
                  displayStart: r.start,
                  displayEnd: r.end,
                  sameDay: r.sameDay,
                  title: r.title,
                };
                arrayOfData.push(data);
              });
              this.displaySchedule = arrayOfData;
            });
          break;
        case "month":
          let startMonth = dayjs(this.currentDate).startOf("month").valueOf();
          let endMonth = dayjs(this.currentDate).endOf("month").valueOf();
          axios
            .post("http://127.0.0.1:8000/schedule/data", {
              dateFrom: startMonth,
              dateTo: endMonth,
            })
            .then((res) => {
              let arrayOfData = [];
              res.data.result.map((r) => {
                let data = {
                  displayStart: r.start,
                  displayEnd: r.end,
                  sameDay: r.sameDay,
                  title: r.title,
                };
                arrayOfData.push(data);
              });
              this.displaySchedule = arrayOfData;
            });
          break;
        case "day":
          let startDay = dayjs(this.currentDate).startOf("day").valueOf();
          let endDay = dayjs(this.currentDate).endOf("day").valueOf();
          axios
            .post("http://127.0.0.1:8000/schedule/data", {
              dateFrom: startDay,
              dateTo: endDay,
            })
            .then((res) => {
              let arrayOfData = [];
              res.data.result.map((r) => {
                let data = {
                  displayStart: r.start,
                  displayEnd: r.end,
                  sameDay: r.sameDay,
                  title: r.title,
                };
                arrayOfData.push(data);
              });
              this.displaySchedule = arrayOfData;
            });
          break;
        default:
          console.log("Mantap gan");
      }
    },
    showTable: function () {
      console.log(this.showTable);
    },
  },
  mounted() {
    let startMonth = dayjs(this.currentDate).startOf("month").valueOf();
    let endMonth = dayjs(this.currentDate).endOf("month").valueOf();
    axios
      .post("http://127.0.0.1:8000/schedule/data", {
        dateFrom: startMonth,
        dateTo: endMonth,
      })
      .then((res) => {
        let arrayOfData = [];
        res.data.result.map((r) => {
          let data = {
            displayStart: r.start,
            displayEnd: r.end,
            sameDay: r.sameDay,
            title: r.title,
          };
          arrayOfData.push(data);
        });
        this.displaySchedule = arrayOfData;
      });
  },
  methods: {
    getWeekday(date) {
      return dayjs(date).weekday();
    },
    createDaysForCurrentMonth(year, month, schedule) {
      return [...Array(this.getNumberOfDaysInMonth(year, month))].map(
        (day, index) => {
          let displayProperty = [];
          let dateInUnix = dayjs(`${year}-${month}-${index + 1}`);
          if (schedule.length > 0) {
            schedule.map((s) => {
              if (
                dateInUnix.isBetween(
                  dayjs(s.displayStart),
                  dayjs(s.displayEnd)
                ) ||
                dayjs(s.displayStart).date() === dateInUnix.date() ||
                dayjs(s.displayEnd).date() === dateInUnix.date()
              ) {
                displayProperty.push({
                  exist: true,
                  title: s.title,
                });
              }
            });
          }
          return {
            date: dayjs(`${year}-${month}-${index + 1}`).format("YYYY-MM-DD"),
            dayOfMonth: index + 1,
            isCurrentMonth: true,
            displayProperty: displayProperty,
          };
        }
      );
    },
    createDaysForPreviousMonth(year, month) {
      let currentMonthDays = this.createDaysForCurrentMonth(
        year,
        month,
        dayjs(`${year}-${month}-01`).daysInMonth()
      );
      const firstDayOfTheMonthWeekday = this.getWeekday(
        currentMonthDays[0].date
      );
      const previousMonth = dayjs(`${year}-${month}-01`).subtract(1, "month");
      // Cover first day of the month being sunday (firstDayOfTheMonthWeekday === 0)
      const visibleNumberOfDaysFromPreviousMonth = firstDayOfTheMonthWeekday
        ? firstDayOfTheMonthWeekday - 1
        : 6;
      const previousMonthLastMondayDayOfMonth = dayjs(currentMonthDays[0].date)
        .subtract(visibleNumberOfDaysFromPreviousMonth, "day")
        .date();
      return [...Array(visibleNumberOfDaysFromPreviousMonth)].map(
        (day, index) => {
          return {
            date: dayjs(
              `${previousMonth.year()}-${previousMonth.month() + 1}-${
                previousMonthLastMondayDayOfMonth + index
              }`
            ).format("YYYY-MM-DD"),
            dayOfMonth: previousMonthLastMondayDayOfMonth + index,
            isCurrentMonth: false,
          };
        }
      );
    },
    createDaysForNextMonth(year, month) {
      let currentMonthDays = this.createDaysForCurrentMonth(
        year,
        month,
        dayjs(`${year}-${month}-01`).daysInMonth()
      );
      const lastDayOfTheMonthWeekday = this.getWeekday(
        `${year}-${month}-${currentMonthDays.length}`
      );
      const nextMonth = dayjs(`${year}-${month}-01`).add(1, "month");
      const visibleNumberOfDaysFromNextMonth = lastDayOfTheMonthWeekday
        ? 7 - lastDayOfTheMonthWeekday
        : lastDayOfTheMonthWeekday;
      return [...Array(visibleNumberOfDaysFromNextMonth)].map((day, index) => {
        return {
          date: dayjs(
            `${nextMonth.year()}-${nextMonth.month() + 1}-${index + 1}`
          ).format("YYYY-MM-DD"),
          dayOfMonth: index + 1,
          isCurrentMonth: false,
        };
      });
    },
    getNumberOfDaysInMonth(year, month) {
      return dayjs(`${year}-${month}-01`).daysInMonth();
    },
    changeToYear() {
      let startYear = dayjs(this.currentDate).startOf("year").valueOf();
      let endYear = dayjs(this.currentDate).endOf("year").valueOf();
      axios
        .post("http://127.0.0.1:8000/schedule/data", {
          dateFrom: startYear,
          dateTo: endYear,
        })
        .then((res) => {
          let arrayOfData = [];
          res.data.result.map((r) => {
            let data = {
              displayStart: r.start,
              displayEnd: r.end,
              sameDay: r.sameDay,
              title: r.title,
            };
            arrayOfData.push(data);
          });
          this.displaySchedule = arrayOfData;
        });
      this.showTable = "year";
      this.buttonFilterActive = "year";
    },
    changeToMonth() {
      let startMonth = dayjs(this.currentDate).startOf("month").valueOf();
      let endMonth = dayjs(this.currentDate).endOf("month").valueOf();
      axios
        .post("http://127.0.0.1:8000/schedule/data", {
          dateFrom: startMonth,
          dateTo: endMonth,
        })
        .then((res) => {
          let arrayOfData = [];
          res.data.result.map((r) => {
            let data = {
              displayStart: r.start,
              displayEnd: r.end,
              sameDay: r.sameDay,
              title: r.title,
            };
            arrayOfData.push(data);
          });
          this.displaySchedule = arrayOfData;
        });
      this.showTable = "month";
      this.buttonFilterActive = "month";
    },
    changeToDay() {
      let startDay = dayjs(this.currentDate).startOf("day").valueOf();
      let endDay = dayjs(this.currentDate).endOf("day").valueOf();
      axios
        .post("http://127.0.0.1:8000/schedule/data", {
          dateFrom: startDay,
          dateTo: endDay,
        })
        .then((res) => {
          let arrayOfData = [];
          res.data.result.map((r) => {
            let data = {
              displayStart: r.start,
              displayEnd: r.end,
              sameDay: r.sameDay,
              title: r.title,
            };
            arrayOfData.push(data);
          });
          this.displaySchedule = arrayOfData;
        });
      this.showTable = "day";
      this.buttonFilterActive = "day";
    },
    onClickDateNumber(date) {
      this.currentDate = dayjs(date).format("YYYY-MM-DD");
      this.buttonFilterActive = "day";
      this.showTable = "day";
    },
    changeDate(e) {
      this.showTable = "day";
      this.buttonFilterActive = "day";
    },
    onClickToday() {
      this.currentDate = dayjs().format("YYYY-MM-DD");
    },
    onClickMonthOfYear(i) {
      let splittedGetYear = this.currentDate.split("-");
      this.currentDate = dayjs()
        .month(i)
        .year(splittedGetYear[0])
        .format("YYYY-MM-DD");
      this.showTable = "month";
      this.buttonFilterActive = "month";
    },
    onClickPrev() {
      switch (this.buttonFilterActive) {
        case "year":
          this.currentDate = dayjs(this.currentDate)
            .subtract(1, "year")
            .format("YYYY-MM-DD");
          break;
        case "month":
          this.currentDate = dayjs(this.currentDate)
            .subtract(1, "month")
            .format("YYYY-MM-DD");
          break;
        case "day":
          this.currentDate = dayjs(this.currentDate)
            .subtract(1, "day")
            .format("YYYY-MM-DD");
          break;
        default:
          console.log("Mantap gan");
      }
    },
    onClickNext() {
      switch (this.buttonFilterActive) {
        case "year":
          this.currentDate = dayjs(this.currentDate)
            .add(1, "year")
            .format("YYYY-MM-DD");
          break;
        case "month":
          this.currentDate = dayjs(this.currentDate)
            .add(1, "month")
            .format("YYYY-MM-DD");
          break;
        case "day":
          this.currentDate = dayjs(this.currentDate)
            .add(1, "day")
            .format("YYYY-MM-DD");
          break;
        default:
          console.log("Mantap gan");
      }
    },
    onShowPopUp(date, index) {
      this.showPopUpNumber = date + "-" + index;
    },
    offShowPopUp() {
      this.showPopUpNumber = null;
    },
    closeAddEventModal() {
      this.addEventModalIsOpen = false;
    },
    openAddEventModal() {
      this.addEventModalIsOpen = true;
    },
  },
  computed: {
    INITIAL_DATE() {
      let splitted = this.currentDate.split("-");
      return splitted[2];
    },
    INITIAL_MONTH() {
      let splitted = this.currentDate.split("-");
      return splitted[1];
    },
    INITIAL_YEAR() {
      let splitted = this.currentDate.split("-");
      return splitted[0];
    },
    calendarList() {
      return [
        ...this.createDaysForPreviousMonth(
          this.INITIAL_YEAR,
          this.INITIAL_MONTH
        ),
        ...this.createDaysForCurrentMonth(
          this.INITIAL_YEAR,
          this.INITIAL_MONTH,
          this.displaySchedule
        ),
        ...this.createDaysForNextMonth(this.INITIAL_YEAR, this.INITIAL_MONTH),
      ];
    },
    selectedDate() {
      return this.INITIAL_DATE;
    },
    selectedMonth() {
      return this.monthsList[this.INITIAL_MONTH - 1];
    },
    selectedYear() {
      return this.INITIAL_YEAR;
    },
    hourAndEventOfTheDay() {
      let arrayOfTimeAndEvent = [];
      let currentTime = dayjs(this.currentDate);
      do {
        let eventArray = [];
        this.displaySchedule.map((d) => {
          if (
            d.displayStart <= currentTime.valueOf() &&
            d.displayEnd >= currentTime.valueOf()
          ) {
            eventArray.push(d.title);
          }
        });
        arrayOfTimeAndEvent.push({
          time: currentTime.format("HH-mm"),
          event: eventArray,
        });
        currentTime = currentTime.add(30, "minute");
      } while (currentTime.format("HH-mm") !== "00-00");
      return arrayOfTimeAndEvent;
    },
    monthsListComputed() {
      let monthArray = [
        {
          month: "January",
          display: false,
        },
        {
          month: "February",
          display: false,
        },
        {
          month: "March",
          display: false,
        },
        {
          month: "April",
          display: false,
        },
        {
          month: "May",
          display: false,
        },
        {
          month: "June",
          display: false,
        },
        {
          month: "July",
          display: false,
        },
        {
          month: "August",
          display: false,
        },
        {
          month: "September",
          display: false,
        },
        {
          month: "October",
          display: false,
        },
        {
          month: "November",
          display: false,
        },
        {
          month: "December",
          display: false,
        },
      ];
      let checkingCurrentDate = dayjs(this.currentDate).format("YYYY");
      let checkingDisplaySchedule = this.displaySchedule;
      checkingDisplaySchedule.map((c, index) => {
        if (dayjs(c.displayStart).format("YYYY") === checkingCurrentDate) {
          monthArray[
            parseInt(dayjs(c.displayStart).format("MM")) - 1
          ].display = true;
        }
      });
      return monthArray;
    },
  },
};
</script>