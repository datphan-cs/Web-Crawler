<?php 
	///////////////// Array to store all links to data /////////////////
	$extracted_link = [];
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
			require 'feature.php';

			$http_client = new \GuzzleHttp\Client();

			$url = $_POST['url'];
			$option = $_POST['radio'];
			crawl_toscrape($url, $option, $extracted_link);

		
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
	if (empty($extracted_link) === True && isset($_POST['url'])){
		echo "THIS WEBSITE MAY NOT HAVE YOUR DESIRED FILE TYPE";
	}
	else{
		for ($i = 20; $i < 25; $i++){
			echo "<a href = $extracted_link[$i]>$extracted_link[$i] </a> <br>";
		}
		// foreach ($extracted_link as $link){
		// 	echo "<a href = $link>$link </a> <br>";
		// }
	}

?>


