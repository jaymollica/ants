<?php

	if($_REQUEST['ant']) {
		$antReq = $_REQUEST['ant'];
	}
	else {
		print '<p>No images available.</p>';
		exit;
	}

	$family = 'ant';
	$subfamilia = 'Leptanillinae';


	$imagesUrl = 'http://en.wikipedia.org/w/api.php?action=query&format=json&titles=' . $antReq . '&prop=images';

	$content = file_get_contents($imagesUrl); 
	$array = json_decode($content, TRUE);

	$page_images = array();

	$i = 0;
	foreach($array['query']['pages'] AS $page) {

		if($page['images']) {

			foreach($page['images'] AS $img) {
				$img['title'] = preg_replace('/File:/i','',$img['title']);
				$page_images[$i] = urlencode($img['title']);
				$i++;
			}

		}
	}

	$n = 0;
	foreach($page_images AS $pi) {

		$imgUrl = 'http://en.wikipedia.org/w/api.php?action=query&format=json&titles=Image:' . $pi . '&prop=imageinfo&iiprop=url';

		$imgContent = file_get_contents($imgUrl);

		$array = json_decode($imgContent, TRUE);

		foreach($array['query']['pages'] AS $page) {

			if($page['imageinfo']) {
				foreach($page['imageinfo'] AS $info) {
					if(preg_match('/.webm/i',$info['url'])) {
						print '<a href="' . $info['url'] . '"><video width="400px" ><source src="' . $info['url'] . '" type="video/webm" /></video></a>';
						$n++;
					}
					elseif(!preg_match('/.svg/i',$info['url'])) {
						print '<a href="' . $info['descriptionurl'] . '"><img src="' . $info['url'] . '" width="400px" /></a>';
						$n++;
					}
				}
			}
		}
	}

	if($n == 0) {
		print '<p>No images available.</p>';
	}

?>