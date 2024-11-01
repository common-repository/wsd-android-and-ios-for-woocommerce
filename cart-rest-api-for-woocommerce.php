<?php

if ( ! class_exists( 'WSDAI_WC_Cart_Endpoint_REST_API' ) ) {
	class WSDAI_WC_Cart_Endpoint_REST_API {

		/**
		 * @var WSDAI_WC_Cart_Endpoint_REST_API - the single instance of the class.
		 *
		 * @access protected
		 * @static
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Plugin Version
		 *
		 * @access public
		 * @static
		 * @since  1.0.0
		 */
		public static $version = '1.0.4';

		/**
		 * Required WooCommerce Version
		 *
		 * @access public
		 * @static
		 * @since  1.0.0
		 */
		public static $required_woo = '3.0.0';

		/**
		 * Main WSDAI_WC_Cart_Endpoint_REST_API Instance.
		 *
		 * Ensures only one instance of WSDAI_WC_Cart_Endpoint_REST_API is loaded or can be loaded.
		 *
		 * @access public
		 * @static
		 * @since  1.0.0
		 * @see    WSDAI_WC_Cart_Endpoint_REST_API()
		 * @return WSDAI_WC_Cart_Endpoint_REST_API - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cloning this object is forbidden.', 'cart-rest-api-for-woocommerce' ), self::$version );
		} // END __clone()

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'cart-rest-api-for-woocommerce' ), self::$version );
		} // END __wakeup()

		/**
		 * Load the plugin.
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {
			// Check WooCommerce dependency.
			add_action( 'plugins_loaded', array( $this, 'check_woocommerce_dependency' ) );

			// Load translation files.
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			// Add link to documentation and support.
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

			// Include required files
			add_action( 'woocommerce_loaded', array( $this, 'includes' ) );
		}

		/*-----------------------------------------------------------------------------------*/
		/*  Helper Functions                                                                 */
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Get the Plugin Path.
		 *
		 * @access public
		 * @static
		 * @since  1.0.0
		 * @return string
		 */
		public static function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		} // END plugin_path()

		/**
		 * Check WooCommerce dependency before activation.
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function check_woocommerce_dependency() {
			// Check we're running the required version of WooCommerce.
			if ( ! defined( 'WC_VERSION' ) || version_compare( WC_VERSION, self::$required_woo, '<' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notice' ) );
				return false;
			}
		} // END check_woocommerce_dependency()

		/**
		 * Display a warning message if minimum version of WooCommerce check fails.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function admin_notice() {
			echo '<div class="error"><p>' . sprintf( __( '%1$s requires at least %2$s v%3$s or higher.', 'cart-rest-api-for-woocommerce' ), 'Cart REST API for WooCommerce', 'WooCommerce', self::$required_woo ) . '</p></div>';
		} // END admin_notice()

		/*-----------------------------------------------------------------------------------*/
		/*  Localization                                                                     */
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Make the plugin translation ready.
		 *
		 * Translations should be added in the WordPress language directory:
		 *      - WP_LANG_DIR/plugins/cart-rest-api-for-woocommerce-LOCALE.mo
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'cart-rest-api-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		} // END load_plugin_textdomain()

		/*-----------------------------------------------------------------------------------*/
		/*  Load Files                                                                       */
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Includes Cart REST API for WooCommerce Admin.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function includes() {
			include_once( $this->plugin_path() . '/includes/class-wc-cart-rest-api-init.php' );
		} // END includes()

		/**
		 * Show row meta on the plugin screen.
		 *
		 * @access public
		 * @static
		 * @param  mixed $links
		 * @param  mixed $file
		 * @return array
		 */
		public static function plugin_row_meta( $links, $file ) {
			if ( $file == plugin_basename( __FILE__ ) ) {
				$row_meta = array(
					'docs'    => '<a href="https://seb86.github.io/WooCommerce-Cart-REST-API-Docs/" target="_blank">' . __( 'Documentation', 'cart-rest-api-for-woocommerce' ) . '</a>',
					'support' => '<a href="https://seb86.github.io/WooCommerce-Cart-REST-API-Docs/#support" target="_blank">' . __( 'Support', 'cart-rest-api-for-woocommerce' ) . '</a>',
				);

				$links = array_merge( $links, $row_meta );
			}

			return $links;
		} // END plugin_row_meta()

	} // END class

} // END if class exists

return WSDAI_WC_Cart_Endpoint_REST_API::instance();
