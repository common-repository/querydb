<?php
/*
Plugin Name: QueryDB
Plugin URI: http://www.pmprog.co.uk/?page_id=51
Description: Returns an SQL queries results in a table
Version: 1.0
Author: Polymath Programming
Author URI: http://www.pmprog.co.uk
License: GPL2
*/

define(querydb_TITLESTRING, 'Top Downloads');
define(querydb_QUERYSTRING, 'SELECT filename, COUNT(filename) FROM downloads GROUP BY filename ORDER BY 2 DESC LIMIT 0,5');


function querydb_widget_display($args)
{
  require_once(ABSPATH . WPINC . '/wp-db.php');		

  $options = get_option('querydb_widget');

  $myrows = $GLOBALS['wpdb']->get_results( $options['querydb_querystring'] );

  extract($args);
  echo $before_widget;
  echo $before_title . $options['querydb_title'] . $after_title;
  echo "<table cellspacing=1 width='100%'>";

  foreach( $myrows as $myrow )
  {
    echo "<tr>";
    foreach( $myrow as $mycol )
    {
      echo "<td>" . $mycol . "</td>";
    }
    echo "</tr>";
  }

  echo "</table>" . $after_widget;
}

function querydb_widget_Admin()
{
  $options = $newoptions = get_option('querydb_widget');
  if( $options == false )
  {
    $newoptions['querydb_querystring'] = querydb_QUERYSTRING;
    $newoptions['querydb_title'] = querydb_TITLESTRING;
  }
  if( $_POST["querydb_querystring"] )
  {
    $newoptions['querydb_querystring'] = $_POST["querydb_querystring"];
    $newoptions['querydb_title'] = $_POST["querydb_title"];
  }
  if( $options != $newoptions )
  {
    $options = $newoptions;
    update_option('querydb_widget', $options);
  }
  $dummyVar = wp_specialchars($options['querydb_querystring']);

  ?><form method="post" action="">
  <p><label for="querydb_title"><?php _e('Title:'); ?><br>
  <input id="querydb_title" name="querydb_title" type="text"
    style="width: 200px;" value="<?php echo wp_specialchars($options['querydb_title']) ?>">
  </label></p>

  <p><label for="querydb_querystring"><?php _e('Query:'); ?><br>
  <input id="querydb_querystring" name="querydb_querystring" type="text"
    style="width: 200px;" value="<?php echo wp_specialchars($options['querydb_querystring']) ?>">
  </label></p>

	<br clear='all'></p>
	<input type="hidden" id="querydb_widget-submit" name="querydb_widget-submit" value="1" />	

  </form><?php 
}

function querydb_menu()
{
  add_options_page('QueryDB', 'QueryDB', 8, __FILE__, 'querydb_options');
}

function querydb_options()
{
  ?>
  <div class="wrap">
  <h2>Query DB</h2>
  <p>Blar blar blar</p>
  </div>
  <?php
}


function querydb_widget_Init()
{
  register_sidebar_widget(__('QueryDB'), 'querydb_widget_display');
  register_widget_control(__('QueryDB'), 'querydb_widget_Admin', 200, 150);
}

add_action('admin_menu', 'querydb_menu');
add_action("plugins_loaded", "querydb_widget_Init");

?>
