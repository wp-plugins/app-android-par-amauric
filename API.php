<?php

if(!class_exists('APIAndroidAppAmauri'))
{
    class APIAndroidAppAmauri {
		
		public function read($id) {

			if (preg_match('#^http#', $id)) {
				$id = url_to_postid(preg_replace('#__sla_sh__#', '/', $id));
			}
			
			$content_post = get_post($id);
			$categories = get_the_category($id);
			$separator = ' ';
			$output = '';
			$cat = '';
			$catID = '';
			if($categories){
				foreach($categories as $category) {
					if ($output == '') {
						$output = $category->cat_name.$separator;
					}
					
					$catID .= $category->term_id . ',';
				}
				$cat = trim($output, $separator);
			}
			$catID = trim($catID, ',');
			
			require(sprintf("%s/Utils.php", dirname(__FILE__)));
			$info = 'Il y a ' . $UtilsAndroidAppAmauri->humanTime(current_time('timestamp') - strtotime($content_post->post_date)) . ' par ' . get_the_author_meta('display_name', $content_post->post_author);
			
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			$content = html_entity_decode(str_replace(array("\r", "\n"),"", $content));
			
			// remove link on image
			$content = preg_replace('/<a[^>]+\>(<img[^>]+\>)<\/a>/i', "$1", $content);
			
			// youtube
			$content = preg_replace('/<iframe.*?(?!src).*?src=[\'|"](https?:)?(\/\/)?(www\.)?(youtu\.be\/|youtube(-nocookie)?\.[a-z]{2,4}(?:\/embed\/|\/v\/|\/watch\?.*?v=))([\w\-]{10,12})([\?|&]?.*?)?[\'|"][^>]+><\/iframe>/', '<a href="https://www.youtube.com/watch?v=$6"><img src="' . get_site_url() . '/android_json/youtube/$6" /></a><br/>', $content);
			
			$content = preg_replace('#"#', '\"', $content);
			$content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
			$content = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $content);
			$content = preg_replace('#	#is', '', $content);
			
			$titre = preg_replace('#"#', '\"', html_entity_decode(get_the_title($id)));
			
			$data = get_terms('category', array('hide_empty' => 1));
			$image = $UtilsAndroidAppAmauri->getImage($id);
			
			// similar articles
			$args = array(
						'post__not_in' => array( $id ),
						'numberposts' => 3,
						'offset' => 0,
						'category' => $catID,
						'orderby' => 'post_date',
						'order' => 'DESC',
						'post_type' => 'post',
						'post_status' => 'publish',
						'suppress_filters' => true
					);
	
			$similaire = '';
			$recent_posts = wp_get_recent_posts( $args, OBJECT );
			foreach($recent_posts as $p) {
				$title_similaire = preg_replace('#"#', '\"', html_entity_decode($p->post_title));
				$similaire .= '{"postID":"' . $p->ID . '","titre":"' . $title_similaire . '", "image":"' .$UtilsAndroidAppAmauri->getImage($p->ID). '"},';
			}
			$similaire = trim($similaire, ',');
			
			$commentaire = '';
			$commentisopen = '0';
			if (comments_open( $id )) {
				$commentisopen = '1';
				$nbcomment = get_comments_number($id);
				$comments = get_comments('number=2&post_id=' . $id);
				foreach($comments as $comment) {
					if ($comment->comment_approved == 1) {
						$commentaire .= '{"avatar":"'.$UtilsAndroidAppAmauri->get_gravatar_url($comment->comment_author_email).'","name":"' . strtoupper($comment->comment_author) . '","date": "Il y a '. human_time_diff( strtotime($comment->comment_date), current_time('timestamp') ) . '","content":"' . $UtilsAndroidAppAmauri->sanitize(wp_trim_words(wp_strip_all_tags($comment->comment_content), 40)) . '"},';
					}
				}
			}
			$commentaire = trim($commentaire, ',');
			
			$json = '{"cat":"'.$cat.'","info":"'.$info.'","permalink":"'. get_permalink( $id ).'","titre":"'.$titre.'","texte":"'.$content.'","image":"'.$image.'","id":"'.$id.'","similaire":{"data":['.$similaire.']},"commentaire_count":"'.$nbcomment.'","commentaire":{"data":['.$commentaire.']},"commentopen":"'.$commentisopen.'"}';
			return '{"data":['.trim($json, ',').']}';
		}
		
		public function comment($id, $offset = 0) {

			require(sprintf("%s/Utils.php", dirname(__FILE__)));
			$commentaire = '';
			$commentisopen = '0';
			if (comments_open( $id )) {
				$commentisopen = '1';
				$nbcomment = 0;
				$comments = get_comments('status=approve&offset=' . $offset . '&number=10&post_id=' . $id);
				foreach($comments as $comment) {
					if ($comment->comment_approved == 1) {
						$nbcomment++;
						
						$isChild = 'nope';
						
						$titre = strtoupper($comment->comment_author);
						$texte = $UtilsAndroidAppAmauri->sanitize($comment->comment_content);
						
						// is a child
						if ($comment->comment_parent != 0) {
							$parent = get_comment( $comment->comment_parent );
							$titre .= ' @ ' . strtoupper($parent->comment_author);
							$texte = '<i>' . $UtilsAndroidAppAmauri->sanitize(wp_trim_words(wp_strip_all_tags($parent->comment_content), 40)) . '</i><br/><br/>' . $texte;
						}
						
						$commentaire .= '{"sticky":"'.$isChild.'","id":"0","image":"'.$UtilsAndroidAppAmauri->get_gravatar_url($comment->comment_author_email).'","titre":"' . $titre . '","cat": "Il y a '. human_time_diff( strtotime($comment->comment_date), current_time('timestamp') ) . '","texte":"' . $texte . '"},';
					}
				}
			}

			return '{"data":['.trim($commentaire, ',').']}';
		}
		
		public function youtube($video) {
			$image = imagecreatefromjpeg( "http://img.youtube.com/vi/$video/hqdefault.jpg" );
			
			$cleft = 0;
			$ctop = 45;
			$canvas = imagecreatetruecolor(960, 540);
			imagecopyresized ($canvas, $image, 0, 0, $cleft, $ctop, 960, 720, 480, 360);
			$image = $canvas;
		
			$imageWidth = imagesx($image);
			$imageHeight = imagesy($image);

			$play_icon = plugin_dir_path( __FILE__ ) . "images/play-hq.png";
			$logoImage = imagecreatefrompng( $play_icon );

			imagealphablending($logoImage, true);
			$logoWidth 		= imagesx($logoImage);
			$logoHeight 	= imagesy($logoImage);

			$left = round($imageWidth / 2) - round($logoWidth);
			$top = round($imageHeight / 2) - round($logoHeight);

			$blackOpacity = imagecolorallocatealpha($image, 0, 0, 0, 70);
			imagefilledrectangle($image, 0, 0, $imageWidth, $imageHeight, $blackOpacity);
			
			imagecopyresized( $image, $logoImage, $left, $top, 0, 0, $logoWidth * 2, $logoHeight * 2, $logoWidth, $logoHeight);
			
			header('HTTP/1.1 200 OK');
			header('Content-Type: image/png');
			imagepng( $image, NULL, 9);
			imagedestroy($image);
			exit(0);
		}
		
		public function category($offset = 0) {

			$count_posts = wp_count_posts();
			$json = '{"name":"_NB_","id":"", "nb":"'.$count_posts->publish.'"},';
			$divider = true;
			
			$categories = get_terms('category', array('hide_empty' => true));
			$data = array();
			
			require(sprintf("%s/Utils.php", dirname(__FILE__)));
			$UtilsAndroidAppAmauri->sort_terms_hierarchicaly($categories, $data);
			
			foreach($data as $d) {
				$nb = '';
				if($d->count > 0) {$nb = $d->count;}
				if ($divider) {$json .= '{"name":"_divider_","id":"0", "nb":"0"},';}
				$json .= '{"name":"'.trim($d->name).'","id":"'.$d->term_id.'", "nb":"'.($nb).'"},';
				
				$divider = false;
				foreach($d->children as $dd) {
					$divider = true;
					$nb = '';
					if($dd->count > 0) {$nb = $dd->count;}
					$json .= '{"name":" '.trim($dd->name).'","id":"'.$dd->term_id.'", "nb":"'.($nb).'"},';
				}
			}

			$json .= '{"name":"_divider_","id":"0", "nb":"0"},';
			
			$pages = get_pages(array('exclude' => get_option('androidappamauri_exclude'))); 
			foreach ( $pages as $page ) {
				$json .= '{"name":"'.trim($page->post_title).'","id":"'.$page->ID.'", "nb":"page"},';
			}
			
			return '{"config":[{"similaire":"'.get_option('androidappamauri_similaire', '1').'","share":"'.get_option('androidappamauri_share', '1').'","commentaire":"'.get_option('androidappamauri_commentaire', '1').'","projectid":"'.get_option('androidappamauri_project').'","ga":"'.get_option('androidappamauri_ga').'","admob_float":"'.get_option('androidappamauri_admob_float').'","admob_splash":"'.get_option('androidappamauri_admob_splash').'","admob_t":"'.get_option('androidappamauri_admob_t').'","admob_b":"'.get_option('androidappamauri_admob_b').'"}],"data":['.trim($json, ',').']}';
		}
		
		public function recent($offset = 0, $isCat = 0, $isSearch = false) {
			
			$json = '';
			$search = '';
			if($isSearch) {
				$search = $isSearch;
			}
			
			$stickys = get_option( 'sticky_posts' );
			$catIsSticky = false;
			
			if (isset($stickys[0]) && !$isSearch) {
				$catIsSticky = true;
			}
			
			if ($catIsSticky) {
				
				$firstSticky = wp_get_recent_posts(array('posts_per_page' => 1, 'post__in'  => $stickys, 'ignore_sticky_posts' => 1, 's' => $search, 'category' => $isCat,'post_type' => 'post', 'post_status' => 'publish','numberposts' => 1,'offset'=>0));
				if ($offset == 0) {
					$sticky = $firstSticky;
					$normal = wp_get_recent_posts(array('post__not_in' => array( $firstSticky[0]['ID'] ), 's' => $search, 'category' => $isCat,'post_type' => 'post', 'post_status' => 'publish','numberposts' => 9,'offset'=>$offset));
				} else {
					$sticky = array();
					$normal = wp_get_recent_posts(array('post__not_in' => array( $firstSticky[0]['ID'] ), 's' => $search, 'category' => $isCat,'post_type' => 'post', 'post_status' => 'publish','numberposts' => 10,'offset'=>$offset-1));
				}
				
				$data = array_merge($sticky, $normal);
			} else {
				$data = wp_get_recent_posts(array('s' => $search, 'category' => $isCat,'post_type' => 'post', 'post_status' => 'publish','numberposts' => 10,'offset'=>$offset));
			}
			
			foreach($data as $d) {
				
				if (in_array($d['ID'], $stickys)) {
					$hessticky = '1';
				} else {
					$hessticky = '0';
				}
				
				$categories = get_the_category($d['ID']);
				$separator = ' ';
				$output = '';
				$cat = '';
				if($categories){
					foreach($categories as $category) {
						if ($output ==  '') {
							$output .= $category->cat_name.$separator;
						}
					}
					$cat = trim($output, $separator);
				}
				$excerpt = preg_replace('#"#', '\"', html_entity_decode(strip_tags(str_replace(array("\r", "\n"),"", apply_filters('the_excerpt', get_post_field('post_excerpt', $d['ID']))))));
				
				$content_post = get_post($d['ID']);
				
				require(sprintf("%s/Utils.php", dirname(__FILE__)));
				$excerpt = $UtilsAndroidAppAmauri->humanTime(current_time('timestamp') - strtotime($content_post->post_date));
				$image = $UtilsAndroidAppAmauri->getImage($d['ID']);
				
				$titre = preg_replace('#"#', '\"', $d['post_title']);
				
				$json .= '{"sticky":"'.$hessticky.'", "cat":"'.$cat.'","titre":"'.$titre.'","texte":"'.$excerpt.'","image":"'.$image.'","id":"'.$d['ID'].'"},';
			}
			return '{"data":['.trim($json, ',').']}';
		}
	}
}

if(class_exists('APIAndroidAppAmauri'))
{
    $APIAndroidAppAmauri = new APIAndroidAppAmauri();
}