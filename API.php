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
			if($categories){
				foreach($categories as $category) {
					$output .= $category->cat_name.$separator;
				}
				$cat = trim($output, $separator);
			}
			
			require(sprintf("%s/Utils.php", dirname(__FILE__)));
			$info = $UtilsAndroidAppAmauri->humanTime(current_time('timestamp') - strtotime($content_post->post_date));
			
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			$content = preg_replace('#"#', '\"', html_entity_decode(str_replace(array("\r", "\n"),"", $content)));
			
			$data = get_terms('category', array('hide_empty' => 1));
			$image = $UtilsAndroidAppAmauri->getImage($id);
			$json = '{"cat":"'.$cat.'","info":"'.$info.'","permalink":"'. get_permalink( $id ).'","titre":"'.html_entity_decode(get_the_title($id)).'","texte":"'.$content.'","image":"'.$image.'","id":"'.$id.'"}';
			return '{"data":['.trim($json, ',').']}';
		}
		
		public function category($offset = 0) {
			
			$count_posts = wp_count_posts();
			$json .= '{"name":"_NB_","id":"", "nb":"'.$count_posts->publish.'"},';
			
			$categories = get_terms('category', array('hide_empty' => true));
			$data = array();
			
			require(sprintf("%s/Utils.php", dirname(__FILE__)));
			$UtilsAndroidAppAmauri->sort_terms_hierarchicaly($categories, $data);
			
			foreach($data as $d) {
				$nb = '';
				if($d->count > 0) {$nb = $d->count;}
				$json .= '{"name":"_divider_","id":"0", "nb":"0"},';
				$json .= '{"name":"'.trim($d->name).'","id":"'.$d->term_id.'", "nb":"'.($nb).'"},';
				
				foreach($d->children as $dd) {
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
			
			return '{"data":['.trim($json, ',').']}';
		}
		
		public function recent($offset = 0, $isCat = 0, $isSearch = false) {
			
			if($isSearch) {
				$search = $isSearch;
			}

			$data = wp_get_recent_posts(array('s' => $search, 'category' => $isCat,'post_type' => 'post', 'post_status' => 'publish','numberposts' => 10,'offset'=>$offset));
			foreach($data as $d) {
				$categories = get_the_category($d['ID']);
				$separator = ' ';
				$output = '';
				$cat = '';
				if($categories){
					foreach($categories as $category) {
						$output .= $category->cat_name.$separator;
					}
					$cat = trim($output, $separator);
				}
				$excerpt = preg_replace('#"#', '\"', html_entity_decode(strip_tags(str_replace(array("\r", "\n"),"", apply_filters('the_excerpt', get_post_field('post_excerpt', $d['ID']))))));
				
				$content_post = get_post($d['ID']);
				
				require(sprintf("%s/Utils.php", dirname(__FILE__)));
				$excerpt = $UtilsAndroidAppAmauri->humanTime(current_time('timestamp') - strtotime($content_post->post_date));
				$image = $UtilsAndroidAppAmauri->getImage($d['ID']);
				
				$json .= '{"cat":"'.$cat.'","titre":"'.$d['post_title'].'","texte":"'.$excerpt.'","image":"'.$image.'","id":"'.$d['ID'].'"},';
			}
			return '{"data":['.trim($json, ',').']}';
		}
	}
}

if(class_exists('APIAndroidAppAmauri'))
{
    $APIAndroidAppAmauri = new APIAndroidAppAmauri();
}