<?php
/********************************************************************************************
 * Plugin Name: WP Ultimate Exporter
 *  Description: Backup tool to export all your WordPress data as CSV file. eCommerce data of WooCommerce, MarketPress, eCommerce, eShop, Custom Post and Custom field informations along with default WordPress modules.
 * Version: 1.4.4
 * Author: Smackcoders
 * Text Domain: wp-ultimate-exporter
 * Domain Path: /languages/
 */

namespace Smackcoders\SMEXP;

if ( ! defined( 'ABSPATH' ) )
exit; // Exit if accessed directly

require_once('Plugin.php');
require_once('SmackExporterInstall.php');
require_once('exportExtensions/ExportExtension.php');
require_once('exportExtensions/CustomerReviewsExport.php');
require_once('exportExtensions/ExportHandler.php');
require_once('exportExtensions/PostExport.php');
require_once('exportExtensions/WooComExport.php');


class ExpCSVHandler extends ExportExtension{

	protected static $instance = null,$install,$exp_instance;
	public $version = '1.4.4';

	public function __construct(){ 
		$this->plugin = Plugin::getInstance();
	}

	public static function getInstance() {
		if (ExpCSVHandler::$instance == null) {
			ExpCSVHandler::$instance = new ExpCSVHandler;	
			ExpCSVHandler::$install = ExpInstall::getInstance();
			ExpCSVHandler::$exp_instance = ExportExtension::getInstance();
			add_filter( 'plugin_row_meta' . plugin_basename( __FILE__ ),  array(ExpCSVHandler::$install, 'plugin_row_meta'), 10, 2 );
			add_action('plugin_action_links_' . plugin_basename( __FILE__ ), array(ExpCSVHandler::$install, 'plugin_row_meta'), 10, 3);


			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			self::init_hooks();

			return ExpCSVHandler::$instance;
		}
		return ExpCSVHandler::$instance;
	}

	public static function init_hooks() {
        
		add_action( 'admin_notices', self::admin_notice_exporter_free() );
	}

	public static function admin_notice_exporter_free() {
				global $pagenow;
				$active_plugins = get_option( "active_plugins" );
			    if ( $pagenow == 'plugins.php' && !in_array('wp-ultimate-csv-importer/index.php', $active_plugins) ) {
				    ?>
				    <div class="notice notice-warning is-dismissible" >
				        <p> WP Ultimate Exporter is an addon of <a href="https://wordpress.org/plugins/wp-ultimate-csv-importer" target="blank" style="cursor: pointer;text-decoration:none">WP Ultimate CSV Importer</a> plugin, kindly install it to continue using WP ultimate exporter. </p>
				        <p>
				    </div>
				    <?php 
			   }
			}

	/**
	 * Init UserSmCSVHandlerPro when WordPress Initialises.
	 */
	public function init() {
		if(is_admin()) :
			// Init action.
			do_action( 'uci_init' );
		if(is_admin()) {
#$this->includes();
			//SmUCIUserAdminAjax::smuci_ajax_events();
# Removed: De-Register the media sizes
		}
		endif;
	}
}

add_action( 'plugins_loaded', 'Smackcoders\\SMEXP\\onpluginsload' );
function onpluginsload(){
	$plugin = ExpCSVHandler::getInstance();
}
global $export_class;
$export_class = new ExpCSVHandler();

?>
