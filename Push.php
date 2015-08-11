<?php

if(!class_exists('PushAndroidAppAmauri'))
{
    class PushAndroidAppAmauri {
		
		public function __construct() {
			add_action( 'sendnotificationspush', array($this, 'cron') );
		}

		public function send($id_post, $titre, $content) {
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
				$intro = substr($content, 0, 100);
				$msg = substr($content, 0, 255);
				
				require(sprintf("%s/Utils.php", dirname(__FILE__)));
				$image = $UtilsAndroidAppAmauri->getImage($id_post);
				if ($image == '') {
					$image = ' ';
				}
				
				$url = 'https://android.googleapis.com/gcm/send';
				$message = array("url" => get_permalink( $id_post ), "image" => $image, "id" => $id_post, "title" => $titre, "info" => $intro, "msg" => $msg);
				$fields = array('time_to_live' => 86400, 'collapse_key' => 'WP ' . $id_post, 'registration_ids' => $id, 'data' => $message);
				$headers = array('Authorization: key=' . get_option('androidappamauri_apipush'), 'Content-Type: application/json');
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
				$result = curl_exec($ch);
				$httpResponse = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				
				// log
				$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}AndroidAppAmauri_logs (`gmt`, `http`, `message`) VALUES (%s, %s, %s)", current_time('timestamp'), $httpResponse, $result));
			} else {
				if (get_option('androidappamauri_apipush', '') == '') {
					$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}AndroidAppAmauri_logs (`gmt`, `http`, `message`) VALUES (%s, %s, %s)", current_time('timestamp'), '0', '{"data":"Clé API absente"}'));
				} else {
					$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}AndroidAppAmauri_logs (`gmt`, `http`, `message`) VALUES (%s, %s, %s)", current_time('timestamp'), '0', '{"data":"Aucun destinataire"}'));
				}
			}
		}
		
		public function sendToAndroid($id_post, $post) {
			
			// si edition 2mns apres creation
			$start_date = new DateTime($post->post_date);
			$since_start = $start_date->diff(new DateTime($post->post_modified));

			// exit if already pushed
			if (get_option('androidappamauri_push_' . $id_post, '0') == '1' OR get_option('androidappamauri_autopush', '1') == '0' OR $since_start->i > 2) {
				return;
			}
			
			global $wpdb;
			
			// do not push more than 1 time
			add_option('androidappamauri_push_' . $id_post, '1');

			$titre = $post->post_title;				
			$content = mb_substr(wp_strip_all_tags($post->post_content), 0, 255);
			
			// ajout a l'historique
			$empreinte = md5($id_post . $titre . $content . mktime(0, 0, 0, date('m', current_time('timestamp')), date('d', current_time('timestamp')), date('Y', current_time('timestamp'))));
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}AndroidAppAmauri_push (`send_date`, `id_post`, `titre`, `message`, `sended`, `empreinte`) VALUES (%s, %s, %s, %s, %s, %s)", current_time('timestamp'), $id_post, $titre, $content, '1', $empreinte));
			
			$this->send($id_post, $titre, $content);
		}
		
		public function cron() {
			global $wpdb;
			
			$query = $wpdb->get_results($wpdb->prepare("SELECT `id`, `id_post`, `titre`, `message` FROM {$wpdb->prefix}AndroidAppAmauri_push WHERE `send_date` < %s AND sended = '0' ORDER BY `id` DESC", current_time('timestamp')));
			foreach($query as $obj) {
				$this->send($obj->id_post, stripslashes($obj->titre), stripslashes($obj->message));
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}AndroidAppAmauri_push SET `sended` = %s WHERE `id` = %s", '1', $obj->id));
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