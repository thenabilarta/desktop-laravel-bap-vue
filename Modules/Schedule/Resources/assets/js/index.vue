<template>
  <div id="app">
    <add-event-modal
      v-if="addEventModalIsOpen"
      v-on:closeAddEventModal="closeAddEventModal"
    ></add-event-modal>
    <edit-event-modal
      v-if="editEventModalIsOpen"
      v-on:closeEditEventModal="closeEditEventModal"
      v-bind:idWhenEditEventModalIsOpen="idWhenEditEventModalIsOpen"
      v-bind:showTable="showTable"
      v-bind:currentDate="currentDate"
    ></edit-event-modal>
    <sui-loader v-if="loading" active size="massive" />
    <div class="navigation">
      <h2>{{ selectedDate }} {{ selectedMonth }} {{ selectedYear }}</h2>
      <sui-button @click="openAddEventModal">Add Event</sui-button>
      <sui-dropdown
        placeholder="Select Display"
        selection
        multiple
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
            <i
              @click="onClickYearDisplayIcon(m.id)"
              class="fas fa-desktop"
              v-if="m.display"
            ></i>
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
                  @click="onClickMonthDisplayIcon(d.id)"
                  :class="[
                    d.isPriority > 0 ? 'fas fa-star' : 'fas fa-desktop',
                    d.recurrenceType === null ? '' : 'fa-spin',
                  ]"
                  :style="d.displayGroups.length > 1 ? 'color: red' : ''"
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
                <p
                  @click="onClickDayDisplayTitle(e.id)"
                  v-for="(e, index) in h.event"
                  :key="index"
                  class="day-title"
                >
                  <span class="display-time"
                    >[{{ e.timeStart }} : {{ e.timeEnd }}]</span
                  >
                  {{ e.title }}
                </p>
              </sui-table-cell>
            </sui-table-row>
          </sui-table-body>
        </sui-table>
      </div>
    </main>
    <footer>
      <sui-table celled>
        <sui-table-body>
          <sui-table-row>
            <sui-table-cell>
              <sui-header-content>
                <i class="fas fa-square-full" style="color: red"></i>
              </sui-header-content>
            </sui-table-cell>
            <sui-table-cell> Multiple </sui-table-cell>
          </sui-table-row>
          <sui-table-row>
            <sui-table-cell>
              <sui-header-content>
                <i class="fas fa-star"></i>
              </sui-header-content>
            </sui-table-cell>
            <sui-table-cell> Priority </sui-table-cell>
          </sui-table-row>
          <sui-table-row>
            <sui-table-cell>
              <sui-header-content>
                <i class="fas fa-sync fa-spin"></i>
              </sui-header-content>
            </sui-table-cell>
            <sui-table-cell> Repeat On </sui-table-cell>
          </sui-table-row>
        </sui-table-body>
      </sui-table>
    </footer>
  </div>
</template>

