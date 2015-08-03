<?php

if(!class_exists('UtilsAndroidAppAmauri'))
{
    class UtilsAndroidAppAmauri {
		
		public function get_gravatar_url( $email ) {
			if ($email != '') {
				$html = get_avatar($email, 200, get_option( 'avatar_default', 'mystery' ));
				if ($html != FALSE) {
					preg_match("/src='(.*?)'/i", $html, $matches);
					return preg_replace('/&#038;/', '&', $matches[1]);
				}
			}
			return '';
		}

		public function sanitize($content) {
			$content = str_replace(']]>', ']]&gt;', $content);
			$content = html_entity_decode(str_replace(array("\r", "\n"),"", $content));
			$content = preg_replace('#"#', '\"', $content);
			$content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
			$content = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $content);
			$content = preg_replace('#	#is', '', $content);
			
			return $content;
		}

		public function getImage($id) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
			if ($image[0] != '') {
				return $image[0];
			}
			
			$images = get_attached_media('image', $id);
			foreach($images as $img) {
				$i = wp_get_attachment_image_src($img->ID, 'medium');
				if ($i[0] != '') {
					return $i[0];
				}
			}
		}
		
		public function humanTime($s) {
			$m = round($s / 60);
			$h = round($s / 3600);
			$d = round($s / 86400);
			if ($m > 1) {
				if ($h > 1) {
					if ($d > 1) {
						return (int)$d.' jours';
					} else {
						return (int)$h.' heures';
					}
				} else {
					return (int)$m.' minutes';
				}
			} else {
				return (int)$s.' secondes';
			}
		}
		
		public function sort_terms_hierarchicaly(Array &$cats, Array &$into, $parentId = 0) {
			foreach ($cats as $i => $cat) {
				if ($cat->parent == $parentId) {
					$into[$cat->term_id] = $cat;
					unset($cats[$i]);
				}
			}

			foreach ($into as $topCat) {
				$topCat->children = array();
				$this->sort_terms_hierarchicaly($cats, $topCat->children, $topCat->term_id);
			}
		}
    }
}

if(class_exists('UtilsAndroidAppAmauri'))
{
    $UtilsAndroidAppAmauri = new UtilsAndroidAppAmauri();
}