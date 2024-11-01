<?php


include_once( ABSPATH . 'wp-admin/includes/plugin.php' );



define('WSDAI_JSON_API_AUTH_HOME', dirname(__FILE__));



/* if (!is_plugin_active(WSDAI_JSON_API_AUTH_HOME.'/json-api/json-api.php')) {



    add_action('admin_notices', 'pim_auth_draw_notice_json_api');



    return;



}  */



add_filter('json_api_controllers', 'WSDAI_pimAuthJsonApiController');



add_filter('WSDAI_json_api_auth_controller_path', 'WSDAI_setAuthControllerPath');



add_action('init', 'WSDAI_json_api_auth_checkAuthCookie', 100);



load_plugin_textdomain('json-api-auth', false, basename(dirname(__FILE__)) . '/languages');



 /* function pim_auth_draw_notice_json_api() {



    echo '<div id="message" class="error fade"><p style="line-height: 150%">';



    _e('<strong>Android and iOS App plugin for WooCommerce by WSD Auth</strong></a> requires the Android and iOS App plugin for WooCommerce by WSD plugin to be activated. Please <a href="https://wordpress.org/plugins/json-api/">install / activate Android and iOS App plugin for WooCommerce by WSD</a> first.', 'json-api-user');
	_e(plugin_dir_path(__FILE__));



    echo '</p></div>';



}  */





function WSDAI_pimAuthJsonApiController($aControllers) {



    $aControllers[] = 'Auth';

    return $aControllers;



}





function WSDAI_setAuthControllerPath($sDefaultPath) {



    return dirname(__FILE__) . '/controllers/Auth.php';



}



function WSDAI_json_api_auth_checkAuthCookie($sDefaultPath) {

    global $json_api;



    if ($json_api->query->cookie) {

      $user_id = wp_validate_auth_cookie($json_api->query->cookie, 'logged_in');

      if ($user_id) {

        $user = get_userdata($user_id);



        wp_set_current_user($user->ID, $user->user_login);

      }

    }

}