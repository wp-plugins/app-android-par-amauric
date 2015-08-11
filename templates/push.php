<?php

if (isset($_POST['NOTIFICATIONSPUSH'])) {
	
	// insertion d'un cron
	if (isset($_POST['androidapppush_insert'])) {
		if ($_POST['androidapppush_insert'] != '') {
			
			$timestamp = $_POST['androidapppush_insert_timestamp'];
			$id_post = $_POST['androidapppush_insert_id_post'];
			$titre = $_POST['androidapppush_insert_titre'];
			$msg = $_POST['androidapppush_insert_msg'];
			
			$empreinte = md5($id_post . $titre . $msg . mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp), date('Y', $timestamp)));
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}AndroidAppAmauri_push (`send_date`, `id_post`, `titre`, `message`, `sended`, `empreinte`) VALUES (%s, %s, %s, %s, %s, %s)", $timestamp, $id_post, $titre, $msg, '0', $empreinte));
		}
	}
	
	// suppression notification
	if (isset($_POST['supprimer_notif'])) {
		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}AndroidAppAmauri_push SET `sended` = %s WHERE `id` = %s", '2', $_POST['supprimer_notif']));
	}
}

// lancement du cron
$PushAndroidAppAmauri->cron();

?>
<div class="wrap">
	<?php
	if (get_option('androidappamauri_apipush', '') == '') {
		?>
		<h1>Configuration requise</h1>
		<a href="<?php echo admin_url('options-general.php?page=app-android-amauri'); ?>">Vous devez saisir votre clée API.</a>
		<?php
	} else {
		// formulaire creation de notif
		$json = '';
		$js = '';
		$options = '';
		
		// if post id specified
		if (isset($_GET['postID'])) {
			$featPostID = $_GET['postID'];
			$options .= '<option value="'.$featPostID.'">'.get_the_title($featPostID).'</option>';
			$json .= '"'.$featPostID.'":[{"permalink":"'.get_the_permalink($featPostID).'","id":"'.$featPostID.'", "titre":"'.get_the_title($featPostID).'","texte":"'.mb_substr(preg_replace('#"#', '\"', html_entity_decode(strip_tags(str_replace(array("\r", "\n"),"", apply_filters('the_excerpt', get_post_field('post_content', $featPostID)))))), 0, 255).'","image":"'.$UtilsAndroidAppAmauri->getImage($featPostID).'"}],';
			
			$js = 'document.getElementById(\'pushpreview_step\').style.display = \'block\';AndroidAppNotifBuilder(\''.$featPostID.'\', notifpreview);';
		} else {
			$options = '<option></option>';
		}
		
		$data = wp_get_recent_posts(array('post_type' => 'post', 'post_status' => 'publish','numberposts' => 100));
		foreach($data as $d) {
			$excerpt = preg_replace('#"#', '\"', html_entity_decode(strip_tags(str_replace(array("\r", "\n"),"", apply_filters('the_excerpt', get_post_field('post_content', $d['ID']))))));
			$image = $UtilsAndroidAppAmauri->getImage($d['ID']);
			$titre = preg_replace('#"#', '\"', $d['post_title']);
			
			$options .= '<option value="'.$d['ID'].'">'.$titre.'</option>';
			$json .= '"'.$d['ID'].'":[{"permalink":"'.get_the_permalink($d['ID']).'","id":"'.$d['ID'].'", "titre":"'.$titre.'","texte":"'.mb_substr($excerpt, 0, 255).'","image":"'.$image.'"}],';
		}
		$json_recent = 'var notifpreview = {"data":{'.trim($json, ',').'}};' . $js;
		
		echo '<input type="button" onclick="document.getElementById(\'pushpreview_step\').style.display = \'block\';" value="+ Créer une notification" class="button button-primary button-large" style="font-size: 20px;padding: 20px;line-height: 0px;" />
		<form action="'.admin_url('tools.php?page='.$this->hook.'-push').'" method="post" id="androidapppush_insert">
		<input type="hidden" name="NOTIFICATIONSPUSH" value="1" />
		<input type="hidden" name="androidapppush_insert" value="1" />
		<input type="hidden" name="androidapppush_insert_id_post" id="androidapppush_insert_id_post" value="" />
		
		<div id="pushpreview_step">
			<h1>Programmation d\'un push</h1>
			<h2>Destination</h2>
			<select style="margin-bottom:3px" onchange="AndroidAppNotifBuilder(this.value, notifpreview);">'.$options.'</select>
			<br/><b>L\'article n\'est pas dans la liste ?</b><br/>Cliquez directement sur le bouton `Notification Push` de la barre admin depuis l\'article.<br/>
			<div id="pushpreview_started">
			<br/><br/>
			<h2>Notification</h2>
			<div id="pushpreview" style="display:none" onclick="window.open(\'\')">
				<div id="pushpreview_image"></div>
				<div class="pushpreview_texte">
					<b id="pushpreview_titre"></b><br/>
					<span id="pushpreview_message"></span>
				</div>
				<div class="clear"></div>
			</div>
			<div class="pushpreview_edit">
				<input onkeyup="document.getElementById(\'pushpreview_titre\').innerHTML = this.value" type="text" name="androidapppush_insert_titre" id="androidapppush_insert_titre" value="" />
				<textarea onkeyup="document.getElementById(\'pushpreview_message\').innerHTML = this.value"" name="androidapppush_insert_msg" id="androidapppush_insert_msg"></textarea>
			</div>
			<h2>Date de push</h2>
			<input type="hidden" name="androidapppush_insert_timestamp" id="androidapppush_insert_timestamp" value="' .current_time('timestamp'). '" />
			<br/><br/>
			<input type="submit" class="button button-primary button-large" value="Programmer la notification" />
			</div>
			<br/><br/><a href="javascript:void(0)" onclick="document.getElementById(\'pushpreview_step\').style.display = \'none\';">Annuler</a><br/><br/>
		</div>
		</form>';
		
		// afficher/masquer supprime
		echo '<br/><br/><a href="javascript:void(0)" onclick="jQuery(\'.pushpreview_since\').toggle();jQuery(\'.pushpreview\').toggle();jQuery(\'.pushlogs\').toggle();"><b>Afficher/Masquer les logs</b></a> | <a href="javascript:void(0)" onclick="jQuery(\'.pushpreview_deleted\').toggle();">Afficher/Masquer les notifications supprimées</a><br/><br/>';
		
		// logs
		echo '<div class="pushlogs" style="display:none;max-height:400px;overflow:auto;">';
		$query = $wpdb->get_results("SELECT `gmt`, `http`, `message` FROM {$wpdb->prefix}AndroidAppAmauri_logs ORDER BY `gmt` DESC");
		foreach($query as $obj) {
			if ($obj->http == 200) {
				$colorFont = 'darkgreen';
			} else if ($obj->http < 400) {
				$colorFont = 'darkorange';
			} else {
				$colorFont = 'darkred';
			}
			
			echo '<div style="display:inline-block;background:' . $colorFont . ';padding:8px;color:#fff;text-align:center;width:150px;">' . $obj->http . '</div>
			<div style="display:inline-block;padding:8px;font-family:courier;white-space: pre;vertical-align: top;background: #333;color: #fff;width: 70%;"><b><span style="color:#0FB70B;font-size:14px;">@ ' . date('d/m/Y H:i:s', $obj->gmt) . '</span></b><br/>' . print_r(json_decode($obj->message), true) . '</div>
			<div class="clear"></div>';
		}
		echo '</div>';
		
		// historique
		$query = $wpdb->get_results("SELECT `id`, `id_post`, `titre`, `message`, `send_date`, `sended` FROM {$wpdb->prefix}AndroidAppAmauri_push ORDER BY `send_date` DESC");
		foreach($query as $obj) {
				
			// deja envoye ou non ?
			$annuler = '';
			$isDeleted = false;
			if ($obj->sended == '2') {
				$isDeleted = true;
				$icon = '<b style="color:darkred">&#10007;</b> ';
				$annuler = '<span><br/>supprimée - non envoyée</span>';
			} else if ($obj->send_date > current_time('timestamp')) {
				$icon = '<b style="color:darkorange">&#x1F55C;</b> ';
				$annuler = '<form action="'.admin_url('tools.php?page='.$this->hook.'-push').'" method="post"><input type="hidden" name="NOTIFICATIONSPUSH" value="1" /><input type="hidden" name="supprimer_notif" value="'.$obj->id.'" /><input type="submit" value="Supprimer" /></form>';
			} else {
				$icon = '<b style="color:darkgreen">&#10003;</b> ';
			}
			
			$image = $UtilsAndroidAppAmauri->getImage($obj->id_post);
			
			$avant = 'il y a ';
			if ($obj->send_date > current_time('timestamp')) {$avant = 'dans ';}
			
			if ($isDeleted) {echo '<div class="pushpreview_deleted">';}
			echo '<div class="pushpreview pushpreview_block" onclick="window.open(\''.get_the_permalink($obj->id_post).'\')">
				<div class="pushpreview_image" style="background-image:url('.$image.')"></div>
				<div class="pushpreview_texte">
					<b>'.stripslashes($obj->titre).'</b><br/>
					'.stripslashes($obj->message).'
				</div>
				<div class="clear"></div>
			</div>
			<div class="pushpreview_since">
				' . $icon . $avant . human_time_diff( $obj->send_date, current_time('timestamp') ) . '
				' . $annuler . '
			</div>
			
			<div class="clear" style="height:20px"></div>';
			if ($isDeleted) {echo '</div>';}
		}
		
		echo '<script type="text/javascript">'.$json_recent.'</script>';
	} ?>
</div>
<div style="clear:both"></div>
<style type="text/css">.AndroidAppAmauri_div{background:#FFF;padding: 10px;border: 1px solid #eee;border-bottom: 2px solid #ddd;max-width: 500px;}</style>