<?php

namespace Roots\Sage\Setup;
use Roots\Sage\Assets;

/**
 * Returns friendly open text
 */
function today_hours_shortcode() {
  if(!class_exists('CMOA_Static_Data')) return '';

  $static = new \CMOA_Static_Data();
  $hours = json_decode($static->get_static_data('hours'), true);
  $today = array_filter($hours['days'], function($hour) {
    return $hour['day'] == date('l');
  });
  if(count($today) > 0) {
    $hours_today = current($today);
    return isset($hours_today['opens']) ? "Today, we are open from ".$hours_today['opens']."–".$hours_today['closes']."." : "The museum is closed today.";
  }
}

/**
 * Returns app store badge svg with link to app
 */
function app_store_badge() {
  return '<a href="https://itunes.apple.com/us/app/carnegie-museum-of-art/id700992890?mt=8" title="Download our app on the App Store" aria-label="Download our app on the App Store">'.Assets\inline_svg('images/app-store-badge.svg').'</a>';
}

/**
 * Returns app store link to app
 */
function app_store_link() {
  return '<a href="https://itunes.apple.com/us/app/carnegie-museum-of-art/id700992890?mt=8" title="Download our app on the App Store">download on the App Store</a>';
}

/**
 * Returns email link
 */
function link_email($atts) {
  $a = shortcode_atts(array('subject' => ''), $atts);
  return '<a href="mailto:'.$atts['email'].'?Subject='.$a['subject'].'" title="Send email to '.$atts['email'].'">'.$atts['text'].'</a>';
}

/**
 * Returns external link
 */
function link_external($atts) {
  $a = shortcode_atts(
    array(
      'url' => '',
      'domain' => '',
      'link_text' => '#',
      'screen_reader_text' => '(external link)',
    ),
    $atts
  );
  return '<a class="link-external" title="Visit '.$a['domain'].'" href="'.$a['url'].'">'.$a['link_text'].'<span class="screen-reader-text"> '.$a['screen_reader_text'].'</span></a>';
}

/**
 * Returns span with aria-label for screen reader assistance
 */
function accessible_text($atts) {
  $a = shortcode_atts(array('display' => '', 'screen_reader' => ''), $atts);
  return '<span aria-label="'.$a['screen_reader'].'">'.$a['display'].'</span>';
}

/**
 * Returns download link
 * [link_download url="" link_text="" file_type="" screen_reader_text=""]
 */
 function link_download($atts) {
   $a = shortcode_atts(
     array(
       'url' => '',
       'file_type' => '',
       'link_text' => '',
       'screen_reader_text' => '(download)',
     ),
     $atts
   );
   return '<a class="link-download" href="'.$a['url'].'">'.$a['link_text'].' '.$a['file_type'].'<span class="screen-reader-text">'.$a['screen_reader_text'].'</span></a>';
 }

/**
 * Formats datetime into html time tag
 * Expects date as yyyy-mm-dd and time as h:m [am/pm] (time is optional)
 * e.g. [date_time date="2016-11-17" time="10:00 am"]
 */
function date_time($atts) {
  $timezone = new \DateTimeZone('America/New_York');
  $a = shortcode_atts(array('date' => '', 'time' => ''), $atts);

  if(!empty($a['date'])) {
    $date = \DateTime::createFromFormat('Y-m-d', $a['date'], $timezone);
  }
  else {
    $date = new \DateTime('now', $timezone);
  }

  if(!$date) return '';

  if(!empty($a['time'])) {
    $date->modify($a['time']);
  }
  else {
    $date->modify('midnight');
  }

  $date_str = '<strong>'.$date->format('M j').'</strong>, '.$date->format('Y');
  if(!empty($a['time'])) {
    $date_str .= ' <span class="times">'.$date->format('g:i a').'</span>';
  }
  return '<time datetime="'.$date->format('c').'">'.$date_str.'</time>';
}

/**
 * Formats date range into html time tag
 * Expects start_date and end_date as yyyy-mm-dd and start_time and end_time as h:m[am/pm] (times are optional)
 * e.g. [date_range start_date="2016-11-17" start_time="10:00am" end_date="2017-01-30" end_time="1:00pm"]
 */
