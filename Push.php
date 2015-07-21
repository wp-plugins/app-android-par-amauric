<?php

if(!class_exists('PushAndroidAppAmauri'))
{
    class PushAndroidAppAmauri {

		public function sendToAndroid($id_post, $post)
		{
			global $wpdb;
			$row = $wpdb->get_results("SELECT registration_id FROM {$wpdb->prefix}AndroidAppAmauri_ids");
			$id = array();
			$send = 0;
			foreach($row as $r) {
				$send++;
				$id[] = $r->registration_id;
			}
			if($send > 0 && get_option('androidappamauri_apipush', '') != '')
			{
				$titre = $post->post_title;				
				$content = wp_strip_all_tags($post->post_content);
				$intro = substr($content, 0, 100);
				$msg = substr($content, 0, 255);
				
				require(sprintf("%s/Utils.php", dirname(__FILE__)));
				$image = $UtilsAndroidAppAmauri->getImage($id_post);
				if ($image == '') {
					$image = ' ';
				}
				
				$url = 'https://android.googleapis.com/gcm/send';
				$message = array("url" => get_permalink( $id_post ), "image" => $image, "id" => $id_post, "title" => $titre, "info" => $intro, "msg" => $msg);
				$fields = array('time_to_live' => 86400, 'collapse_key' => 'WP ' . $_SERVER['SERVER_NAME'], 'registration_ids' => $id, 'data' => $message);
				$headers = array('Authorization: key=' . get_option('androidappamauri_apipush'), 'Content-Type: application/json');
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
				$result = curl_exec($ch);
				curl_close($ch);
			}
		}
		
		public function register() {
			global $wpdb;
			
			$id = $_POST['regId'];
			$device = $_POST['u'];
			
			if ($id != '' AND $device != '') {
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}AndroidAppAmauri_ids SET `registration_id` = %s WHERE `device_id` = %s", $id, $device));
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}AndroidAppAmauri_ids (`registration_id`, `device_id`) VALUES (%s, %s)", $id, $device));
			}
		}
		
		public function unregister() {
			global $wpdb;
			
			$device = $_POST['u'];
			$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}AndroidAppAmauri_ids WHERE `device_id` = %s", $device));
		}
    }
}

if(class_exists('PushAndroidAppAmauri'))
{
    $PushAndroidAppAmauri = new PushAndroidAppAmauri();
}