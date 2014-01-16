<?php

define('WP_USE_THEMES', false);

require "../../../wp-load.php";
require "../../../wp-admin/includes/file.php";
require "../../../wp-admin/includes/image.php";

$upload_overrides = array( 'test_form' => FALSE );

$error = "";
$msg = "";
$status = "";

$fileElementName = 'fileToUpload';

if(!empty($_FILES[$fileElementName]['error'])) {
	switch($_FILES[$fileElementName]['error'])	{

		case '1':
			$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			break;
		case '3':
			$error = 'The uploaded file was only partially uploaded';
			break;
		case '4':
			$error = 'No file was uploaded.';
			break;

		case '6':
			$error = 'Missing a temporary folder';
			break;
		case '7':
			$error = 'Failed to write file to disk';
			break;
		case '8':
			$error = 'File upload stopped by extension';
			break;
		case '999':
		default:
			$error = 'No error code avaiable';
	}
}	elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none') {
	$error = 'Não foi possível fazer o upload';
} else {

		$file_array = array(
			'name' 		=> $_FILES['fileToUpload']['name'],
			'type'		=> $_FILES['fileToUpload']['type'],
			'tmp_name'	=> $_FILES['fileToUpload']['tmp_name'],
			'error'		=> $_FILES['fileToUpload']['error'],
			'size'		=> $_FILES['fileToUpload']['size'],
		);

		$uploads = wp_upload_dir();
		$uploaded_file = wp_handle_upload($file_array, $upload_overrides);

		if($uploaded_file) {
			$status = "Enviou com sucesso";
			
		}

		//for security reason, we force to remove all uploaded file
		@unlink($_FILES['fileToUpload']);

		echo "{";
		echo  "error: '" . $error . "',\n";
		echo  "url: '" . base64_encode($uploaded_file["file"]) . "'\n";
		//echo  "status: '" . $status ."'\n";
		echo "}";	
}		
