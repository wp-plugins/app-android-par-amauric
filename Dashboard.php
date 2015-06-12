<?php

if(!class_exists('DashboardAndroidAppAmauri'))
{
    class DashboardAndroidAppAmauri {

		public function widget() {
			wp_add_dashboard_widget(
				'android_app_widget',
                'Android App',
                array($this, 'getPush')
			);
		}

		public function getPush() {
			global $wpdb;
			$row = $wpdb->get_results("SELECT COUNT(registration_id) as nb FROM {$wpdb->prefix}AndroidAppAmauri_ids");
			
			echo '<b style="font-size:22px;color:#333">' . $row[0]->nb . '</b><br/><span style="color:gray;font-size:15px;">abonnés aux notifications push</span>';
			if (get_option('androidappamauri_package', '') != '') {echo '<br/><br/><a href="https://play.google.com/store/apps/details?id='.get_option('androidappamauri_package', '').'">Voir la fiche Play Store</a>';}
		}
    }
}

if(class_exists('DashboardAndroidAppAmauri'))
{
    $DashboardAndroidAppAmauri = new DashboardAndroidAppAmauri();
}