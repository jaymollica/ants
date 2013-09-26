<?php

	if($_REQUEST['ant'] && $_REQUEST['depth']) {
		$antReq = $_REQUEST['ant'];
		$depth = $_REQUEST['depth'];
	}
	else {
		exit;
	}

	if(preg_match('/local/i', $_SERVER['HTTP_HOST'])) {
	  $urlRoot = 'http://antweb.local/api';
	}
	elseif(preg_match('/antweb-stg/i', $_SERVER['HTTP_HOST'])) {
	  $urlRoot = 'http://10.2.22.83/api/';
	}
	else {
		$urlRoot = 'http://www.antweb.org/api';
	}
	

	//we infer rank from depth of name
	if($depth == 1) {
		$rank = 'subfamily';
	}
	elseif($depth == 2) {
		$rank = 'genus';
	}
	elseif($depth == 3) {
		$rank = 'species';
	}

	$imagesUrl = $urlRoot . '/?rank=' . $rank . '&name=' . $antReq;



	$content = file_get_contents($imagesUrl); 
	$ants = json_decode($content, TRUE);


	foreach($ants AS $ant) {
		if($ant['images']) {
			$i = 1;
			$imgs = $ant['images'];

			print '<div class="specimen_box">';
			print '<h3>' . $ant['meta']['code'] . '</h3>';
			print '<p class="taxonomy">' . ucfirst($ant['meta']['subfamily']) . ' ' . ucfirst($ant['meta']['genus']) . ' ' . $ant['meta']['species'] . '</p>';
			
			foreach($imgs AS $img) {
				foreach($img['shot_types'] AS $type) {

					foreach($type AS $t) {

						foreach($t AS $url) {

							if(preg_match('/high/i',$url)) {
								$href = $url;
							}

							if(preg_match('/low/i',$url)) {
								$src = $url;
							}
						}

						print '<a href="'. $href . '" target="_blank"><img src="'. $src . '" /></a>';

					}
				}
			}

			print '</div>';
		}
		
	}

	if(!$i) {
		print '<p>url: ' . $imagesUrl . '</p>';
		print '<p>No images available for <i>' . $antReq . '</i>.</p>';
	}

?>