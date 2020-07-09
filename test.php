#!/usr/bin/php
<?PHP

function get_content_from_registry($url, $current=0) {
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,1);
	curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	$content = curl_exec($ch);
	if( $content === false && $current < 5 ) {
		sleep(1);
		$current++;
		$content = get_content_from_registry( $url, $current );
	} 
	curl_close($ch);
	return $content;
}

echo print_r(json_decode(get_content_from_registry( "https://registry.hub.docker.com/v2/repositories/mobiusnine/foldingathome" )));

?>