function date_range($atts) {
  $timezone = new \DateTimeZone('America/New_York');
  $a = shortcode_atts(array('start_date' => '', 'end_date' => '', 'start_time' => '', 'end_time' => ''), $atts);

  if(!empty($a['start_date'])) {
    $start_date = \DateTime::createFromFormat('Y-m-d', $a['start_date'], $timezone);
  }
  else {
    $start_date = new \DateTime('now', $timezone);
  }

  if(!empty($a['end_date'])) {
    $end_date = \DateTime::createFromFormat('Y-m-d', $a['end_date'], $timezone);
  }
  else {
    $end_date = new \DateTime('now', $timezone);
  }

  if((!$start_date || !$end_date) || ($start_date > $end_date)) return '';

  if($start_date && !empty($a['start_time'])) {
    $start_date->modify($a['start_time']);
  }
  elseif($start_date) {
    $start_date->modify('midnight');
  }

  if($end_date && !empty($a['end_time'])) {
    $end_date->modify($a['end_time']);
  }
  elseif($end_date) {
    $end_date->modify('midnight');
  }

  $date_str = '<strong itemprop="startDate" content="'.$start_date->format('c').'">'.$start_date->format('M j').'</strong>';
  if($start_date->format('Y') != $end_date->format('Y')) {
    $date_str .= ', '.$start_date->format('Y');
  }
  if($start_date->format('Y-m-d') != $end_date->format('Y-m-d')) {
    $date_str .= '&ndash;<strong itemprop="endDate" content="'.$end_date->format('c').'">'.$end_date->format('M j').'</strong>';
  }
  $date_str .= ', '.$end_date->format('Y');
  if(!empty($a['start_time']) || !empty($a['end_time'])) {
    $date_str .= ' <span class="times">';
    $date_str .= $start_date->format('g:i a');
    $date_str .= '&ndash;'.$end_date->format('g:i a');
    $date_str .= '</span>';
  }
  return '<time>'.$date_str.'</time>';
}

/**
 * Returns contact data from static JSON file. Formats as email_link if email address
 */
function contact_data($atts, $content, $tag) {
  if(!class_exists('CMOA_Static_Data')) return '';

  $static = new \CMOA_Static_Data();
  $contact_data = json_decode($static->get_static_data('contacts'), true);
  if(isset($contact_data[$tag])) {
    if(strpos($tag, 'email_') !== false) {
      $email_tag = ['email' => $contact_data[$tag], 'text' => $contact_data[$tag]];
      return link_email($email_tag);
    }
    else {
      return $contact_data[$tag];
    }
  }
  else {
    return '';
  }
}

/**
 * Sets up shortcodes for contacts bases on static JSON file
 */
function contact_shortcodes() {
  if(!class_exists('CMOA_Static_Data')) return;

  $static = new \CMOA_Static_Data();
  $contact_data = json_decode($static->get_static_data('contacts'), true);
  foreach ($contact_data as $key => $value) {
    add_shortcode($key, __NAMESPACE__ . '\\contact_data');
  }
}
add_action('init', __NAMESPACE__ . '\\contact_shortcodes');



/**
 * Returns weather policy
 * [text_weather_policy]
 */
 function text_weather_policy( $atts ) {
   return '<section class="callout weather-policy"><h1 class="level-4">Inclement Weather Policy</h1><p style="font-size:90%; variant:italic;">Should hazardous conditions result in cancellation of classes, announcements will be made on television stations KDKA, WTAE and WPXI. Decisions are based on the needs of all students and instructors, some of whom drive considerable distances to Oakland. Makeup days may be scheduled for missed classes. During any inclement weather, please use your own discretion to attend for your own safety and that of your student.</p></section>';
 }

 /**
  * Returns refund policy
  * [text_multi_session_refund]
  */
  function text_multi_session_refund( $atts ) {
    return '<section class="callout class-refund-policy"><h1 class="level-4">Refund Policy</h1><p style="font-size:90%; variant:italic;">For multi-session classes, full refund less $10 handling fee for withdrawals made at least one week before program begins. Withdrawals made less than one week before the program starts, but before the second class session, are subject to a fee of $10 plus the pro-rated cost of a single class. No refunds after the second class has met.</p></section>';
  }

  /**
   * Returns refund policy
   * [text_single_session_refund]
   */
   function text_single_session_refund( $atts ) {
     return '<section class="callout class-refund-policy"><h1 class="level-4">Refund Policy</h1><p style="font-size:90%; variant:italic;">For single-session programs, full refund less $10 handling fee for withdrawals made at least one week before program date. No refunds with less than a week’s advance notice.</p></section>';
   }

