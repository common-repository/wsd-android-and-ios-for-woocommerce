<?php 
/*
Plugin Name:  WSD Android and iOS App plugin for WooCommerce
Plugin URI:   https://www.websystems-design.com/woocommerce-mobile-app/
Description:  This plugin is developed to connect your WooCommerce store to WSD Android and iOS App plugin for WooCommerce
Version:      v.01
Author:       Websystems
Author URI:   https://www.websystems-design.com/woocommerce-mobile-app/
License:      GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wporg
Domain Path:  /languages
*/

//ob_start();
include dirname(__FILE__) . '/json-api-user.php';
include dirname(__FILE__) . '/json-api-auth.php';
//include dirname(__FILE__) . '/cart-rest-api-for-woocommerce';


$dir = WSDAI_json_api_dir();
@include_once "$dir/singletons/api.php";
@include_once "$dir/singletons/query.php";
@include_once "$dir/singletons/introspector.php";
@include_once "$dir/singletons/response.php";
@include_once "$dir/models/post.php";
@include_once "$dir/models/comment.php";
@include_once "$dir/models/category.php";
@include_once "$dir/models/tag.php";
@include_once "$dir/models/author.php";
@include_once "$dir/models/attachment.php";

function WSDAI_json_api_init() {
  global $json_api;
  if (phpversion() < 5) {
    add_action('admin_notices', 'WSDAI_json_api_php_version_warning');
    return;
  }
  if (!class_exists('WSDAI_JSON_API')) {
    add_action('admin_notices', 'WSDAI_json_api_class_warning');
    return;
  }
  add_filter('rewrite_rules_array', 'WSDAI_json_api_rewrites');
  $json_api = new WSDAI_JSON_API();
}

function WSDAI_json_api_php_version_warning() {
  echo "<div id=\"json-api-warning\" class=\"updated fade\"><p>Sorry, Android and iOS App plugin for WooCommerce by WSD requires PHP version 5.0 or greater.</p></div>";
}

function WSDAI_json_api_class_warning() {
  echo "<div id=\"json-api-warning\" class=\"updated fade\"><p>Oops, JSON_API class not found. If you've defined a WSDAI_json_api_dir constant, double check that the path is correct.</p></div>";
}

function WSDAI_json_api_activation() {
  // Add the rewrite rule on activation
  global $wp_rewrite;
  add_filter('rewrite_rules_array', 'WSDAI_json_api_rewrites');
  $wp_rewrite->flush_rules();
}

function WSDAI_json_api_deactivation() {
  // Remove the rewrite rule on deactivation
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}

function WSDAI_json_api_rewrites($wp_rules) {
  $base = get_option('json_api_base', 'api');
  if (empty($base)) {
    return $wp_rules;
  }
  $json_api_rules = array(
    "$base\$" => 'index.php?json=info',
    "$base/(.+)\$" => 'index.php?json=$matches[1]'
  );
  return array_merge($json_api_rules, $wp_rules);
}

function WSDAI_json_api_dir() {
  if (defined('WSDAI_json_api_dir') && file_exists(WSDAI_json_api_dir)) {
    return WSDAI_json_api_dir;
  } else {
    return dirname(__FILE__);
  }
}

// Add initialization and activation hooks
add_action('init', 'WSDAI_json_api_init');
register_activation_hook("$dir/json-api.php", 'WSDAI_json_api_activation');
register_deactivation_hook("$dir/json-api.php", 'WSDAI_json_api_deactivation');



?>