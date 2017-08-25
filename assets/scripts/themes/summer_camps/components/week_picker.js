require('es6-promise').polyfill();
import React, { Component } from 'react';
import moment from 'moment';
import classNames from 'classnames';

export default class WeekPicker extends Component {
  constructor(props) {
    super(props);
    this.state = {
      weeks: [
        {
          id: -1,
          start: null
        }
      ],
      selected_week: parseInt(this.props.week),
      list_open: false
    }
  }

  componentDidMount() {
    const start_date = moment(this.props.startDate).startOf('isoWeek');
    const end_date = moment(this.props.startDate).startOf('isoWeek').add(4, 'days');
    let weeks = this.state.weeks;
    for(let i = 0; i < this.props.weekCount; i++) {
      const start = start_date.clone().add(i, 'weeks');
      weeks = [...weeks, {id: i, start}];
    }
    this.setState({weeks});
  }

  componentDidUpdate(prevProps, prevState) {
    const week_item = parseInt(this.props.week) || 0;
    const { weeks, selected_week } = this.state;
    const week_ids = weeks.map(week => week.id);
    if(selected_week !== week_item && week_ids.includes(week_item)) {
      document.getElementById('calendar-form').submit();
    }
  }

  toggleFilter = (e) => {
    if(!e.charCode || e.charCode === 13) {
      this.setState({list_open: !this.state.list_open});
    }
  }

  selectWeek = (item) => {
    this.setState({selected_week: item.id, list_open: false});
  };

  formatWeek(week) {
    if(!week.start) {
      return 'All Weeks';
    }
    const end = week.start.clone().add(4, 'days');
    let str = `${moment(week.start).format('MMMM D')}–`;
    if(week.start.format('M') != end.format('M')) {
      str += end.format('MMMM ');
    }
    str += end.format('D');
    return str;
  }

  render() {
    const { weeks, selected_week, list_open } = this.state;
    const week_list = weeks.map(week => {
      const cx = classNames({
        active: week.id === selected_week
      })
      return (
        <li key={week.id} className={cx}>
          <a href="#" tabIndex="0" onClick={this.selectWeek.bind(this, week)}>{this.formatWeek(week)}</a>
        </li>
      );
    });
    const week_obj = weeks.find(week => week.id == selected_week);
    const week_name = week_obj ? this.formatWeek(week_obj) : '';
    return (
      <div className="filter-wrapper">
        <div className={classNames('calendar-control', { open: list_open })} tabIndex="0" onClick={this.toggleFilter} onKeyPress={this.toggleFilter}>
          <strong>Sort by:</strong>
          <span className="start-date">{week_name}</span>
        </div>
        <ul className={classNames('filter-list', { open: list_open })}>
          {week_list}
        </ul>
        <input type="hidden" name="week" value={selected_week} />
      </div>
    );
  }
}
