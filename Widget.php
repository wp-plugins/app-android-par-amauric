<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('AndroidAppAmauri_Widget'))
{
    class AndroidAppAmauri_Widget extends WP_Widget {
    
        public function __construct() {
            parent::__construct(
                'AndroidAppAmauri_Widget',
                'App Android',
                array( 'description' => __( 'Affichage du lien vers le Play Store.', 'AndroidAppAmauri' ), )
            );
        }
	
        public function widget( $args, $instance ) {
            extract( $args );
		
            echo $before_widget;
            echo '<a href="https://play.google.com/store/apps/details?id=' . get_option('androidappamauri_package') . '" target="_blank"><img src="https://developer.android.com/images/brand/'.$instance['lien'].'" width="100%" alt="" border="0" /></a>';
            echo $after_widget;
        }
    
        public function form( $instance ) {
           
            if ( isset( $instance[ 'lien' ] ) ) {
                $lien = $instance[ 'lien' ];
            } else {
                $lien = '';
            }

            // Choix de l'image
			$images = array(
				'Français' => 'title',
				'[Petit] Appli Android sur Google Play' => 'fr_app_rgb_wo_45.png',
				'[Petit] Disponible sur Google Play' => 'fr_generic_rgb_wo_45.png',
				'[Grand] Appli Android sur Google Play' => 'fr_app_rgb_wo_60.png',
				'[Grand] Disponible sur Google Play' => 'fr_generic_rgb_wo_60.png',
				'English' => 'title',
				'[Small] Android app on Google Play' => 'en_app_rgb_wo_45.png',
				'[Small] Get it on Google Play' => 'en_generic_rgb_wo_45.png',
				'[Big] Android app on Google Play' => 'en_app_rgb_wo_60.png',
				'[Big] Get it on Google Play' => 'en_generic_rgb_wo_60.png',
			);
			
			$grouped = false;
			echo '<select style="margin:15px 0;width:70%" id="'.$this->get_field_id( 'lien' ).'" name="'.$this->get_field_name( 'lien' ).'">';
			foreach($images as $name => $image) {
				if ($image == 'title') {
					if ($grouped) {echo '</optgroup>';}
					
					$grouped = true;
					echo '<optgroup label="'.$name.'">';
				} else {
					echo '<option value="'.$image.'"';if($image == $lien) {echo ' selected';}echo '>'.$name.'</option>';
				}
			}
			echo '</optgroup>
			</select>';
        }
    
        public function update( $new_instance, $old_instance ) {
            $instance = array();
            $instance['lien'] = ( !empty( $new_instance['lien'] ) ) ? strip_tags( $new_instance['lien'] ) : '';
            return $instance;
        }
    }
}

// Initialisation du widget
add_action('widgets_init', function() {
    register_widget('AndroidAppAmauri_Widget');
});