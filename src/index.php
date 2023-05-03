<?php

///////////////// Array to store all links to data /////////////////
$extracted_link = [];
///////////////// Crawl data from specified URL /////////////////
if (isset($_POST['url'])) {
	if (isset($_POST['down_all'])) {
		$extracted_link = unserialize($_POST['data']);

		$zip = new ZipArchive();

		# create a temp file & open it
		$tmp_file = tempnam('.', '');
		$zip->open($tmp_file, ZipArchive::CREATE);

		$i = 0;
		foreach ($extracted_link as $file) {
			# download file
			if ($i >= 10) {
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
	} else {
		require '../vendor/autoload.php';
		require 'feature.php';

		$url = $_POST['url'];
		$option = $_POST['radio'];
		if (strpos($url, "books.toscrape.com") !== False) {
			crawl_toscrape($url, $option, $extracted_link);
		} else if (strpos($url, "pdfdrive") !== False) {
			crawl_pdfdrive($url, $option, $extracted_link);
		} else if (strpos($url, "catalog.data.gov") !== False) {
			crawl_catalog($url, $option, $extracted_link);
		}



	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
		crossorigin="anonymous"></script>
	<title>Document</title>
</head>

<body class="container-fluid h-100">
	<h1>Simple web crawler tool</h1>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" name="form" method="POST" class="mb-4">
		<div class="mb-3">
			<label class="form-label" for="url">Enter the link you want to crawl for:</label>
			<input type="text" name="url" id="url" class="form-control" placeholder="https://...">
		</div>
		<div class="mb-3">
			<legend for="select-filetype">File type:</legend>
			<fieldset id="select-filetype">
				<input type="radio" id="img" name="radio" value="img" class="form-check-input me-1" checked>
				<label for="img" class="form-check-label">Image File (.png, .jpg, .gif)</label>

				<div>
					<input type="radio" id="doc" name="radio" value="doc" class="form-check-input me-1">
					<label for="doc" class="form-check-label">Document (.pdf, .doc, .docx) </label>
				</div>

				<div>
					<input type="radio" id="csv" name="radio" value="csv" class="form-check-input me-1">
					<label for="csv" class="form-check-label">Worksheet (.csv, .xlsx) </label>
				</div>

				<div>
					<input type="radio" id="zip" name="radio" value="zip" class="form-check-input me-1">
					<label for="zip" class="form-check-label">Compressed File (.tar.gz, .zip, .rar, .7z) </label>
				</div>
				<div>
					<input type="radio" id="sound" name="radio" value="sound" class="form-check-input me-1">
					<label for="sound" class="form-check-label">Music File (.mp3, .wav) </label>
				</div>

				<br>
			</fieldset>
		</div>
		<div class="col">
			<input type="submit" name="my_form_submit_button" value="CRAWL" class="btn btn-primary" />
			<input type='hidden' name='data' value="<?php echo htmlentities(serialize($extracted_link)); ?>" />
			<input type="submit" id="down_btn" name="down_all" value="DOWNLOAD ALL" class="btn btn-secondary" />
		</div>
	</form>
	<h2>Result</h2>
	<?php if (empty($extracted_link) === True && isset($_POST['url'])) { ?>
		<p>"THIS WEBSITE MAY NOT HAVE YOUR DESIRED FILE TYPE";</p>
	<?php } else { ?>
		<div style="height: 30rem; overflow-y: scroll;" class="border p-2 rounded">
			<?php foreach ($extracted_link as $link) { ?>
				<div class="border mb-1 p-3 rounded">
					URL: <a href=<?= $link ?>><?= $link ?></a>
				</div>
			<?php } ?>
		</div>
	<?php } ?>


</body>

</html>