<?php
class UploadifyAction
{
	public function index()
	{
		// Define a destination
		//$targetFolder = './uploads'; // Relative to the root
		$targetFolder = isset($_POST['save_path']) ? UPLOAD_PATH . $_POST['save_path'] : UPLOAD_PATH;

		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
			$targetPath = $targetFolder;
			
			// Validate the file size
			$file_size = $_FILES['Filedata']["size"];
			if(1024 * 1024 < $file_size) {
				echo  json_encode(array('status' => 0, 'data' => $file_size));
				return;
			}

			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);

			$extension = $fileParts['extension'];
			$targetFile = rtrim($targetPath,'/') . '/' . uniqid(). '.' . $extension;

			if (in_array($extension,$fileTypes) && is_uploaded_file($tempFile)) {
				move_uploaded_file($tempFile,$targetFile);
				echo json_encode(array('status' => 1, 'data' => $targetFile));
			} else {
				echo  json_encode(array('status' => 0, 'data' => '不支持该文件类型'));
			}
		}
	}
}
?>