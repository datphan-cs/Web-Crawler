<?php

function prototype ($option, &$file_type, &$tag_name, &$attr){
	if ($option == 'img'){
		$file_type = ['.jpg', '.png', '.gif'];
		$tag_name = 'img';
		$attr = 'src';
	}
	else if ($option == 'doc'){
		$file_type = ['.pdf', '.doc', '.docx'];
		$tag_name = 'a';
		$attr = 'href';
	}
	else if ($option == 'csv'){
		$file_type = ['.csv', '.xlsx'];
		$tag_name = 'a';
		$attr = 'href';
	}
	else if ($option == 'zip'){
		$file_type = ['.tar.gz', '.zip', '.rar', '.7z'];
		$tag_name = 'a';
		$attr = 'href';
	}
	else if ($option == 'sound'){
		$file_type = ['.mp3', '.wav'];
		$tag_name = 'a';
		$attr = 'href';
	}
}

// Finish books_toscrape: get data from all page
function crawl_toscrape(&$url, $option, &$link_array){
	$http_client = new \GuzzleHttp\Client();

	$baseurl = "http://books.toscrape.com/";
	// $url = $baseurl;
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
		if (count($link_array) >=50){
			break;
		}
	}
}
function crawl_pdfdrive(&$url, $option, &$link_array){
	// $http_client = new \GuzzleHttp\Client();

	// $baseurl = "https://www.pdfdrive.com";
	// $url = $baseurl;
	// $option = $_POST['radio'];
	// $file_type = [''];
	// $tag_name = $place = '';

	// $mid_page_array = [];
	// prototype($option, $file_type, $tag_name, $place);
	// $page = 1;
	// 	// echo "Page number: $page <br>";
	// 	$response = $http_client->get($url);
		
	// 	$html_string = (string) $response->getBody();
	// 	//add this line to suppress any warnings
	// 	libxml_use_internal_errors(true);
		
	// 	$doc = new DOMDocument();
	// 	$doc->loadHTML($html_string);
		
	// 	$file_crawled = new DOMXPath($doc);

	// 	foreach($file_crawled->query('//div[@class="file-right"]/a') as $mid_page) {
	// 		$data = $mid_page->getAttribute($place);
	// 		if (strpos($data,'//') === False){
	// 			$data = $baseurl . $data;
	// 		}
	// 		if (strpos($data,"../") !== False){
	// 			$data = str_replace("../","",$data);
	// 		}
	// 		$len = strlen($data);
	// 		// for ($i = $len-1; $i>=0; $i--){
	// 		// 	if ($data[$i] == '-'){
	// 		// 		$data[$i+1] = 'd';
	// 		// 	}
	// 		// }
	// 		echo "New path: $data <br>";
	// 		array_push($mid_page_array,$data);
	// 	}
	// 	// foreach($mid_page_array as $mid_page){
	// 	// 	$response = $http_client->get($mid_page);
	// 	// 	$html_string = (string) $response->getBody();
	// 	// 	//add this line to suppress any warnings
	// 	// 	libxml_use_internal_errors(true);
			
	// 	// 	$doc = new DOMDocument();
	// 	// 	$doc->loadHTML($html_string);
			
	// 	// 	$file_crawled = new DOMXPath($doc);

	// 	// }


	// 	$client = Client::getInstance();
	// 	$client->getEngine()->setPath('R:\\xampp\\htdocs\\php-tut\\web_crawler\\bin\\phantomjs.exe');
	// 	/** 
	// 	 * @see JonnyW\PhantomJs\Http\Request
	// 	 **/
	// 	$request = $client->getMessageFactory()->createRequest($mid_page_array[0], 'GET');
	
	// 	/** 
	// 	 * @see JonnyW\PhantomJs\Http\Response 
	// 	 **/
	// 	$response = $client->getMessageFactory()->createResponse();
	
	// 	// Send the request
	// 	$client->send($request, $response);
	
	// 	if($response->getStatus() === 200) {
	
	// 		// Dump the requested page content
	// 		echo $response->getContent();
	// 	}
	
}

function crawl_catalog(&$url, $option, &$link_array){
	$http_client = new \GuzzleHttp\Client();

	$baseurl = "https://catalog.data.gov";
	// $url = $baseurl;
	$option = $_POST['radio'];
	$file_type = [''];
	$tag_name = $place = '';

	prototype($option, $file_type, $tag_name, $place);
	$page = 1;
	while (count($link_array) <=10 and $page <=10) {
		// echo "Page number: $page <br>";
		$response = $http_client->get($url);
		
		$html_string = (string) $response->getBody();
		//add this line to suppress any warnings
		libxml_use_internal_errors(true);
		
		$doc = new DOMDocument();
		$doc->loadHTML($html_string);
		
		$file_crawled = new DOMXPath($doc);

		foreach($file_crawled->query('//ul[@class="dataset-resources unstyled"]//li/a') as $link) {
			$data = $link->getAttribute($place);
			foreach($file_type as $extension){
				if (strpos($data, $extension) !== False){
					array_push($link_array,$data);
				}
			}
		}
		$next_page = $file_crawled->query('//div//ul[@class="pagination"]//li/a');
		if ($next_page->length > 0){
			$number_of_page = $next_page->length;
			$next_data = $next_page[$number_of_page-1]->getAttribute('href');
			$url = $baseurl . $next_data;
			$page += 1;
		}
	}
}
?>