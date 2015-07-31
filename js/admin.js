/**
 * Live preview des notifications push.
 */

function AndroidAppNotifBuilder(post_id, notifpreview) {

	// recuperation des datas json
	var json = notifpreview.data[post_id][0],
		image = json.image,
		titre = json.titre,
		texte = json.texte;
		
	// live preview
	document.getElementById('pushpreview').onclick = function() {window.open(json.permalink)};
	document.getElementById('pushpreview').style.display = 'inline-block';
	document.getElementById('pushpreview_started').style.display = 'block';
	document.getElementById('pushpreview_image').style.backgroundImage = 'url(' + image + ')';
	document.getElementById('pushpreview_titre').innerHTML = titre;
	document.getElementById('pushpreview_message').innerHTML = texte;
	
	// champs du formulaire	
	document.getElementById('androidapppush_insert_id_post').value = post_id;
	document.getElementById('androidapppush_insert_titre').value = titre;
	document.getElementById('androidapppush_insert_msg').value = texte;
	
	jQuery(function(){
		jQuery('#androidapppush_insert_timestamp').datetimepicker({
			minDate:0,
			format:'unixtime',
			inline:true,
			lang:'fr'
		});
	});
}
