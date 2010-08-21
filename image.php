<?php

	$headers = array(
		array(
			'img' => 'images/headers/header_sign.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/4485964682/',
			'width' => 950,
			'height' => 250,
		),
		array(
			'img' => 'images/headers/header_duckies.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/4710923546/',
			'width' => 950,
			'height' => 250,
		),
		array(
			'img' => 'images/headers/header_pier.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/4485317853/',
			'width' => 950,
			'height' => 250,
		),
		array(
			'img' => 'images/headers/header_lake.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/3576628927/',
			'width' => 950,
			'height' => 250,
		),
	);
	
	// pick a header
	$header = $headers[ mt_rand( 0, count( $headers ) - 1 ) ];
	
	echo json_encode( $header );

?>