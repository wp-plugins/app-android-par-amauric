<?php

if(!class_exists('RewriteAndroidAppAmauri'))
{
    class RewriteAndroidAppAmauri {
			
		public function json() {
			
			require(sprintf("%s/Push.php", dirname(__FILE__)));
			require(sprintf("%s/API.php", dirname(__FILE__)));
		
			if ( preg_match( '#^/android_json/(.*)$#', $_SERVER['REQUEST_URI'], $match ) ) {
				$request = explode( '/', $match[1] );
				$return = false;
				
				if (!isset($request[1])) {$request[1] = '';}
				if (!isset($request[2])) {$request[2] = '';}
				
				switch ( $request[0] ) {
					case 'category':
						$return = true;
						$output = $APIAndroidAppAmauri->category($request[1]);
						break;
					case 'bycat':
						$return = true;
						if ($request[1] == '') {$request[1] = 0;}
						$output = $APIAndroidAppAmauri->recent($request[2], $request[1]);
						break;
					case 'recent':
						$return = true;
						if ($request[2] == '') {$request[2] = 0;}
						$output = $APIAndroidAppAmauri->recent($request[1], $request[2]);
						break;
					case 'search':
						$return = true;
						if ($request[2] == '') {$request[2] = 0;}
						$output = $APIAndroidAppAmauri->recent($request[2], 0, $request[1]);
						break;
					case 'comment':
						$return = true;
						if ($request[2] == '') {$request[2] = 0;}
						$output = $APIAndroidAppAmauri->comment($request[1], $request[2]);
						break;
					case 'read':
						$return = true;
						$output = $APIAndroidAppAmauri->read($request[1]);
						break;
					case 'youtube':
						$return = true;
						$output = $APIAndroidAppAmauri->youtube($request[1]);
						break;
					case 'register':
						$return = true;
						$output = $PushAndroidAppAmauri->register();
						break;
					case 'unregister':
						$return = true;
						$output = $PushAndroidAppAmauri->unregister();
						break;
				}
				if ( $return ) {
					header('HTTP/1.1 200 OK');
					header('Content-Type: application/json; charset=utf-8');
					echo $output;
					exit(0);
				}
			}
		}
    }
}

if(class_exists('RewriteAndroidAppAmauri'))
{
    $RewriteAndroidAppAmauri = new RewriteAndroidAppAmauri();
}