<script>
import axios from "axios";
import dayjs from "dayjs";
import weekday from "dayjs/plugin/weekday";
import weekOfYear from "dayjs/plugin/weekOfYear";
import isBetween from "dayjs/plugin/isBetween";
import AddEventModal from "./components/AddEventModal";
import EditEventModal from "./components/EditEventModal";
import "../css/index.css";
dayjs.extend(weekday);
dayjs.extend(weekOfYear);
dayjs.extend(isBetween);
export default {
  name: "App",
  components: {
    AddEventModal: AddEventModal,
    EditEventModal: EditEventModal,
  },
  data() {
    return {
      currentDate: dayjs().format("YYYY-MM-DD"),
      displayForCurrentDay: false,
      showTable: "month",
      weekdays: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      current: [],
      buttonFilterActive: "month",
      displaySchedule: [],
      showPopUpNumber: null,
      addEventModalIsOpen: false,
      editEventModalIsOpen: false,
      idWhenEditEventModalIsOpen: null,
      loading: false,
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
          text: "All",
          value: 0,
        },
        {
          text: "Display",
          disabled: true,
        },
      ],
    };
  },
  watch: {
    current: function () {
      console.log(this.current);
    },
    currentDate: function () {
      this.loading = true;
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
                  id: r.id,
                  isPriority: r.event.isPriority,
                  displayOrder: r.event.displayOrder,
                  syncTimezone: r.event.syncTimezone,
                  displayGroups: r.event.displayGroups,
                  recurrenceType: r.event.recurrenceType,
                };
                arrayOfData.push(data);
              });
              this.displaySchedule = arrayOfData;
              this.loading = false;
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
                  id: r.id,
                  isPriority: r.event.isPriority,
                  displayOrder: r.event.displayOrder,
                  syncTimezone: r.event.syncTimezone,
                  displayGroups: r.event.displayGroups,
                  recurrenceType: r.event.recurrenceType,
                };
                arrayOfData.push(data);
              });
              this.displaySchedule = arrayOfData;
              this.loading = false;
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
                  id: r.id,
                  isPriority: r.event.isPriority,
                  displayOrder: r.event.displayOrder,
                  syncTimezone: r.event.syncTimezone,
                  displayGroups: r.event.displayGroups,
                  recurrenceType: r.event.recurrenceType,
                };
                arrayOfData.push(data);
              });
              this.displaySchedule = arrayOfData;
              this.loading = false;
            });
          break;
        default:
          console.log("Mantap gan");
      }
    },
  },
  mounted() {
    let startMonth = dayjs(this.currentDate).startOf("month").valueOf();
    let endMonth = dayjs(this.currentDate).endOf("month").valueOf();
    this.loading = true;
    axios
      .all([
        axios.post("http://127.0.0.1:8000/schedule/data", {
          dateFrom: startMonth,
          dateTo: endMonth,
        }),
        axios.get("http://127.0.0.1:8000/display/data"),
        axios.get("http://127.0.0.1:8000/displaygroup/data"),
      ])
      .then((res) => {
        let arrayOfData = [];
        res[0].data.result.map((r) => {
          let data = {
            displayStart: r.start,
            displayEnd: r.end,
            sameDay: r.sameDay,
            title: r.title,
            id: r.id,
            isPriority: r.event.isPriority,
            displayOrder: r.event.displayOrder,
            syncTimezone: r.event.syncTimezone,
            displayGroups: r.event.displayGroups,
            recurrenceType: r.event.recurrenceType,
          };
          arrayOfData.push(data);
        });
        this.displaySchedule = arrayOfData;
        this.loading = false;

        res[1].data.map((d) => {
          this.displayList.push({
            text: d.display,
            value: d.displayId,
          });
        });

        this.displayList.push({
          text: "Display Group",
          disabled: true,
        });

        res[2].data.map((d) => {
          this.displayList.push({
            text: d.displayGroup,
            value: d.displayGroupId,
          });
        });
      });
  },
  methods: {
    refreshPage() {
      console.log("Refresh");
      this.loading = true;
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
                  id: r.id,
                  isPriority: r.event.isPriority,
                  displayOrder: r.event.displayOrder,
                  syncTimezone: r.event.syncTimezone,
                  displayGroups: r.event.displayGroups,
                  recurrenceType: r.event.recurrenceType,
                };
                arrayOfData.push(data);
              });
              this.displaySchedule = arrayOfData;
              this.loading = false;
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
                  id: r.id,
                  isPriority: r.event.isPriority,
                  displayOrder: r.event.displayOrder,
                  syncTimezone: r.event.syncTimezone,
                  displayGroups: r.event.displayGroups,
                  recurrenceType: r.event.recurrenceType,
                };
                arrayOfData.push(data);
              });
              this.displaySchedule = arrayOfData;
              this.loading = false;
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
                  id: r.id,
                  isPriority: r.event.isPriority,
                  displayOrder: r.event.displayOrder,
                  syncTimezone: r.event.syncTimezone,
                  displayGroups: r.event.displayGroups,
                  recurrenceType: r.event.recurrenceType,
                };
                arrayOfData.push(data);
              });
              this.displaySchedule = arrayOfData;
              this.loading = false;
            });
          break;
        default:
          console.log("Mantap gan");
      }
    },
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
                // this.displayList.map((d) => {
                //   s.displayGroups.map((dis) => {
                //     if (d.text === dis.displayGroup && this.current.includes(d.vlaue)) {
                //       console.log(d.text);
                //     }
                //   });
                // });
                if (this.current.includes(0)) {
                  displayProperty.push({
                    exist: true,
                    title: s.title,
                    id: s.id,
                    isPriority: s.isPriority,
                    displayOrder: s.displayOrder,
                    syncTimezone: s.syncTimezone,
                    displayGroups: s.displayGroups,
                    recurrenceType: s.recurrenceType,
                  });
                }
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
              id: r.id,
              isPriority: r.event.isPriority,
              displayOrder: r.event.displayOrder,
              syncTimezone: r.event.syncTimezone,
              displayGroups: r.event.displayGroups,
              recurrenceType: r.event.recurrenceType,
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
              id: r.id,
              isPriority: r.event.isPriority,
              displayOrder: r.event.displayOrder,
              syncTimezone: r.event.syncTimezone,
              displayGroups: r.event.displayGroups,
              recurrenceType: r.event.recurrenceType,
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
              id: r.id,
              isPriority: r.event.isPriority,
              displayOrder: r.event.displayOrder,
              syncTimezone: r.event.syncTimezone,
              displayGroups: r.event.displayGroups,
              recurrenceType: r.event.recurrenceType,
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
      this.refreshPage();
    },
    openAddEventModal() {
      this.addEventModalIsOpen = true;
    },
    closeEditEventModal() {
      this.editEventModalIsOpen = false;
      this.refreshPage();
    },
    openEditEventModal() {
      this.editEventModalIsOpen = true;
    },
    onClickYearDisplayIcon(id) {
      console.log(id);
    },
    onClickMonthDisplayIcon(id) {
      console.log("Display Icon Month clicked, Id = " + id);
      this.editEventModalIsOpen = true;
      this.idWhenEditEventModalIsOpen = id;
    },
    onClickDayDisplayTitle(id) {
      console.log("Display Icon Day clicked, Id = " + id);
      this.editEventModalIsOpen = true;
      this.idWhenEditEventModalIsOpen = id;
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
        let id = null;
        this.displaySchedule.map((d) => {
          if (
            d.displayStart <= currentTime.valueOf() &&
            d.displayEnd >= currentTime.valueOf()
          ) {
            eventArray.push({
              timeStart: dayjs(d.displayStart).format("HH-mm"),
              timeEnd: dayjs(d.displayEnd).format("HH-mm"),
              title: d.title,
              id: d.id,
            });
          }
        });
        arrayOfTimeAndEvent.push({
          time: currentTime.format("HH-mm"),
          event: eventArray,
          id: id,
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
          id: [],
        },
        {
          month: "February",
          display: false,
          id: [],
        },
        {
          month: "March",
          display: false,
          id: [],
        },
        {
          month: "April",
          display: false,
          id: [],
        },
        {
          month: "May",
          display: false,
          id: [],
        },
        {
          month: "June",
          display: false,
          id: [],
        },
        {
          month: "July",
          display: false,
          id: [],
        },
        {
          month: "August",
          display: false,
          id: [],
        },
        {
          month: "September",
          display: false,
          id: [],
        },
        {
          month: "October",
          display: false,
          id: [],
        },
        {
          month: "November",
          display: false,
          id: [],
        },
        {
          month: "December",
          display: false,
          id: [],
        },
      ];
      let checkingCurrentDate = dayjs(this.currentDate).format("YYYY");
      let checkingDisplaySchedule = this.displaySchedule;
      checkingDisplaySchedule.map((c, index) => {
        if (dayjs(c.displayStart).format("YYYY") === checkingCurrentDate) {
          monthArray[
            parseInt(dayjs(c.displayStart).format("MM")) - 1
          ].display = true;
          monthArray[parseInt(dayjs(c.displayStart).format("MM")) - 1].id.push(
            c.id
          );
        }
      });
      return monthArray;
    },
  },
};
</script>