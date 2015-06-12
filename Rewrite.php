<?php

if(!class_exists('RewriteAndroidAppAmauri'))
{
    class RewriteAndroidAppAmauri {
			
		public function json() {
			
			require(sprintf("%s/Push.php", dirname(__FILE__)));
			require(sprintf("%s/API.php", dirname(__FILE__)));
		
			if ( preg_match( '#^/android_json/(.*)$#', $_SERVER['REQUEST_URI'], $match ) ) {
				$request = explode( '/', $match[1] );
				$output = false;
				switch ( $request[0] ) {
					case 'category':
						$output = $APIAndroidAppAmauri->category($request[1]);
						break;
					case 'bycat':
						if ($request[1] == '') {$request[1] = 0;}
						$output = $APIAndroidAppAmauri->recent($request[2], $request[1]);
						break;
					case 'recent':
						if ($request[2] == '') {$request[2] = 0;}
						$output = $APIAndroidAppAmauri->recent($request[1], $request[2]);
						break;
					case 'search':
						if ($request[2] == '') {$request[2] = 0;}
						$output = $APIAndroidAppAmauri->recent($request[2], 0, $request[1]);
						break;
					case 'read':
						$output = $APIAndroidAppAmauri->read($request[1]);
						break;
					case 'register':
						$output = $PushAndroidAppAmauri->register();
						break;
					case 'unregister':
						$output = $PushAndroidAppAmauri->unregister();
						break;
				}
				if ( $output ) {
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