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
		array(
			'img' => 'images/headers/header_shoreline.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/4485346693/',
			'width' => 950,
			'height' => 250,
		),
		array(
			'img' => 'images/headers/header_seagull.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/4485971464/',
			'width' => 950,
			'height' => 250,
		),
		array(
			'img' => 'images/headers/header_waves.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/4485971102/',
			'width' => 950,
			'height' => 250,
		),
		array(
			'img' => 'images/headers/header_pier_waves.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/4485970534/',
			'width' => 950,
			'height' => 250,
		),
		array(
			'img' => 'images/headers/header_shoreline2.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/4485969242/',
			'width' => 950,
			'height' => 250,
		),
		array(
			'img' => 'images/headers/header_boy-in-the-waves.jpg',
			'flickr' => 'http://www.flickr.com/photos/mellertime/4485967894/',
			'width' => 950,
			'height' => 250,
		),
	);
	
	// pick a header
	$header = $headers[ mt_rand( 0, count( $headers ) - 1 ) ];
	
	echo json_encode( $header );

?>