<?php
/******************************************************************************************
 * Copyright (C) Smackcoders. - All Rights Reserved under Smackcoders Proprietary License
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * You can contact Smackcoders at email address info@smackcoders.com.
 *******************************************************************************************/
namespace Smackcoders\SMEXP;

if ( ! defined( 'ABSPATH' ) )
exit; // Exit if accessed directly

class ExpInstall {

	protected static $instance = null,$smack_instance,$tables_instance;
	private static $db_updates = array();
	/**
	 * SmackCSVInstall Constructor
	 */
	public function __construct() {
		$this->plugin = Plugin::getInstance();
	}

	/**
	 * SmackCSVInstall Instance
	 */
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$smack_instance = new ExpInstall();
		}
		return self::$instance;
	}

	/**
	 * Hook in tabs.
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'check_version' ), 5 );
		add_action( 'admin_init', array( __CLASS__, 'install_actions' ) );
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( __CLASS__, 'smack_uci_action_links' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Check WPUltimateCSVImporterPro version.
	 */
	public static function check_version() {
		if ( get_option( 'ULTIMATE_CSV_IMP_VERSION' ) != SmackUCI()->version )  {
			self::install();
			do_action( 'sm_uci_pro_updated' );
		}
	}

	/**
	 * Install actions when a update button is clicked.
	 */
	public static function install_actions() {
		if ( ! empty( $_GET['do_update_sm_uci_pro'] ) ) {
			self::update();
		}
	}

	/**
	 * Show notice stating update was successful.
	 */
	public static function updated_notice() {
		?>
			<div class='notice updated uci-message wc-connect is-dismissible'>
			<p><?php esc_html__( 'Ultimate CSV Importer PRO data update complete. Thank you for updating to the latest version!', 'wp-ultimate-csv-importer-pro' ); ?></p>
			</div>
			<?php
	}

	/**
	 * @param $links
	 *
	 * @return array
	 */
	public function smack_uci_action_links( $links ) {
		$links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=sm-uci-settings') ) .'">Settings</a>';
		$links[] = '<a href="http://wp-buddy.com" target="_blank">More plugins by WP-Buddy</a>';
		return $links;
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param       mixed $links Plugin Row Meta
	 * @param       mixed $file  Plugin Base file
	 * @return      array
	 */
	public static function plugin_row_meta( $links, $file ) {

		$row_meta = array(
				
				'support' => '<a href="' . esc_url( apply_filters( 'sm_uci_support_url', admin_url() . 'admin.php?page=sm-uci-support' ) ) . '" title="' . esc_attr( __( 'Contact Support', 'wp-ultimate-exporter' ) ) . '" target="_blank">' . __( 'Support', 'wp-ultimate-exporter' ) . '</a>',
			);
			unset( $links['edit'] );
			//unset($links['View details']);

			return array_merge( $row_meta, $links );
	}

}
