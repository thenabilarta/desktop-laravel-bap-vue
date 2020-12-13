<template>
  <div id="app">
    <div class="navigation">
      <h2>{{ selectedDate }} {{ selectedMonth }} {{ selectedYear }}</h2>
      <sui-button>Add Event</sui-button>
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
          <li class="month-list" v-for="(m, index) in monthsList" :key="index">
            <span @click="onClickMonthOfYear(index)">{{ m }}</span>
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
            :class="c.isCurrentMonth ? '' : 'not-current'"
            v-for="(c, index) in calendarList"
            :key="index"
          >
            <sui-icon v-if="c.displayExist" name="desktop" />
            <span @click="onClickDateNumber(c.date)" class="dayNumber">{{
              c.dayOfMonth
            }}</span>
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
            <sui-table-row>
              <sui-table-cell class="time">00:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">00:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">01:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">01:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">02:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">02:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">03:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">03:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">04:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">04:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">05:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">05:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">06:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">06:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">07:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">07:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">08:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">08:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">09:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">09:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">10:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">10:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">11:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">11:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">12:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">12:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">13:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">13:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">14:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">14:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">15:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">15:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">16:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">16:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">17:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">17:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">18:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">18:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">19:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">19:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">20:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">20:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">21:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">21:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">22:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">22:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">23:00</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
            <sui-table-row>
              <sui-table-cell class="time">23:30</sui-table-cell>
              <sui-table-cell></sui-table-cell>
            </sui-table-row>
          </sui-table-body>
        </sui-table>
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';
import dayjs from 'dayjs';
import weekday from 'dayjs/plugin/weekday';
import weekOfYear from 'dayjs/plugin/weekOfYear';
import '../css/index.css';

dayjs.extend(weekday);
dayjs.extend(weekOfYear);

