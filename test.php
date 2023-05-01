<?php 
	///////////////// Form to get URL + File Extension /////////////////
	$extracted_link = [];
?>
<?php
///////////////// Crawl data from specified URL /////////////////
if (isset($_POST['url'])){
	if (isset($_POST['down_all'])){
		$extracted_link = unserialize($_POST['data']);
		foreach ($extracted_link as $link){
			$file_name = basename($link);
			$file_path = "download/";
			$file_name = $file_path . $file_name;
			if (file_put_contents($file_name, file_get_contents($link))){
				echo "File downloaded successfully";
			}
			else{
				echo "File downloading failed.";
			}
		}

	
	}
	else{
		require 'vendor/autoload.php';
		$http_client = new \GuzzleHttp\Client();
		$baseurl = $_POST['url'];
		$url = $baseurl;
		$option = $_POST['radio'];
		if ($option == 'img'){
			$file_type = ['.jpg', '.png', '.gif'];
			$tag_name = 'img';
			$place = 'src';
		}
		else if ($option == 'pdf'){
			$file_type = ['.pdf'];
			$tag_name = 'a';
			$place = 'href';
		}
		else{
			$file_type = ['.tar.gz', '.zip', '.rar', '.7z'];
			$tag_name = 'a';
			$place = 'href';
		}
		// $page_number = 1;
		while (True){
			// echo "Page Number: $page_number <br>";
			$response = $http_client->get($url);
			
			$html_string = (string) $response->getBody();
			//add this line to suppress any warnings
			libxml_use_internal_errors(true);
			
			$doc = new DOMDocument();
			$doc->loadHTML($html_string);
			
			$possible_tag = $doc->getElementsByTagName($tag_name);
	
			foreach($possible_tag as $tag) {
					$data = $tag->getAttribute($place);
					foreach ($file_type as $extension)
					if (strpos($data,$extension) !== False){
						if (strpos($data,'//') === False){
							$data = $baseurl . $data;
						}
						// echo "<a href = '$data'> $data <a> <br/>";
						array_push($extracted_link,$data);
					}
				}
			// [ ]Cannot identified if next_page exist or not
			// $next_page = $doc->getElementsByTagName('a');
			// echo "<br>";
			// echo "<br>";
			// if (empty($next_page) === False){
			// 	$next_data = $next_page[0]->getAttribute('href');
			// 	if (strpos($next_data, 'catalogue/') === False){
			// 		$next_data = 'catalogue/' . $next_data;
			// 	}
			// 	$url = $baseurl . $next_data;
			// 	$page_number += 1;
			// }
			// else{
			// 	break;
			// }
			// $extracted_link = json_encode($extracted_link);

	   ;
			break;
		}
		///////////////// "Download" button to download all files /////////////////
	
	}	
	
}
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="form" method="POST">
  <input type="text" name="url" id="url" placeholder = "Enter the link you want to scrape here:">
  <br>
  <br>
  <fieldset>
    <div>
      <input type="radio" id="img" name="radio" value = "img" checked>
      <label for="img">Image File (.png, .jpg, .gif)</label>
    </div>

    <div>
      <input type="radio" id="pdf" name="radio" value = "pdf">
      <label for="pdf">PDF</label>
    </div>

    <div>
      <input type="radio" id="zip" name="radio" value = "zip">
      <label for="zip">Compressed File (.tar.gz, .zip, .rar, .7z) </label>
    </div>
	<br>
	</fieldset>
  <br/>
  <input type="submit" name="my_form_submit_button" value="CRAWL"/>
  <input type='hidden' name='data' value="<?php echo htmlentities(serialize($extracted_link)); ?>" />
  <input type="submit" id ="down_btn" name="down_all" value="DOWNLOAD ALL"/>
</form>

<?php	
	// $extracted_link = unserialize($extracted_link);
	if (empty($extracted_link) === True){
		echo "THIS WEBSITE MAY NOT HAVE YOUR DESIRED FILE TYPE";
	}
	else{
		foreach ($extracted_link as $link){
			echo "<a href = $link>$link </a> <br>";
		}
	}

?>


