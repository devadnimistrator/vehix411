<?php
/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	function save($path) {
		$input = fopen("php://input", "r");
		$temp = tmpfile();
		$realSize = stream_copy_to_stream($input, $temp);
		fclose($input);

		if ($realSize != $this->getSize()){
			return false;
		}

		$target = fopen($path, "w");
		fseek($temp, 0, SEEK_SET);
		stream_copy_to_stream($temp, $target);
		fclose($target);

		return true;
	}
	function getName() {
		return $_GET['qqfile'];
	}
	function getSize() {
		if (isset($_SERVER["CONTENT_LENGTH"])){
			return (int)$_SERVER["CONTENT_LENGTH"];
		} else {
			throw new Exception('Getting content length is not supported.');
		}
	}
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
	/**
	 * Save the file to the specified path
	 * @return boolean TRUE on success
	 */
	function save($path) {
		if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
			return false;
		}
		return true;
	}
	function getName() {
		return $_FILES['qqfile']['name'];
	}
	function getSize() {
		return $_FILES['qqfile']['size'];
	}
}

class qqFileUploader {
	private $allowedExtensions = array();
	private $sizeLimit = 10485760;
	private $file;

	function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
		$allowedExtensions = array_map("strtolower", $allowedExtensions);

		$this->allowedExtensions = $allowedExtensions;
		$this->sizeLimit = $sizeLimit;

		$this->checkServerSettings();

		if (isset($_GET['qqfile'])) {
			$this->file = new qqUploadedFileXhr();
		} elseif (isset($_FILES['qqfile'])) {
			$this->file = new qqUploadedFileForm();
		} else {
			$this->file = false;
		}
	}

	private function checkServerSettings(){
		$postSize = $this->toBytes(ini_get('post_max_size'));
		$uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

		if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
			$size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
			die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
		}
	}

	private function toBytes($str){
		$val = trim($str);
		$last = strtolower($str[strlen($str)-1]);
		switch($last) {
			case 'g': $val *= 1024;
			case 'm': $val *= 1024;
			case 'k': $val *= 1024;
		}
		return $val;
	}

	/**
	 * Returns array('success'=>true) or array('error'=>'error message')
	 */
	function handleUpload($file_type = "temp", $replaceOldFile = FALSE) {
		if (!$this->file){
			return array('error' => 'No files were uploaded.');
		}

		$size = $this->file->getSize();

		if ($size == 0) {
			return array('error' => 'File is empty');
		}

		if ($size > $this->sizeLimit) {
			return array('error' => 'File is too large');
		}

		$pathinfo = pathinfo($this->file->getName());
		$filename = tep_format_path($pathinfo['filename']);
		
		//$filename = md5(uniqid());
		$ext = $pathinfo['extension'];

		if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
			$these = implode(', ', $this->allowedExtensions);
			return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
		}

		$upload_dir = date('Ymd')."/";
		switch ($file_type) {
			case "image": $upload_dir = "image/".$upload_dir; break;
			case "video": $upload_dir = "video/".$upload_dir; break;
			case "document": $upload_dir = "document/".$upload_dir; break;
			case "audio": $upload_dir = "audio/".$upload_dir; break;
			default: $upload_dir = "temp/".$upload_dir;
		}

		if (is_dir(DIR_WS_UPLOAD.$upload_dir) === false) {
			if (mkdir(DIR_WS_UPLOAD.$upload_dir, 0777) === false) {
				return array('error' => 'File upload.' . DIR_WS_UPLOAD.$upload_dir);
			}
		}

		$file_path = DIR_WS_UPLOAD.$upload_dir.$filename.".".$ext;

		if ($replaceOldFile === true) {
			if (file_exists($file_path)) {
				@unlink($file_path);
			}
		} else {
			while (file_exists($file_path)) {
				$filename = $filename."_".rand(1, 99);
				$file_path = DIR_WS_UPLOAD.$upload_dir.$filename.".".$ext;
			}
		}
		
		if ($this->file->save($file_path)){
			return array('success'=>true, 'filename'=>$pathinfo['filename'], 'filepath'=>$upload_dir.$filename.".".$ext, 'filefullpath'=>HTTP_WS_UPLOAD.$upload_dir.$filename.".".$ext);
		} else {
			return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
		}
	}
}