export default {
  name: 'App',
  data() {
    return {
      currentDate: dayjs().format('YYYY-MM-DD'),
      displayForCurrentDay: false,
      dateToday: null,
      showTable: 'month',
      weekdays: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      current: null,
      buttonFilterActive: 'month',
      displaySchedule: [],
      displayList: [
        {
          text: 'Polytron',
          value: 1,
        },
        {
          text: 'Xiaomi',
          value: 2,
        },
        {
          text: 'Dell',
          value: 3,
        },
        {
          text: 'Samsung',
          value: 4,
        },
      ],
      monthsList: [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December',
      ],
    };
  },
  mounted() {
    axios
      .post('http://127.0.0.1:8000/schedule/data', {
        dateFrom: dayjs(this.calendarList[0].date).valueOf(),
        dateTo: dayjs(this.calendarList.pop().date).valueOf(),
      })
      .then((res) =>
        res.data.result.map((r) => {
          let toSplitStart = dayjs
            .unix(r.start / 1000)
            .format('YYYY-MM-DD-HH-mm')
            .split('-');
          let splittedStart = toSplitStart.splice(0, 3);
          let newDateStart = splittedStart.join('-');
          let toSplitEnd = dayjs
            .unix(r.end / 1000)
            .format('YYYY-MM-DD-HH-mm')
            .split('-');
          let splittedEnd = toSplitEnd.splice(0, 3);
          let newDateEnd = splittedEnd.join('-');
          this.displaySchedule.push({
            title: r.title,
            start: dayjs.unix(r.start / 1000).format('YYYY-MM-DD-HH-mm'),
            end: dayjs.unix(r.end / 1000).format('YYYY-MM-DD-HH-mm'),
            displayDateStart: newDateStart,
            displayDateEnd: newDateEnd,
          });
        })
      );
  },
  methods: {
    getWeekday(date) {
      return dayjs(date).weekday();
    },
    createDaysForCurrentMonth(year, month, schedule) {
      return [...Array(this.getNumberOfDaysInMonth(year, month))].map(
        (day, index) => {
          let displayExist = false;

          if (schedule.length > 0) {
            if (
              dayjs(`${year}-${month}-${index + 1}`).format('YYYY-MM-DD') ===
              schedule[1].displayDateEnd
            ) {
              displayExist = true;
            }
          }

          if (schedule.length > 0) {
            if (
              dayjs(`${year}-${month}-${index + 1}`).format('YYYY-MM-DD') ===
              schedule[1].displayDateStart
            ) {
              displayExist = true;
            }
          }

          return {
            date: dayjs(`${year}-${month}-${index + 1}`).format('YYYY-MM-DD'),
            dayOfMonth: index + 1,
            isCurrentMonth: true,
            displayExist: displayExist,
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
      const previousMonth = dayjs(`${year}-${month}-01`).subtract(1, 'month');
      // Cover first day of the month being sunday (firstDayOfTheMonthWeekday === 0)
      const visibleNumberOfDaysFromPreviousMonth = firstDayOfTheMonthWeekday
        ? firstDayOfTheMonthWeekday - 1
        : 6;
      const previousMonthLastMondayDayOfMonth = dayjs(currentMonthDays[0].date)
        .subtract(visibleNumberOfDaysFromPreviousMonth, 'day')
        .date();
      return [...Array(visibleNumberOfDaysFromPreviousMonth)].map(
        (day, index) => {
          return {
            date: dayjs(
              `${previousMonth.year()}-${previousMonth.month() +
                1}-${previousMonthLastMondayDayOfMonth + index}`
            ).format('YYYY-MM-DD'),
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
      const nextMonth = dayjs(`${year}-${month}-01`).add(1, 'month');
      const visibleNumberOfDaysFromNextMonth = lastDayOfTheMonthWeekday
        ? 7 - lastDayOfTheMonthWeekday
        : lastDayOfTheMonthWeekday;
      return [...Array(visibleNumberOfDaysFromNextMonth)].map((day, index) => {
        return {
          date: dayjs(
            `${nextMonth.year()}-${nextMonth.month() + 1}-${index + 1}`
          ).format('YYYY-MM-DD'),
          dayOfMonth: index + 1,
          isCurrentMonth: false,
        };
      });
    },
    getNumberOfDaysInMonth(year, month) {
      return dayjs(`${year}-${month}-01`).daysInMonth();
    },
    changeToYear() {
      this.showTable = 'year';
      this.buttonFilterActive = 'year';
    },
    changeToMonth() {
      this.showTable = 'month';
      this.buttonFilterActive = 'month';
    },
    changeToDay() {
      this.showTable = 'day';
      this.buttonFilterActive = 'day';
    },
    onClickDateNumber(date) {
      console.log(date);
      this.currentDate = dayjs(date).format('YYYY-MM-DD');
      this.buttonFilterActive = 'day';
      this.showTable = 'day';
    },
    changeDate(e) {
      console.log(e.target.value);
      this.showTable = 'day';
      this.buttonFilterActive = 'day';
    },
    onClickToday() {
      this.currentDate = dayjs().format('YYYY-MM-DD');
    },
    onClickMonthOfYear(i) {
      console.log(i);
      let splittedGetYear = this.currentDate.split('-');
      this.currentDate = dayjs()
        .month(i)
        .year(splittedGetYear[0])
        .format('YYYY-MM-DD');
      this.showTable = 'month';
      this.buttonFilterActive = 'month';
    },
    onClickPrev() {
      switch (this.buttonFilterActive) {
        case 'year':
          this.currentDate = dayjs(this.currentDate)
            .subtract(1, 'year')
            .format('YYYY-MM-DD');
          break;
        case 'month':
          this.currentDate = dayjs(this.currentDate)
            .subtract(1, 'month')
            .format('YYYY-MM-DD');
          break;
        case 'day':
          this.currentDate = dayjs(this.currentDate)
            .subtract(1, 'day')
            .format('YYYY-MM-DD');
          break;
        default:
          console.log('Mantap gan');
      }
    },
    onClickNext() {
      switch (this.buttonFilterActive) {
        case 'year':
          this.currentDate = dayjs(this.currentDate)
            .add(1, 'year')
            .format('YYYY-MM-DD');
          break;
        case 'month':
          this.currentDate = dayjs(this.currentDate)
            .add(1, 'month')
            .format('YYYY-MM-DD');
          break;
        case 'day':
          this.currentDate = dayjs(this.currentDate)
            .add(1, 'day')
            .format('YYYY-MM-DD');
          break;
        default:
          console.log('Mantap gan');
      }
    },
  },
  computed: {
    INITIAL_DATE() {
      let splitted = this.currentDate.split('-');
      return splitted[2];
    },
    INITIAL_MONTH() {
      let splitted = this.currentDate.split('-');
      return splitted[1];
    },
    INITIAL_YEAR() {
      let splitted = this.currentDate.split('-');
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
  },
};
</script>
