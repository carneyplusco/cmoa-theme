require('es6-promise').polyfill();
import React, { Component } from 'react';
import fetch from 'isomorphic-fetch';
import classNames from 'classnames';
import store from 'store2';

const default_item = {
  term_id: 0
};

export default class CalendarFilter extends Component {
  constructor(props) {
    super(props);
    this.state = {
      items: [
        {
          term_id: 0,
          name: this.props.default_label
        }
      ],
      selected_item: default_item,
      list_open: false,
      sessionStorage: store.namespace('cmoa_calendar')
    }
  }

  componentDidMount() {
    const api_url = `/wp-json/events/v1/${this.props.term}`;

    if(store.session.has(`cmoa_calendar.${this.props.term}`)) {
      const data = store.session(`cmoa_calendar.${this.props.term}`);
      this.setItems(data);
    }

    else {
      fetch(api_url)
        .then(resp => resp.json())
        .then(data => {
          this.state.sessionStorage.session(this.props.term, data);
          this.setItems(data);
        });
    }
  }

  setItems(data) {
    const event_item = parseInt(this.props.event_item) || 0;
    const items = this.state.items.concat(data);
    const selected_item = items.find(item => item.term_id === event_item);
    this.setState({items, selected_item: (selected_item || default_item)});
  }

  componentDidUpdate(prevProps, prevState) {
    const event_item = parseInt(this.props.event_item) || 0;
    const { items, selected_item } = this.state;
    const item_ids = items.map(item => item.term_id);
    if(selected_item.term_id !== event_item && item_ids.includes(event_item)) {
      document.getElementById('calendar-form').submit();
    }
  }

  toggleFilter = (e) => {
    if(!e.charCode || e.charCode === 13) {
      this.setState({list_open: !this.state.list_open});
    }
  };

  selectItem = (item) => {
    this.setState({selected_item: item, list_open: false});
  };

  render() {
    const { items, selected_item, list_open } = this.state;
    const { control_label, form_item } = this.props;
    const item_list = items.map(item => {
      const cx = classNames({
        active: item.term_id === selected_item.term_id
      })
      return (
        <li key={item.term_id} className={cx}>
          <a href="#" tabIndex="0" onClick={this.selectItem.bind(this, item)}>{item.name}</a>
        </li>
      );
    });
    return (
      <div className="filter-wrapper">
        <div className={classNames('calendar-control', { open: list_open })} tabIndex="0" onClick={this.toggleFilter} onKeyPress={this.toggleFilter}>
          <strong>{control_label}:</strong>
          <span className="start-date">{selected_item.name}</span>
        </div>
        <ul className={classNames('filter-list', { open: list_open })}>
          {item_list}
        </ul>
        <input type="hidden" name={form_item} value={selected_item.term_id} />
      </div>
    );
  }
}
