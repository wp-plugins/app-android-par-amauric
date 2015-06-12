<?php

if(!class_exists('SEOAndroidAppAmauri'))
{
    class SEOAndroidAppAmauri {

		public function meta() {
			if (is_single()) {
				global $post;
				if ($post->ID != '' && get_option('androidappamauri_package', '') != '') {
					echo '<link rel="alternate" href="android-app://' . get_option('androidappamauri_package') . '/http/' . preg_replace('#http[s]?://#', '', get_permalink($post->ID)) . '" />';
				}
			}
		}
    }
}

if(class_exists('SEOAndroidAppAmauri'))
{
    $SEOAndroidAppAmauri = new SEOAndroidAppAmauri();
}