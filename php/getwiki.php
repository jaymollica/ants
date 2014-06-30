<?php

	if($_REQUEST['ant'] && $_REQUEST['depth']) {
		$antReq = $_REQUEST['ant'];
		$depth = $_REQUEST['depth'];
	}
	else {
		exit;
	}
	
	$urlRoot = 'http://www.antweb.org/api/v2';

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

	$imagesUrl = $urlRoot . '/?' . $rank . '=' . $antReq;



	$content = file_get_contents($imagesUrl); 
	$ants = json_decode($content, TRUE);


	foreach($ants['specimens'] AS $ant) {
		if($ant['images']) {
			$i = 1;
			$imgs = $ant['images'];

			print '<div class="specimen_box">';
			print '<h3>' . ucfirst($ant['meta']['subfamily']) . ' ' . ucfirst($ant['meta']['genus']) . ' ' . $ant['meta']['species'] . '</h3>';
			print '<p class="taxonomy">' . $ant['meta']['code'] . '</p>';
			
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