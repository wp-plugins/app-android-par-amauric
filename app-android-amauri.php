<?php
/*
Plugin Name: App Android (par Amauri)
Plugin URI: https://android.ferank.fr/
Description: Création de l'interface entre le blog et l'application.
Version: 0.5.5
Author: Amauri CHAMPEAUX
Author URI: http://amauri.champeaux.fr/a-propos
*/

if(!class_exists('AndroidAppAmauri'))
{
	class AndroidAppAmauri
	{
		public function __construct() {
			require_once(sprintf("%s/Admin.php", dirname(__FILE__)));
			require_once(sprintf("%s/Push.php", dirname(__FILE__)));
			require_once(sprintf("%s/SEO.php", dirname(__FILE__)));
			require_once(sprintf("%s/Rewrite.php", dirname(__FILE__)));
			require_once(sprintf("%s/Dashboard.php", dirname(__FILE__)));
			require_once(sprintf("%s/Widget.php", dirname(__FILE__)));
			
			add_action('template_redirect', array($RewriteAndroidAppAmauri, 'json' ), 0);
			add_action('publish_post', array($PushAndroidAppAmauri, 'sendToAndroid' ), 10, 2);
			add_action('wp_head', array($SEOAndroidAppAmauri, 'meta'));
			add_action('wp_dashboard_setup', array($DashboardAndroidAppAmauri, 'widget'));
		}
		
		public static function activate() {
			global $wpdb;

			$charset_collate = $wpdb->get_charset_collate();
			$sql = '';
			$sql .= "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."AndroidAppAmauri_ids (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
				`registration_id` varchar(255) NOT NULL DEFAULT '',
				`device_id` varchar(255) NOT NULL DEFAULT '',
				UNIQUE KEY `id` (`id`),
				UNIQUE KEY `registration_id` (`registration_id`),
				UNIQUE KEY `device_id` (`registration_id`)
			) $charset_collate;";
			$sql .= "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."AndroidAppAmauri_push (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
				`send_date` int(20) NOT NULL DEFAULT 0,
				`sended` varchar(1) NOT NULL DEFAULT '0',
				`empreinte` varchar(64) NOT NULL DEFAULT '',
				`id_post` int(20) NOT NULL DEFAULT 0,
				`titre` varchar(255) NOT NULL DEFAULT '',
				`message` varchar(255) NOT NULL DEFAULT '',
				UNIQUE KEY `id` (`id`),
				UNIQUE KEY `empreinte` (`empreinte`)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			
			// cron push
			wp_schedule_event( time(), 'hourly', 'sendnotificationspush' );
		}
		
		public static function deactivate() {
			global $wpdb;
			//$wpdb->query("DROP TABLE {$wpdb->prefix}AndroidAppAmauri_ids");
			$wpdb->query("DROP TABLE {$wpdb->prefix}AndroidAppAmauri_push");
			
			// remove cron
			wp_clear_scheduled_hook( 'sendnotificationspush' );
		}
	}
}

if(class_exists('AndroidAppAmauri'))
{
	register_activation_hook(__FILE__, array('AndroidAppAmauri', 'activate'));
	register_deactivation_hook(__FILE__, array('AndroidAppAmauri', 'deactivate'));
	
	$AndroidAppAmauri = new AndroidAppAmauri();
}