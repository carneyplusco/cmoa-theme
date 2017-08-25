require('es6-promise').polyfill();
import React, { Component } from 'react';
import moment from 'moment';
import R from 'ramda';
import classNames from 'classnames';
import fetch from 'isomorphic-fetch';
import store from 'store2';

const mapIndexed = R.addIndex(R.map);

const same_day = R.curry((d1, d2) => moment(d1.date).isSame(moment(d2.date), 'day'));
const is_today = same_day({date: moment()});
const today = R.find(is_today);
const is_outside = (day, month) => {
  const start_of_month = month.clone().startOf('month');
  const end_of_month = month.clone().endOf('month');
  const the_day = moment(day.date);
  return the_day.isBefore(start_of_month) || the_day.isAfter(end_of_month);
}

export default class CalendarWidget extends Component {
  constructor(props) {
    super(props);
    this.state = {
      current_month: moment(),
      days: [],
      selected_day: null,
      loading_events: false,
      sessionStorage: store.namespace('cmoa_calendar')
    }
  }

  componentDidMount() {
    this.getEvents();
  }

  shouldComponentUpdate(nextProps, nextState) {
    // only re-render when new days come in, or when a day is selected
    // this prevents all the days from flashing when the current_month changes
    return (
      this.state.loading_events != nextState.loading_events ||
      this.state.days != nextState.days ||
      this.state.selected_day != nextState.selected_day
    )
  }

  getEvents = () => {
    const { current_month, sessionStorage } = this.state;
    const year = current_month.format('YYYY');
    const month = current_month.format('MM');
    const api_url = `/wp-json/events/v1/year/${year}/month/${month}`;
    if(store.session.has(`cmoa_calendar.${year}-${month}`)) {
      const data = store.session(`cmoa_calendar.${year}-${month}`);
      this.setState({loading_events: false, days: data, selected_day: today(data)});
    }
    else {
      fetch(api_url)
        .then(resp => resp.json())
        .then(data => {
          sessionStorage.session(`${year}-${month}`, data);
          this.setState({loading_events: false, days: data, selected_day: today(data)});
        });
    }
  };

  getPrevEvents = (e) => {
    e.preventDefault();
    const prev_month = this.state.current_month.subtract(1, 'month');
    this.setState({loading_events: true, current_month: prev_month});
    this.getEvents();
  };

  getNextEvents = (e) => {
    e.preventDefault();
    const next_month = this.state.current_month.add(1, 'month');
    this.setState({loading_events: true, current_month: next_month});
    this.getEvents();
  };

  showEventsForDate = (day, e) => {
    e.preventDefault();
    this.setState({selected_day: day});
  };

  render() {
    const { current_month, selected_day, loading_events } = this.state;
    const cal_row = (day_arr, index) => <CalendarRow key={index} current_month={current_month} days={day_arr} selected_day={selected_day} clickDate={this.showEventsForDate} />;
    const make_rows = R.compose(mapIndexed(cal_row), R.splitEvery(7));
    const events_list = !!selected_day && selected_day.events.length ? selected_day.events.map((event, index) => <CalendarEvent key={index} event={event} />) : <li>No events scheduled for selected day.</li>;
    const current_month_label = `Selected month ${current_month.format('MMMM YYYY')}`;
    return (
      <div className={classNames('calendar-wrapper', { loading: loading_events })} tabIndex="0">
        <div className="cal-split">
          <hr className="left inverted" />
          <nav className="calendar-nav">
            <ul>
              <li className="prev-month"><a aria-label="Previous month" tabIndex="0" onClick={this.getPrevEvents}>‹</a></li>
              <li className="month-name"><p aria-label={current_month_label}>{current_month.format('MMMM YYYY')}</p></li>
              <li className="next-month"><a aria-label="Next month" tabIndex="0" onClick={this.getNextEvents}>›</a></li>
            </ul>
          </nav>
          <div className="day-list">
            {make_rows(this.state.days)}
          </div>
        </div>
        <div className="cal-split">
          <hr className="left inverted" />
          <ul className="event-list">
            {events_list}
          </ul>
        </div>
      </div>
    );
  }
}

const CalendarRow = ({current_month, days, selected_day, clickDate}) => {
  const cal_days = days.map((day, index) => {
    const cx = classNames({
      outside: is_outside(day, current_month),
      'has-events': day.events.length,
      today: is_today(day),
      active: !!selected_day && same_day(day, selected_day)
    });
    return (
      <li key={index} className={cx}>
        <a tabIndex="0" onClick={clickDate.bind(this, day)}>{moment(day.date).format('D')}</a>
      </li>
    );
  });
  return (
    <ul>
      {cal_days}
    </ul>
  );
}

const CalendarEvent = ({event}) => {
  const { name, url, start_date, all_day } = event;
  const event_time = all_day ? "All Day" : moment(start_date).format('h:mm A');
  return (
    <li>
      <span className="event-time">{event_time}</span>
      <span className="event-name accent-text"><a href={url} tabIndex="0" dangerouslySetInnerHTML={{__html: name}}></a></span>
    </li>
  );
}
