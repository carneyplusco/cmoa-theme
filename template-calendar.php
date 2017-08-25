<?php
/**
 * Template Name: Calendar
 */

  use Roots\Sage\Assets;

  $timezone = new DateTimeZone('America/New_York');
  $now = new DateTime("now", $timezone);
  $start = get_query_var('start', $now->format('Y-m-d'));
  $end = get_query_var('end', $now->modify('+30 days')->format('Y-m-d'));

  $start_date = DateTime::createFromFormat('Y-m-d', $start, $timezone)->setTime(0, 0);
  $end_date = DateTime::createFromFormat('Y-m-d', $end, $timezone)->setTime(23, 59);

  $exhibitions = \CMOA\API\get_exhibitions_in_range($start_date->format('Ymd'), $end_date->format('Ymd'));

  $event_category = get_query_var('event_category');
  $event_tag = get_query_var('event_tag');

  if($event_category && !is_numeric($event_category)) {
    $category_term = get_term_by('slug', $event_category, 'events_categories');
    $event_category = $category_term->term_id;
  }

  if($event_tag && !is_numeric($event_tag)) {
    $tag_term = get_term_by('slug', $event_tag, 'events_tags');
    $event_tag = $tag_term->term_id;
  }

  $filters['cat_ids'][] = $event_category;
  $filters['tag_ids'][] = $event_tag;

  $cal = new CMOA_Calendar();
  $events = $cal->unique_events($cal->get_event_between_dates($start_date->format('U'), $end_date->format('U'), $filters));
?>

<section class="hero">
</section>

<section class="section-primary">
  <div class="container">
    <h1>Calendar of Events</h1>
    <form action="/calendar" method="get" class="calendar-controls" id="calendar-form">
      <div class="calendar-controls__wrapper">
        <div id="react-datepicker">
          <noscript>
            <input type="date" name="start" />
            <input type="date" name="end" />
          </noscript>
        </div>
      </div>
      <div class="calendar-controls__wrapper">
        <div id="react-event-filter" data-category_id="<?= $event_category ?>">
          <noscript>
            <select name="event_category">
              <option value="0">All Event Types</option>
              <?php foreach ($cal->calendar_categories() as $category): ?>
                <option value="<?= $category->term_id ?>"><?= $category->name ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Update Events</button>
          </noscript>
        </div>
      </div>
    </form>
  </div>

  <div class="container">
    <?php Assets\get_template_part('templates/content', 'calendar-of-events', ['exhibitions' => $exhibitions, 'events' => $events, 'start' => $start_date->format('m-d-Y'), 'end' => $end_date->format('m-d-Y')]); ?>
  </div>
</section>
