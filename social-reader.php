<?php

/**
 * Plugin Name: Social Reader 
 * Description: Add Social Reader web services and make a WordPress powered site socially readable.
 * Author: Praveen 
 * Version: 1.0 
 * Author URI: http://apnerve.com
 */
run_actions();

/**
 * Adds the query variable 'api'
 */
function social_reader_queryvars($qvars) {
  $qvars[] = 'api';
  $qvars[] = 'socialreader';
  return $qvars;
}

/**
 * loads the custom template with articleId added to every feed item
 */
function social_reader() {
  global $wp_query;
  if ($wp_query->query_vars['socialreader'] == 1) {
    $rss_template = dirname(__FILE__) . '/social-reader-feed-view.php';
    load_template($rss_template);
  }
  else
    do_feed_rss2(false);
}

/**
 * Lists the categories in JSON format along with the feedlinks for each categories
 */
function social_reader_webservice() {
  global $wp_query;
  if ($wp_query->query_vars['api'] == 1) {
    $rss_template = dirname(__FILE__) . '/social-reader-api-view.php';
    load_template($rss_template);
    die(); // TODO: Check for a better method
  }
}

/**
 * Add hooks
 */
function run_actions() {
  add_filter('query_vars', 'social_reader_queryvars');
  remove_all_actions('do_feed_rss2');
  add_action('do_feed_rss2', 'social_reader');
  add_action('get_header', 'social_reader_webservice');
}

/** Helper function used in feeds for getting published times */
function time_since($offset=0) {

// Parameters
  $t_second = " second";
  $t_seconds = " seconds";
  $t_minute = " minute";
  $t_minutes = " minutes";
  $t_hour = " hour";
  $t_hours = " hours";
  $t_day = " day";
  $t_days = " days";
  $t_month = " month";
  $t_months = " months";
  $t_year = " year";
  $t_years = " years";
  $t_separator = ", ";
  $t_ending = " ago.";

  $time = time() + ($offset * 3600);

  $diff = $time - get_the_time('U');

  if ($diff < 60) {

    if ($diff == 0 or $diff > 1) {
      echo $diff . $t_seconds . $t_ending;
    }
    else {
      echo $diff . $t_second . $t_ending;
    }
  }
  else if ($diff < 3600) {

    $minutes = $diff / 60;
    $minutes = explode(".", $minutes);
    $minutes = $minutes[0];
    $seconds = $diff % 60;

    if ($minutes == 1) {

      if ($seconds == 1) {
        echo $minutes . $t_minute . $t_separator . $seconds . $t_second . $t_ending;
      }
      else if ($seconds > 1 or $seconds == 0) {
        echo $minutes . $t_minute . $t_separator . $seconds . $t_seconds . $t_ending;
      }
    }
    else if ($minutes > 1) {

      if ($seconds == 1) {
        echo $minutes . $t_minutes . $t_separator . $seconds . $t_second . $t_ending;
      }
      else if ($seconds > 1 or $seconds == 0) {
        echo $minutes . $t_minutes . $t_separator . $seconds . $t_seconds . $t_ending;
      }
    }
  }
  else if ($diff < 86400) {

    $hours = $diff / 3600;
    $hours = explode(".", $hours);
    $hours = $hours[0];

    $minutes = ($diff % 3600) / 60;
    $minutes = explode(".", $minutes);
    $minutes = $minutes[0];

    if ($hours == 1) {

      if ($minutes == 1) {
        echo $hours . $t_hour . $t_separator . $minutes . $t_minute . $t_ending;
      }
      else if ($minutes > 1 or $minutes == 0) {
        echo $hours . $t_hour . $t_separator . $minutes . $t_minutes . $t_ending;
      }
    }
    else if ($hours > 1) {

      if ($minutes == 1) {
        echo $hours . $t_hours . $t_separator . $minutes . $t_minute . $t_ending;
      }
      else if ($minutes > 1 or $minutes == 0) {
        echo $hours . $t_hours . $t_separator . $minutes . $t_minutes . $t_ending;
      }
    }
  }
  else if ($diff < 604800) {

    $days = $diff / 86400;
    $days = explode(".", $days);
    $days = $days[0];

    $hours = ($diff % 86400) / 3600;
    $hours = explode(".", $hours);
    $hours = $hours[0];

    if ($days == 1) {

      if ($hours == 1) {
        echo $days . $t_day . $t_separator . $hours . $t_hour . $t_ending;
      }
      else if ($hours > 1 or $hours == 0) {
        echo $days . $t_day . $t_separator . $hours . $t_hours . $t_ending;
      }
    }
    else if ($days > 1) {

      if ($hours == 1) {
        echo $days . $t_days . $t_separator . $hours . $t_hour . $t_ending;
      }
      else if ($hours > 1 or $hours == 0) {
        echo $days . $t_days . $t_separator . $hours . $t_hours . $t_ending;
      }
    }
  }
  else if ($diff < 2592000) {

    $days = $diff / 86400;
    $days = explode(".", $days);
    $days = $days[0];

    if ($hours == 1) {
      echo "$days day ago.";
    }
    else if ($days > 1) {
      echo "$days days ago.";
    }
  }
  else if ($diff < 31536000) {

    $months = $diff / 2592000;
    $months = explode(".", $months);
    $months = $months[0];

    $days = ($diff % 2592000) / 86400;
    $days = explode(".", $days);
    $days = $days[0];

    if ($months == 1) {

      echo $months . " month" . $t_ending;
    }
    else if ($months > 1) {

      echo $months . " months" . $t_ending;
    }
  }
  else if ($diff >= 31536000) {

    $years = $diff / 31536000;
    $years = explode(".", $years);
    $years = $years[0];

    $months = ($diff % 31536000) / 2592000;
    $months = explode(".", $months);
    $months = $months[0];

    if ($years == 1) {

      if ($months == 1) {
        echo $years . $t_year . $t_separator . $months . $t_month . $t_ending;
      }
      else if ($months > 1 or $months == 0) {
        echo $years . $t_year . $t_separator . $months . $t_months . $t_ending;
      }
    }
    else if ($years > 1) {

      if ($months == 1) {
        echo $years . $t_years . $t_separator . $months . $t_month . $t_ending;
      }
      else if ($months > 1 or $months == 0) {
        echo $years . $t_years . $t_separator . $months . $t_months . $t_ending;
      }
    }
  }
}
