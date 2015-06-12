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
        }
        
        /**
         * Add link to the admin panel.
         */
        function register_settings_page() {
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
            register_setting( 'appAndroidAmauri', 'androidappamauri_apipush' );
            register_setting( 'appAndroidAmauri', 'androidappamauri_package' );
            register_setting( 'appAndroidAmauri', 'androidappamauri_exclude' );
        }
        
        /**
         * Settings page.
         */
        function config_page() {
			include(sprintf("%s/templates/config.php", dirname(__FILE__)));    
        }
    }
}

if(class_exists('AdminAndroidAppAmauri'))
{
    $AdminAndroidAppAmauri = new AdminAndroidAppAmauri();
}
