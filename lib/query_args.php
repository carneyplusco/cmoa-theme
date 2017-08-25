<?php
namespace CMOA\QueryArgs;

  /*
   *
   * Register custom query arguments
   *
  */

  function add_custom_query_var($vars){
    $vars[] = "start";
    $vars[] = "end";
    $vars[] = "event_category";
    $vars[] = "event_tag";
    return $vars;
  }
  add_filter('query_vars', __NAMESPACE__.'\\add_custom_query_var');

?>
