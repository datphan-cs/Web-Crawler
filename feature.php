<?php
function prototype ($option, &$file_type, &$tag_name, &$attr){
	if ($option == 'img'){
		$file_type = ['.jpg', '.png', '.gif'];
		$tag_name = 'img';
		$attr = 'src';
	}
	else if ($option == 'pdf'){
		$file_type = ['.pdf'];
		$tag_name = 'a';
		$attr = 'href';
	}
	else{
		$file_type = ['.tar.gz', '.zip', '.rar', '.7z'];
		$tag_name = 'a';
		$attr = 'href';
	}
}

// Finish books_toscrape: get data from all page
function crawl_toscrape(&$url, $option, &$link_array){
	$http_client = new \GuzzleHttp\Client();

	$baseurl = $_POST['url'];
	$url = $baseurl;
	$option = $_POST['radio'];
	$file_type = [''];
	$tag_name = $place = '';

	prototype($option, $file_type, $tag_name, $place);
	$page = 1;
	while (True){
		// echo "Page number: $page <br>";
		$response = $http_client->get($url);
		
		$html_string = (string) $response->getBody();
		//add this line to suppress any warnings
		libxml_use_internal_errors(true);
		
		$doc = new DOMDocument();
		$doc->loadHTML($html_string);
		
		$file_crawled = new DOMXPath($doc);

		foreach($file_crawled->query('//ol[@class="row"]//li//article//div//a/img') as $image) {
			$data = $image->getAttribute($place);
			if (strpos($data,'//') === False){
				$data = $baseurl . $data;
			}
			if (strpos($data,"../") !== False){
				$data = str_replace("../","",$data);
			}
			array_push($link_array,$data);

		}
		// [ ]Cannot identified if next_page exist or not
		$next_page = $file_crawled->query('//div//ul//li[@class="next"]/a');
		if ($next_page->length > 0){
			$next_data = $next_page[0]->getAttribute('href');
			if (strpos($next_data, 'catalogue/') === False){
				$next_data = 'catalogue/' . $next_data;
			}
			$url = $baseurl . $next_data;
			$page += 1;
		}
		else if ($page >=5){
			break;
		}

	}
}
function crawl_pdfdrive(&$url, $option, &$link_array){
	$http_client = new \GuzzleHttp\Client();

	$baseurl = $_POST['url'];
	$url = $baseurl;
	$option = $_POST['radio'];
	$file_type = [''];
	$tag_name = $place = '';

	prototype($option, $file_type, $tag_name, $place);
	$page = 1;
	while (True){
		// echo "Page number: $page <br>";
		$response = $http_client->get($url);
		
		$html_string = (string) $response->getBody();
		//add this line to suppress any warnings
		libxml_use_internal_errors(true);
		
		$doc = new DOMDocument();
		$doc->loadHTML($html_string);
		
		$file_crawled = new DOMXPath($doc);

		foreach($file_crawled->query('//ol[@class="row"]//li//article//div//a/img') as $image) {
			$data = $image->getAttribute($place);
			if (strpos($data,'//') === False){
				$data = $baseurl . $data;
			}
			if (strpos($data,"../") !== False){
				$data = str_replace("../","",$data);
			}
			array_push($link_array,$data);

		}
		// [ ]Cannot identified if next_page exist or not
		$next_page = $file_crawled->query('//div//ul//li[@class="next"]/a');
		if ($next_page->length > 0){
			$next_data = $next_page[0]->getAttribute('href');
			if (strpos($next_data, 'catalogue/') === False){
				$next_data = 'catalogue/' . $next_data;
			}
			$url = $baseurl . $next_data;
			$page += 1;
		}
		else if ($page >=5){
			break;
		}

	}
}
?>