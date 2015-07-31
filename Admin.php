<?php

if(!class_exists('AdminAndroidAppAmauri'))
{
    class AdminAndroidAppAmauri {

        var $hook = 'app-android-amauri',
            $longname = 'App Android (par AmauriC)',
            $shortname = 'App Android',
            $filename = 'app-android-amauri/app-android-amauri.php',
            $homepage = 'https://android.ferank.fr/';
        
        function __construct() {
            add_action('admin_menu', array($this, 'register_settings_page'));
            add_filter('plugin_action_links', array($this,'add_action_link'), 10, 2);
            add_action('admin_init', array($this,'register'));
			add_action('admin_enqueue_scripts', array($this,'js'));
			add_action('admin_bar_menu', array($this, 'menu_push'), 90);
			add_action('wp_enqueue_scripts', array($this, 'menu_push_icon'));
        }
        
        /**
         * Add link to the admin panel.
         */
        function register_settings_page() {
            add_submenu_page('tools.php', 'Notifications Push', 'Notifications Push', 'manage_options', $this->hook.'-push', array($this, 'push_page'));
			add_options_page($this->longname, $this->shortname, 'manage_options', $this->hook, array($this, 'config_page'));
        }
        
        /**
         * Add setting link to plugin list.
         */
        function add_action_link( $links, $file ) {
            static $this_plugin;
            if( empty($this_plugin) ) $this_plugin = $this->filename;
            if ( $file == $this_plugin ) {
                $settings_link = '<a href="' . admin_url('options-general.php?page='.$this->hook) . '">' . __('Réglages') . '</a>';
                array_unshift( $links, $settings_link );
            }
            return $links;
        }
        
        /**
         * Register options.
         */
        function register() {
            register_setting( 'appAndroidAmauri', 'androidappamauri_autopush' );
            register_setting( 'appAndroidAmauri', 'androidappamauri_apipush' );
            register_setting( 'appAndroidAmauri', 'androidappamauri_project' ); // project id push
            register_setting( 'appAndroidAmauri', 'androidappamauri_ga' ); // google analytics
            register_setting( 'appAndroidAmauri', 'androidappamauri_admob_t' ); // admob top
            register_setting( 'appAndroidAmauri', 'androidappamauri_admob_b' ); // admob bottom
            register_setting( 'appAndroidAmauri', 'androidappamauri_admob_splash' ); // admob splash
            register_setting( 'appAndroidAmauri', 'androidappamauri_admob_float' ); // admob float
            register_setting( 'appAndroidAmauri', 'androidappamauri_share' );
            register_setting( 'appAndroidAmauri', 'androidappamauri_similaire' );
            register_setting( 'appAndroidAmauri', 'androidappamauri_commentaire' );
            register_setting( 'appAndroidAmauri', 'androidappamauri_package' );
            register_setting( 'appAndroidAmauri', 'androidappamauri_exclude' );
        }
        
        /**
         * Settings page.
         */
        function config_page() {
			include(sprintf("%s/templates/config.php", dirname(__FILE__)));    
        }

        /**
         * Settings page.
         */
        function push_page() {
			global $wpdb;
			require(sprintf("%s/Push.php", dirname(__FILE__)));
			require(sprintf("%s/Utils.php", dirname(__FILE__)));
			include(sprintf("%s/templates/push.php", dirname(__FILE__)));    
        }
		
		/**
		 * Javascript notif push.
		 */
		function js() {
			wp_register_style('androidappamauri', plugins_url('app-android-par-amauric/css/admin.css'));
			wp_register_style('androidappamauridatetime', plugins_url('app-android-par-amauric/js/datetimepicker/jquery.datetimepicker.css'));
			wp_enqueue_style('androidappamauri');
			wp_enqueue_style('androidappamauridatetime');
			wp_enqueue_script('androidappamauri', plugins_url('app-android-par-amauric/js/admin.js'));
			wp_enqueue_script('androidappamauridatetime', plugins_url('app-android-par-amauric/js/datetimepicker/jquery.datetimepicker.js'), array('jquery'));
		}
		
		/**
		 * Menu toolbar.
		 */
		function menu_push($wp_admin_bar){
			if (is_single() && get_the_ID() > 0) {
				$args = array(
					'id' => 'android-create-pushs',
					'title' => '<span class="ab-icon"></span> Notification Push',
					'href' => admin_url('tools.php?page='.$this->hook.'-push&postID=' . get_the_ID())
				);
				$wp_admin_bar->add_node($args);
			}
		}
		
		/**
		 * Add icon to the admin toolbar.
		 */
		function menu_push_icon()
		{
			wp_register_style('androidappamauri_adminbar', plugins_url('app-android-par-amauric/css/adminbar.css'));
			wp_enqueue_style( 'androidappamauri_adminbar' );
		}
    }
}

if(class_exists('AdminAndroidAppAmauri'))
{
    $AdminAndroidAppAmauri = new AdminAndroidAppAmauri();
}
