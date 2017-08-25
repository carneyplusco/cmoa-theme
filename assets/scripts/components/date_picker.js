import React, { Component } from 'react';
import moment from 'moment';
import R from 'ramda';
import classNames from 'classnames';

const mapIndexed = R.addIndex(R.map);

const is_outside = (day, month) => {
  const start_of_month = month.clone().startOf('month');
  const end_of_month = month.clone().endOf('month');
  return day.isBefore(start_of_month) || day.isAfter(end_of_month);
}

export default class DatePicker extends Component {
  constructor(props) {
    super(props);
    this.state = {
      calendar_open: false,
      current_month: moment(),
      start_date: moment(),
      end_date: moment(),
      range_selected: false
    }
  }

  componentDidMount() {
    const moment_start = moment(this.props.start_date);
    const moment_end = moment(this.props.end_date);
    const { current_month } = this.state;
    if(moment_start.isBefore(current_month.clone().startOf('month').day(0))) {
      current_month.subtract(1, 'month');
    }
    else if(moment_start.isAfter(current_month.clone().endOf('month').day(6))) {
      current_month.add(1, 'month');
    }
    this.setState({start_date: moment_start, end_date: moment_end, current_month: current_month});
  }

  componentDidUpdate(prevProps, prevState) {
    const { range_selected } = this.state;
    if(range_selected) {
      document.getElementById('calendar-form').submit();
    }
  }

  toggleCalendar = (e) => {
    if(!e.charCode || e.charCode === 13) {
      this.setState({calendar_open: !this.state.calendar_open});
    }
  };

  getPrevMonth = (e) => {
    e.preventDefault();
    const prev_month = this.state.current_month.subtract(1, 'month');
    this.setState({current_month: prev_month});
  };

  getNextMonth = (e) => {
    e.preventDefault();
    const next_month = this.state.current_month.add(1, 'month');
    this.setState({current_month: next_month});
  };

  setStartDate = (day, e) => {
    const { start_date, end_date, range_selected } = this.state;
    this.setState({start_date: day.clone(), end_date: day.clone().add(30,'d'), range_selected: true});
  };

  setDateRange = (day, e) => {
    const { start_date, end_date, range_selected } = this.state;
    if(start_date && start_date.isSame(day, 'day')) {
      this.setState({start_date: null, end_date: null, range_selected: false});
    }
    else if(start_date && start_date.isBefore(day) && !range_selected) {
      this.setState({end_date: day.clone(), range_selected: true});
    }
    else {
      this.setState({start_date: day.clone(), end_date: null, range_selected: false});
    }
  };

  hoverDate = (day, e) => {
    const { start_date, end_date, range_selected } = this.state;
    if(start_date && start_date.isBefore(day) && !range_selected) {
      this.setState({end_date: day.clone()});
    }
  };

  render() {
    const { current_month, start_date, end_date, calendar_open } = this.state;
    let first = current_month.clone().startOf('month').day(0);
    let last = current_month.clone().endOf('month').day(6);
    let days = [];
    while(first.isBefore(last)) {
      days.push(first.clone());
      first.add(1,'d');
    }
    const cal_row = (day_arr, index) => <CalendarRow key={index} days={day_arr} {...this.state} clickDate={this.setStartDate} />;
    const make_rows = R.compose(mapIndexed(cal_row), R.splitEvery(7));
    const current_month_label = `Selected month ${current_month.format('MMMM YYYY')}`;
    return (
      <div className="datepicker-wrapper">
        <div className={classNames('calendar-control', { open: calendar_open })} tabIndex="0" onClick={this.toggleCalendar} onKeyPress={this.toggleCalendar}>
          <strong>Date Range:</strong>
          <span className="start-date" aria-label={start_date.format('MMMM Do YYYY')}>{start_date && start_date.format('MMM. D')}</span>
          <span className="end-date" aria-label={end_date.format('MMMM Do YYYY')}>{end_date && end_date.format('MMM. D')}</span>
        </div>
        <div className={classNames('calendar-widget', { open: calendar_open })}>
          <nav className="calendar-nav">
            <ul>
              <li className="prev-month"><a tabIndex="0" href="#" aria-label="Previous month" onClick={this.getPrevMonth}>‹</a></li>
              <li className="month-name"><p aria-label={current_month_label}>{current_month.format('MMMM YYYY')}</p></li>
              <li className="next-month"><a tabIndex="0" href="#" aria-label="Next month" onClick={this.getNextMonth}>›</a></li>
            </ul>
          </nav>
          <div className="day-list">
            {make_rows(days)}
          </div>
          <input type="hidden" name="start" value={start_date && start_date.format('YYYY-MM-DD')} />
          <input type="hidden" name="end" value={end_date && end_date.format('YYYY-MM-DD')} />
        </div>
      </div>
    );
  }
}

const CalendarRow = ({days, current_month, start_date, end_date, clickDate, hoverDate}) => {
  const cal_days = days.map((day, index) => {
    const cx = classNames({
      outside: is_outside(day, current_month),
      start: day.isSame(start_date, 'day'),
      end: day.isSame(end_date, 'day'),
      selected: day.isBetween(start_date, end_date),
      today: day.isSame(moment(), 'day')
    });
    return (
      <li key={index} className={cx} aria-label={day.format('MMMM Do YYYY')}>
        <a href="#" tabIndex="0" onClick={clickDate.bind(this, day)}>{day.format('D')}</a>
      </li>
    );
  });
  return (
    <ul>
      {cal_days}
    </ul>
  );
}
