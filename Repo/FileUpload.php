<?php 
class FileUpload{
	
	public $dir;

 	public static function getRootPath($root = true) {
 		if($_SERVER['HTTP_HOST'] == 'localhost')
 		{
 			if($root)
		 		$dir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'babystar'.DIRECTORY_SEPARATOR;
		 	else
		 		$dir = 'http://'.$_SERVER['HTTP_HOST'].'/babystar/';		 		
 		}
 		else
 		{
 			if($root)
		 		$dir = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'babystar'.DIRECTORY_SEPARATOR;
		 	else
		 		$dir = 'http://'.$_SERVER['HTTP_HOST'].'/babystar/';
 		}

 		return $dir;
    }

	public function uploadTmp($file, $folder)
	{
		$resp = array('code' => 400,  'file_name' => '', 'web_url' => '');
		$path = self::getRootPath(true).'data'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR;
		$webUrl = self::getRootPath(false).'data/'.$folder.'/';
		$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
		$fileName = time().rand(1, 100).'.'.strtolower($ext);
		if(is_array($file))
		{
			
				if(move_uploaded_file($file['tmp_name'], $path.$fileName))
				{
					$resp['file_name'] = $fileName;
					$resp['code'] = 200;
					$resp['web_url'] = $webUrl.$fileName;
				}
			
		}

		return $resp;
	}	
}