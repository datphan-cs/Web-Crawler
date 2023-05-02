<?php 
	
	///////////////// Array to store all links to data /////////////////
	$extracted_link = [];
	///////////////// Crawl data from specified URL /////////////////
	if (isset($_POST['url'])){
		if (isset($_POST['down_all'])){
			$extracted_link = unserialize($_POST['data']);

			$zip = new ZipArchive();

			# create a temp file & open it
			$tmp_file = tempnam('.', '');
			$zip->open($tmp_file, ZipArchive::CREATE);

			$i = 0;
			foreach ($extracted_link as $file) {
				# download file
				if ($i >=10){
					break;
				}
				$i++;
				$download_file = file_get_contents($file);
				$zip->addFromString(basename($file), $download_file);
			}

			# close zip
			$zip->close();

			# send the file to the browser as a download
			header('Content-disposition: attachment; filename="my file.zip"');
			header('Content-type: application/zip');
			readfile($tmp_file);
			unlink($tmp_file);
			// foreach ($extracted_link as $link){
			// 	$file_name = basename($link);
			// 	$file_path = "download/";
			// 	$file_name = $file_path . $file_name;
			// 	if (file_put_contents($file_name, file_get_contents($link))){
			// 		echo "File downloaded successfully";
			// 	}
			// 	else{
			// 		echo "File downloading failed.";
			// 	}
			// }
		}
		else{
			require 'vendor/autoload.php';
			require 'feature.php';

			$url = $_POST['url'];
			$option = $_POST['radio'];
			if (strpos($url,"books.toscrape.com") !== False){
				crawl_toscrape($url, $option, $extracted_link);			
			}
			else if (strpos($url,"pdfdrive") !== False){
				crawl_pdfdrive($url, $option, $extracted_link);
			}
			else if (strpos($url, "catalog.data.gov") !== False){
				crawl_catalog($url, $option, $extracted_link);
			}


		
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
      <label for="img">Image File (.png, .jpg, .gif) </label>
    </div>

    <div>
      <input type="radio" id="doc" name="radio" value = "doc">
      <label for="pdf">Document (.pdf, .doc, .docx) </label>
    </div>

    <div>
      <input type="radio" id="csv" name="radio" value = "csv">
      <label for="csv">Worksheet (.csv, .xlsx) </label>
    </div>

    <div>
      <input type="radio" id="zip" name="radio" value = "zip">
      <label for="zip">Compressed File (.tar.gz, .zip, .rar, .7z) </label>
    </div>
    <div>
      <input type="radio" id="sound" name="radio" value = "sound">
      <label for="sound">Music File (.mp3, .wav) </label>
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
		// for ($i = 20; $i < 25; $i++){
		// 	echo "<a href = $extracted_link[$i]>$extracted_link[$i] </a> <br>";
		// }
		foreach ($extracted_link as $link){
			echo "<a href = $link>$link </a> <br>";
		}
	}

?>