/**
 * Returns blockquote
 * [blockquote quote="" author="" work_title="" work_attributes=""]
 */
function blockquote($atts) {
	$a = shortcode_atts(
		array(
			'quote' => '',
			'author' => '',
			'title' => '',
			'attributes' => '',
		),
		$atts
	);
  $quote_str = '';
  if(!empty($a['quote'])) {
    $quote_str .= '<blockquote><p>'.$a['quote'].'</p></blockquote>';
  }
  if(!empty($a['author'])) {
    $quote_str .= '<span class="blockquote-attr">'.$a['author'];
    if(!empty($a['title'])) {
      $quote_str .= ', <cite>'.$a['title'].'</cite>';
    }
    if(!empty($a['attributes'])) {
      $quote_str .= ' '.$a['attributes'];
    }
    $quote_str .= '</span>';
  }

	return '<div class="quote">'.$quote_str.'</div>';
}

/**
 * Returns inline quote
 * [quote quote="" source=""]
 */
function quote($atts) {
	$a = shortcode_atts(
		array(
			'quote' => '',
			'source' => '',
		),
		$atts
	);
	return '<q cite="'.$a['source'].'">'.$a['quote'].'</q>';
}

/**
 * Returns pull quote
 * [pull_quote quote=""]
 */
function pull_quote($atts) {
	$a = shortcode_atts(
		array(
			'quote' => '',
		),
		$atts
	);
	return '<aside class="quote"><q>'.$a['quote'].'</q></aside>';
}

/**
 * Returns Vimeo embed
 * [vimeo id=""]
 */
function vimeo($atts) {
	$a = shortcode_atts(
		array(
			'id' => '',
		),
		$atts
	);
	return '<div class="embed"><iframe src="https://player.vimeo.com/video/'.$a['id'].'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
}

/**
 * Returns Youtube embed
 * [youtube id=""]
 */
function youtube($atts) {
	$a = shortcode_atts(
		array(
      'id' => '',
    ),
    $atts
  );
  return '<div class="embed"><iframe src="http://www.youtube.com/embed/'.$a['id'].'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
}

/**
 * Register shortcodes
 */
function register_shortcodes() {
	add_shortcode('today_hours', __NAMESPACE__ . '\\today_hours_shortcode');
	add_shortcode('app_store_badge', __NAMESPACE__ . '\\app_store_badge');
  add_shortcode('app_store_link', __NAMESPACE__ . '\\app_store_link');
	add_shortcode('link_email', __NAMESPACE__ . '\\link_email');
	add_shortcode('link_external', __NAMESPACE__ . '\\link_external');
	add_shortcode('link_download', __NAMESPACE__ . '\\link_download');
	add_shortcode('text_weather_policy', __NAMESPACE__ . '\\text_weather_policy');
	add_shortcode('text_multi_session_refund', __NAMESPACE__ . '\\text_multi_session_refund');
	add_shortcode('text_single_session_refund', __NAMESPACE__ . '\\text_single_session_refund');
	add_shortcode('quote', __NAMESPACE__ . '\\quote');
	add_shortcode('blockquote', __NAMESPACE__ . '\\blockquote');
	add_shortcode('pull_quote', __NAMESPACE__ . '\\pull_quote');
	add_shortcode('vimeo', __NAMESPACE__ . '\\vimeo');
  add_shortcode('youtube', __NAMESPACE__ . '\\youtube');
	add_shortcode('accessible_text', __NAMESPACE__ . '\\accessible_text');
	add_shortcode('date_time', __NAMESPACE__ . '\\date_time');
	add_shortcode('date_range', __NAMESPACE__ . '\\date_range');
}
add_action('init', __NAMESPACE__ . '\\register_shortcodes');

?>
