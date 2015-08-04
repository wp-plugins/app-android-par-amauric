<div class="wrap">
    <h1>Votre application Android</h1>
	<h4>La mise à jour des apps peut durer jusqu'à 1 heure</h4>
    <form method="post" action="options.php" style="max-width:990px;" id="form_appandroid">
	<br/>
    <?php
    settings_fields( 'appAndroidAmauri' );
    ?>
	
	<a class="configApp_menupicker" href="javascript:void(0)" onclick="appconfig_lower();jQuery('#configApp_interface').toggle();"><span class="dashicons dashicons-admin-appearance"></span><br/>Interface de l'app</a>
	<a class="configApp_menupicker" href="javascript:void(0)" onclick="appconfig_lower();jQuery('#configApp_menu').toggle();"><span class="dashicons dashicons-menu"></span><br/>Pages du menu</a>
	<a class="configApp_menupicker" href="javascript:void(0)" onclick="appconfig_lower();jQuery('#configApp_push').toggle();"><span class="dashicons dashicons-admin-comments"></span><br/>Notifications push</a>
	<a class="configApp_menupicker" href="javascript:void(0)" onclick="appconfig_lower();jQuery('#configApp_ga').toggle();"><span class="dashicons dashicons-dashboard"></span><br/>Google Analytics</a>
	<a class="configApp_menupicker" href="javascript:void(0)" onclick="appconfig_lower();jQuery('#configApp_admob').toggle();"><span class="dashicons dashicons-megaphone"></span><br/>AdMob (publicités)</a>
	<a class="configApp_menupicker" href="javascript:void(0)" onclick="appconfig_lower();jQuery('#configApp_play').toggle();"><span class="dashicons dashicons-cart"></span><br/>Fiche Play Store</a>
	<a class="configApp_menupicker" href="javascript:void(0)" onclick="appconfig_lower();jQuery('#configApp_appindexing').toggle();"><span class="dashicons dashicons-admin-links"></span><br/>App Indexing (SEO)</a>
	<a class="configApp_menupicker" href="javascript:void(0)" onclick="appconfig_lower();jQuery('#configApp_mentions').toggle();"><span class="dashicons dashicons-admin-users"></span><br/>Mentions légales</a>
	
	<div id="configApp_push" class="configApp">
    <h2>Notifications push</h2>
	<input type="button" onclick="document.location = '<?php echo admin_url('tools.php?page='.$this->hook.'-push');?>';" value="+ Créer une notification" class="button button-primary button-large" style="font-size: 16px;padding: 16px 20px;line-height: 0px;margin-bottom:20px;" />
    <div class="AndroidAppAmauri_div">
	<h3>Pour vous guider dans les étapes, suivez <a href="http://amauri.champeaux.fr/developpement/android/push/" target="_blank">le tutoriel</a></h3>
    <table class="form-table">
		<tr valign="top">
			<th scope="row" style="padding: 15px 0 0;color: gray;font-weight: 500;font-size: 12px;">Configuration</th>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('API Key Google');?><br/></th>
			<td><input type="text" name="androidappamauri_apipush" value="<?php echo get_option('androidappamauri_apipush');?>" /></td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e('Numéro du projet');?><br/></th>
			<td><input type="text" name="androidappamauri_project" value="<?php echo get_option('androidappamauri_project');?>" /></td>
		</tr>
		
		<tr valign="top">
			<th scope="row" style="padding: 15px 0 0;color: gray;font-weight: 500;font-size: 12px;">Envoi automatique</th>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('A la publication d\'un article');?></th>
			<td><input type="checkbox" name="androidappamauri_autopush" value="1" <?php if (get_option('androidappamauri_autopush', '1') == '1'){echo 'checked';} ?> /></td>
		</tr>
    </table>
    </div>
	</div>
	
	<div id="configApp_appindexing" class="configApp">
    <h2>App indexing</h2>
    <div class="AndroidAppAmauri_div">
	<h3 style="border-bottom:0;padding-bottom:0">Pour vous guider dans les étapes, suivez <a href="http://amauri.champeaux.fr/developpement/android/app-indexing/" target="_blank">le tutoriel</a></h3>
    </div>
	</div>
	
	<div id="configApp_admob" class="configApp">
    <h2>AdMob (Google AdSense)</h2>
    <div class="AndroidAppAmauri_div">
	<h3>Pour créer un nouveau bloc d'annonces, suivez <a href="https://support.google.com/admob/answer/3052638?hl=fr" target="_blank">le tutoriel</a></h3>
    <table class="form-table">	
		<tr valign="top">
			<th scope="row" style="padding: 15px 0 0;color: gray;font-weight: 500;font-size: 12px;">Au démarrage</th>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Interstitial (1x / heure)');?></th>
			<td><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="androidappamauri_admob_splash" value="<?php echo get_option('androidappamauri_admob_splash');?>" /></td>
		</tr>
		
		<tr valign="top">
			<th scope="row" style="padding: 15px 0 0;color: gray;font-weight: 500;font-size: 12px;">Toutes les pages</th>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Bandeau flottant');?></th>
			<td><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="androidappamauri_admob_float" value="<?php echo get_option('androidappamauri_admob_float');?>" /></td>
		</tr>
		
		<tr valign="top">
			<th scope="row" style="padding: 15px 0 0;color: gray;font-weight: 500;font-size: 12px;">Page des articles</th>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('300x250 <u>au dessus</u> des articles');?></th>
			<td><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="androidappamauri_admob_t" value="<?php echo get_option('androidappamauri_admob_t');?>" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('300x250 <u>en dessous</u> des articles');?></th>
			<td><input placeholder="ca-app-pub-XXXXXXXXXXXXXXXX/NNNNNNNNNN" type="text" name="androidappamauri_admob_b" value="<?php echo get_option('androidappamauri_admob_b');?>" /></td>
		</tr>
    </table>
    </div>
	</div>

	<div id="configApp_ga" class="configApp">
    <h2>Google Analytics</h2>
    <div class="AndroidAppAmauri_div">
	<h3>Pour créer une nouvelle vue, suivez <a href="https://support.google.com/analytics/answer/1009714?hl=fr" target="_blank">le tutoriel</a></h3>
    <table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('Identifiant');?></th>
			<td><input placeholder="UA-XXXXXXXX-X" type="text" name="androidappamauri_ga" value="<?php echo get_option('androidappamauri_ga');?>" /></td>
		</tr>
    </table>
    </div>
	</div>
	
	<div id="configApp_mentions" class="configApp">
    <h2>Mentions légales</h2>
    <div class="AndroidAppAmauri_div">
	<h3>Entrez les mentions légales de votre blog. <a href="http://amauri.champeaux.fr/administration/droit-juridique/mentions-legales/" target="_blank">Coment les rédiger ?</a></h3>
    <table class="form-table">
		<tr valign="top">
			<td><?php wp_editor( get_option('androidappamauri_mentions'), 'androidappamauri_mentions', array(
				'media_buttons' => false,
				'teeny' => true,
			) ); ?></td>
		</tr>
    </table>
    </div>
	</div>
	
	<div id="configApp_play" class="configApp">
    <h2>Play Store</h2>
    <div class="AndroidAppAmauri_div">
	<h3>Un widget vous permet de faire un lien vers la fiche de l'app</h3>
    <table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('Nom du package');?></th>
			<td><input type="text" name="androidappamauri_package" placeholder="lol.app.xxxxx" value="<?php echo get_option('androidappamauri_package');?>" /></td>
		</tr>
    </table>
    </div>
	</div>

	<div id="configApp_interface" class="configApp">
    <h2>Interface de l'app</h2>
    <div class="AndroidAppAmauri_div">
	<h3>Personnalisation de l'interface de l'app</h3>
    <table class="form-table">
		<tr valign="top">
			<th scope="row" style="padding: 15px 0 0;color: gray;font-weight: 500;font-size: 12px;">Couleurs principales</th>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Thème');?></th>
			<td class="mini_android_label">
				<label><input name="androidappamauri_theme" type="radio" value="blue" <?php if (get_option('androidappamauri_theme') == 'blue'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#03A9F4"></div><div class="mini_android_bubble" style="background:#FF5252"></div></div></label>
				<label><input name="androidappamauri_theme" type="radio" value="vert" <?php if (get_option('androidappamauri_theme') == 'vert'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#4CAF50"></div><div class="mini_android_bubble" style="background:#A62A54"></div></div></label>
				<label><input name="androidappamauri_theme" type="radio" value="jaune" <?php if (get_option('androidappamauri_theme') == 'jaune'){echo 'checked';} ?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#D8C600"></div><div class="mini_android_bubble" style="background:#FF4081"></div></div></label>
				<label><input name="androidappamauri_theme" type="radio" value="orange" <?php if (get_option('androidappamauri_theme') == 'orange'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#FF5722"></div><div class="mini_android_bubble" style="background:#8BC34A"></div></div></label>
				<label><input name="androidappamauri_theme" type="radio" value="rouge" <?php if (get_option('androidappamauri_theme') == 'rouge'){echo 'checked';} ?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#F44336"></div><div class="mini_android_bubble" style="background:#8BC34A"></div></div></label>
				<label><input name="androidappamauri_theme" type="radio" value="violet" <?php if (get_option('androidappamauri_theme') == 'violet'){echo 'checked';} ?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#9C27B0"></div><div class="mini_android_bubble" style="background:#D8C600"></div></div></label>
				<label><input name="androidappamauri_theme" type="radio" value="gris" <?php if (get_option('androidappamauri_theme') == 'gris'){echo 'checked';}?> /><div class="mini_android"><div class="mini_android_toolbar" style="background:#607D8B"></div><div class="mini_android_bubble" style="background:#A66300"></div></div></label>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row" style="padding: 15px 0 0;color: gray;font-weight: 500;font-size: 12px;">Page des articles</th>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Afficher les articles similaires');?></th>
			<td><input type="checkbox" name="androidappamauri_similaire" value="1" <?php if (get_option('androidappamauri_similaire', '1') == '1'){echo 'checked';} ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Afficher les commentaires');?></th>
			<td><input type="checkbox" name="androidappamauri_commentaire" value="1" <?php if (get_option('androidappamauri_commentaire', '1') == '1'){echo 'checked';} ?> /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('Afficher la bulle de partage');?></th>
			<td><input type="checkbox" name="androidappamauri_share" value="1" <?php if (get_option('androidappamauri_share', '1') == '1'){echo 'checked';} ?> /></td>
		</tr>
    </table>
    </div>
	</div>
	
	<div id="configApp_menu" class="configApp">
    <h2>Pages à exclure du menu</h2>
    <div class="AndroidAppAmauri_div">
    <table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('Pages à exclure');?></th>
			<td>
				<?php
				$exclude = get_option('androidappamauri_exclude');
				$pages = get_pages(); 
				foreach ( $pages as $page ) {
					echo '<label><input type="checkbox" name="androidappamauri_exclude[]" value="' . $page->ID . '" ';
					if (is_array($exclude))
					{
						if (in_array($page->ID, $exclude)) {
							echo 'checked';
						}
					}
					echo ' /> ' . trim($page->post_title) . '</label><br/>';
				}
				?>
			</td>
		</tr>
    </table>
    </div>
	</div>
	
    <?php submit_button(); ?>
    </form>
</div>
<div style="clear:both"></div>
<script type="text/javascript">
function appconfig_lower() {
	jQuery('#form_appandroid').css('max-width', '100%');
	jQuery('.configApp').css('display', 'none');
	jQuery('.configApp_menupicker').css('width', '80px');
	jQuery('.configApp_menupicker').css('margin-right', '10px');
	jQuery('.configApp_menupicker').css('font-size', '12px');
	jQuery('.configApp_menupicker span.dashicons').css('margin-bottom', '2px');
	jQuery('.configApp_menupicker span.dashicons').css('width', '30px');
	jQuery('.configApp_menupicker span.dashicons').css('height', '30px');
	jQuery('.configApp_menupicker span.dashicons').css('font-size', '30px');
}
</script>