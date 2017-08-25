<?php

class FeaturedItem {
  function __construct($ID, $type, $title, $lead, $locations) {
    $this->ID = $ID;
    $this->type = $type;
    $this->title = $title;
    $this->lead = $lead;
    $this->locations = $locations;
    $this->chip = new Chip;
  }

  public function chip_text() {
    return "<p>".$this->chip->text."</p>";
  }
}

class FeaturedExhibition extends FeaturedItem {
  public function chip_text() {
    if(!empty($this->chip->text)) {
      return "<p>".$this->chip->text."</p>";
    }
    elseif(!get_field('has_start_and_end_date', $this->ID) && get_field('lead_message', $this->ID)) {
      return "<p>".get_field('lead_message', $this->ID)."</p>";
    }
    else {
      return "<p><strong itemprop='startDate'>".$this->start_date->format('M j')."&ndash;</strong><strong itemprop='endDate'>".$this->end_date->format('M j')." </strong>".$this->end_date->format('Y')."</p>";
    }
  }
}

class FeaturedEvent extends FeaturedItem {
  public function chip_text() {
    if(!empty($this->chip->text)) {
      return "<p>".$this->chip->text."</p>";
    }

    $event = $this->get_event($this->ID);
    if($event->get('recurrence_rules')) {
      return "<p itemprop='startDate'><strong>Starts </strong><strong>".$this->start_date->format('M j')." </strong>".$this->start_date->format('Y')."</p>";
    }
    return "<p itemprop='startDate'><strong>".$this->start_date->format('M j')." </strong>".$this->start_date->format('Y')."</p>";
  }

  public function get_event($event_id) {
    global $ai1ec_registry;
    $event = $ai1ec_registry->get('model.event', $event_id);
    return $event;
  }

  public function recurrence($event) {
    $frequency_pattern = '/^FREQ=([^;]+);/';
    preg_match($frequency_pattern, $event->get('recurrence_rules'), $matches);
    return isset($matches[1]) && $matches[1];
  }
}

class Chip {
  function __construct($chip_text = '', $chip_color = 'charcoal') {
    $this->text = $chip_text;
    $this->color = $chip_color;
  }
}

class FeaturedItemFactory {
  public static function build($featured_item) {
    $item = $featured_item['item'];
    $chip_text = isset($featured_item['chip_text']) ? $featured_item['chip_text'] : '';
    $chip = new Chip($chip_text);
    switch ($item->post_type) {
      case 'exhibition':
        $location_terms = wp_get_post_terms($item->ID, 'locations');
        $department_terms = wp_get_post_terms($item->ID, 'departments');
        $feature = new FeaturedExhibition($item->ID, 'Exhibition', $item->post_title, $item->post_excerpt, FeaturedItemFactory::join_terms($location_terms));
        $feature->start_date = \DateTime::createFromFormat('Ymd', get_field('start_date', $item));
        $feature->end_date = \DateTime::createFromFormat('Ymd', get_field('end_date', $item));
        $chip->color = count($department_terms) > 0 ? get_field('chip_color', $department_terms[0]) : $chip->color;
        break;

      case 'ai1ec_event':
        global $ai1ec_registry;
        $ai1ec_event = $ai1ec_registry->get('model.event', $item->ID);
        $details = get_field('excerpt', $item->ID);
        $location_terms = wp_get_post_terms($item->ID, 'locations');
        $locations = count($location_terms) > 2 ? 'Various Locations' : FeaturedItemFactory::join_terms($location_terms);
        $category_terms = wp_get_post_terms($item->ID, 'events_categories');
        $feature = new FeaturedEvent($item->ID, FeaturedItemFactory::join_terms($category_terms), $item->post_title, $details, $locations);
        $feature->start_date = \DateTime::createFromFormat('Y-m-d\TH:i:sO', $ai1ec_event->get('start'));
        $feature->end_date = \DateTime::createFromFormat('Y-m-d\TH:i:sO', $ai1ec_event->get('end'));
        $chip->color = count($category_terms) ? get_field('chip_color', $category_terms[0]) : $chip->color;
        break;

      default:
        $feature = new \FeaturedItem;
        break;
    }

    $feature->chip = $chip;
    return $feature;
  }

  static function join_terms($terms) {
    $term_names = array_map(function($term) {
      return $term->name;
    }, $terms);
    return implode($term_names, ', ');
  }
}

?>
