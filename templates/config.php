<div class="wrap" style="float:left">
    <h1>Votre application Android</h1>
    <form method="post" action="options.php">
    <?php
    settings_fields( 'appAndroidAmauri' );
    ?>
    <h2 style="margin-bottom:20px;margin-top:20px;">Notifications push</h2>
    <div class="AndroidAppAmauri_div">
    <table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('API Key Google');?><br/><a href="http://amauri.champeaux.fr/developpement/android/push/" target="_blank">>> Tutoriel <<</a></th>
			<td><input type="text" name="androidappamauri_apipush" value="<?php echo get_option('androidappamauri_apipush');?>" /></td>
		</tr>
    </table>
    </div>
	
    <h2 style="margin-bottom:20px;margin-top:50px;">App indexing</h2>
    <div class="AndroidAppAmauri_div">
    <table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('Nom du package');?><br/><a href="http://amauri.champeaux.fr/developpement/android/app-indexing/" target="_blank">>> Tutoriel <<</a></th>
			<td><input type="text" name="androidappamauri_package" value="<?php echo get_option('androidappamauri_package');?>" /></td>
		</tr>
    </table>
    </div>
	
    <h2 style="margin-bottom:20px;margin-top:50px;">Pages à exclure du menu</h2>
    <div class="AndroidAppAmauri_div">
    <table class="form-table">
		<tr valign="top">
			<th scope="row"><?php _e('Pages à exclure');?></th>
			<td>
				<?php
				$exclude = get_option('androidappamauri_exclude');
				$pages = get_pages(); 
				foreach ( $pages as $page ) {
					echo '<label><input type="checkbox" name="androidappamauri_exclude[]" value="' . $page->ID . '" ';if (in_array($page->ID, $exclude)) {echo 'checked';}echo ' /> ' . trim($page->post_title) . '</label><br/>';
				}
				?>
			</td>
		</tr>
    </table>
    </div>
	
    <?php submit_button(); ?>
    </form>
</div>
<div style="clear:both"></div>
<style type="text/css">.AndroidAppAmauri_div{background:#FFF;padding: 10px;border: 1px solid #eee;border-bottom: 2px solid #ddd;max-width: 500px;}</style>