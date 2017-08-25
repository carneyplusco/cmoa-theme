require('es6-promise').polyfill();
import React, { Component } from 'react';
import fetch from 'isomorphic-fetch';
import classNames from 'classnames';
import store from 'store2';

const default_category = {
  term_id: 0
};

export default class EventFilter extends Component {
  constructor(props) {
    super(props);
    this.state = {
      categories: [
        {
          term_id: 0,
          name: 'All Event Types'
        }
      ],
      selected_category: default_category,
      list_open: false,
      sessionStorage: store.namespace('cmoa_calendar')
    }
  }

  componentDidMount() {
    const api_url = `/wp-json/events/v1/categories`;

    if(store.session.has('cmoa_calendar.categories')) {
      const data = store.session('cmoa_calendar.categories');
      this.setCategories(data);
    }

    else {
      fetch(api_url)
        .then(resp => resp.json())
        .then(data => {
          this.state.sessionStorage.session('categories', data);
          this.setCategories(data);
        });
    }
  }

  setCategories(data) {
    const event_category = parseInt(this.props.event_category) || 0;
    const categories = this.state.categories.concat(data);
    const selected_category = categories.find(category => category.term_id === event_category);
    this.setState({categories, selected_category: (selected_category || default_category)});
  }

  componentDidUpdate(prevProps, prevState) {
    const event_category = parseInt(this.props.event_category) || 0;
    const { categories, selected_category } = this.state;
    const category_ids = categories.map(category => category.term_id);
    if(selected_category.term_id !== event_category && category_ids.includes(event_category)) {
      document.getElementById('calendar-form').submit();
    }
  }

  toggleFilter = (e) => {
    if(!e.charCode || e.charCode === 13) {
      this.setState({list_open: !this.state.list_open});
    }
  };

  selectCategory = (category) => {
    this.setState({selected_category: category, list_open: false});
  };

  render() {
    const { categories, selected_category, list_open } = this.state;
    const category_list = categories.map(category => {
      const cx = classNames({
        active: category.term_id === selected_category.term_id
      })
      return (
        <li key={category.term_id} className={cx}>
          <a href="#" tabIndex="0" onClick={this.selectCategory.bind(this, category)}>{category.name}</a>
        </li>
      );
    });
    return (
      <div className="filter-wrapper">
        <div className={classNames('calendar-control', { open: list_open })} tabIndex="0" onClick={this.toggleFilter} onKeyPress={this.toggleFilter}>
          <strong>Event Type:</strong>
          <span className="start-date">{selected_category.name}</span>
        </div>
        <ul className={classNames('filter-list', { open: list_open })}>
          {category_list}
        </ul>
        <input type="hidden" name="event_category" value={selected_category.term_id} />
      </div>
    );
  }
}
