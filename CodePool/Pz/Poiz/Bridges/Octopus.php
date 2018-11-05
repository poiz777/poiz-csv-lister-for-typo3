<?php

namespace CodePool\Pz\Poiz\Bridges;

use CodePool\Pz\DataObjects\PzCsvFileEntity;
use Doctrine\ORM\EntityManager;
use ParseCsv\Csv;

require_once __DIR__ . '/../../../../config/pimple_config.php';

class Octopus {

	/**
	 * @var int
	 */
	protected static $maxFileSize   = 20000000;

	public function __construct() { }

	public static function customEntityToArray($entity) {
		$result     = array();
		$class      = new \ReflectionClass(get_class($entity));
		$properties = $class->getProperties();
		
		foreach($properties as $property){
			$clean  = self::rinseFieldName($property->name);
			if($class->hasMethod("get{$clean}")){
				$method = "get{$clean}";
				$result[$property->name]  =$entity->$method();
			}
		}
		return $result;
	}

	public static function arrayToObjectRecursive($array, &$objReturn=null){
		if(!is_array($array) || empty($array)) return null;
		$objReturn = (!$objReturn) ? new \stdClass() : $objReturn;
		foreach($array as $key=>$val){
			if(is_array($val)){
				$objReturn->$key = new \stdClass();
				static::arrayToObjectRecursive($val, $objReturn->$key);
			}else{
				$objReturn->$key		= $val;
			}
		}
		return $objReturn;
	}

	public static function objectToArrayRecursive($object, &$return_array=null){
		if(!is_object($object) || empty($object)) return null;
		$return_array = (!$return_array) ? [] : $return_array;
		foreach($object as $key=>$val){
			if(is_object($val)){
				$return_array[$key] = [];
				static::objectToArrayRecursive($val, $return_array[$key]);
			}else{
				$return_array[$key]		= $val;
			}
		}
		return $return_array;
	}
	
	public static function parseGetVarsAsCleanArray($filter=false, $extName='tx_poizgallery_pzgallery'){
		$curURL         = urldecode(Octopus::getCurrentPageURL());
		$qString        = urldecode($_SERVER['QUERY_STRING']);
		$arrParts       = explode("&", $qString);
		$parsed         = [];
		foreach($arrParts as $keyEqualsVal){
			list($key, $val)    = array_filter(explode("=", trim($keyEqualsVal)));
			$parsed[$key]       = $val;
		}
		if($filter){
			$hash           = $parsed["cHash"];
			$action         = $parsed["{$extName}[action]"];
			$controller     = $parsed["tx_poizgallery_pzgallery[controller]"];
			unset($parsed["cHash"]);
			unset($parsed["{$extName}[action]"]);
			unset($parsed["{$extName}[controller]"]);
		}
		return $parsed;
	}
	
	public static function getArgumentsFromRequestVars($requestType="POST", $formKey="tx_PoizGallery_pzBender"){
		$oArguments         = null;
		switch(strtoupper($requestType)){
			case "POST":
				$oArguments = unserialize(base64_decode($_POST[$formKey]['__referrer']['arguments']));
				break;
			case "GET":
				$oArguments = $_GET[$formKey];
			
		}
		return $oArguments;
	}
	
	public static function getRequestVars($requestType="POST", $formKey=null){
		$requestVars        = null;
		switch(strtoupper($requestType)){
			case "POST":
				$gpVars         = $formKey ? $_POST[$formKey] : $_POST;
				break;
			case "GET":
				$gpVars         = $formKey ? $_GET[$formKey]  : $_GET;
				break;
			default:
				$gpVars         = $formKey ? $_GET[$formKey]  : $_GET;
				break;
		}
		$requestVars    = array_map(function($val){
			if(is_string($val) || is_numeric($val)){
				return trim(htmlspecialchars($val));      //strip_tags())
			}
		}, $gpVars);
		return $requestVars;
	}
	
	public static function extractInnerKeyVars($arrKeyVars, $extName='tx_poizgallery_pzgallery'){
		$parsed         = [];
		foreach($arrKeyVars as $keyWithExtNamePrefix=>$keyVal){
			$key            = preg_replace("#(" . $extName . "\[)(.*)(\])#", "$2", $keyWithExtNamePrefix);
			$parsed[$key]   = $keyVal;
		}
		return $parsed;
	}
	
	public static function rebuildParsedGetVars($arrMergedLink, $encoded=true){
		$strRebuilt     = "";
		foreach($arrMergedLink as $key=>$val){
			$strRebuilt.= "{$key}={$val}&";
		}
		$strRebuilt    = rtrim($strRebuilt, "&");
		
		return ($encoded) ? "?" .  urlencode($strRebuilt) : "?" .  $strRebuilt;
	}
	
	/**
	 * @param $fileName             // NAME OF FILE TO DELETE
	 * @param int $deleteOrEmpty    // 0 == DELETE, 1 == EMPTY CONTENT
	 * @param int $ageInSeconds     // AGE IN SECONDS: DEFAULT = 15 MINUTES = 60*60 SECONDS
	 * @return bool | int
	 */
	public static function deleteFileOrEmptyFileContentOlderThanXSeconds($fileName, $deleteOrEmpty=1, $ageInSeconds=60*15){ // 15 MINUTES...
		$status = FALSE;
		if(!file_exists($fileName)){
			return $status; // THROW AN EXCEPTION.... OR SO....
		}
		$fileInfo       = new \SplFileInfo($fileName);
		$fileModTime    = $fileInfo->getMTime();    // UNIX TIMESTAMP
		$currentTime    = time();
		if( ($currentTime - $fileModTime) >= $ageInSeconds ){
			if($deleteOrEmpty === 0){
				$status = unlink($fileName);
			}else if($deleteOrEmpty === 1){
				$status = self::writeFile($fileName, "");
			}
		}
		return $status;
	}

	public static function writeFile($filePathName, $fileContent=null){
		$fileBaseName   = basename($filePathName);
		$saveDirectory  = rtrim( str_replace( $fileBaseName, "", $filePathName), DIRECTORY_SEPARATOR);
		if(!is_dir($saveDirectory)){
			mkdir($saveDirectory, 0777, true);
		}
		return file_put_contents($filePathName, $fileContent);
	}

	public static function autoSetClassProps($object, $props, $eBank=FALSE){
		if( (is_array($props) || is_object($props)) && $props ){
			foreach($props as $propName=>$propValue){
				$gsName                     = self::rinseFieldName($propName);
				$setterMethod               = "set" . $gsName;
				if(property_exists($object, $propName)){
					if(method_exists($object, $setterMethod)){
						$object->$setterMethod($propValue);
					}else{
						$object->$propName          = $propValue;
					}
					$object->entityBank[$propName]  = $propValue;
				}
			}
		}
		return isset($object->entityBank) && $eBank ? $object->entityBank : $object;
	}
	
	public static function autoSetClassProperties($object, $arrData){
		if(!is_null($arrData)){
			foreach($arrData as $prop=>$val){
				if(property_exists($object, $prop)){
					$object->$prop    = $val;
					$object->entityBank[$prop]    = $val;
					unset($object->entityBank["entityBank"]);
				}
			}
		}
	}

	public static function getClassProperties($fullyQualifiedClassName){
		$arrClassProps                  = [];
		$refClass                       = new \ReflectionClass($fullyQualifiedClassName);

		foreach ($refClass->getProperties() as &$refProperty) {
			$arrClassProps[]        = $refProperty->getName();
		}
		return $arrClassProps;
	}

	public static function initializeEntityBank($object){
		$refClass					= new \ReflectionClass($object);

		foreach ($refClass->getProperties() as &$refProperty) {
			$key					= $refProperty->getName();
			if($key == "entityBank"){ continue; }
			$object->entityBank[$key]	= $object->$key;
		}
		return $object->entityBank;
	}

	public static function initializeProperties($object, $thisObject){
		foreach ($object as $prop=>$propVal) {
			if(property_exists($thisObject, $prop)){
				if($prop == "entityBank" || preg_match("#^_#", $prop)){ continue; }
				$thisObject->$prop                = $propVal;
				$thisObject->entityBank[$prop]	= $propVal;
			}
		}
		return $thisObject;
	}

	public static function parseEnvFile($envFile){
		$envData            =  file_get_contents($envFile);
		$envObject          = new \stdClass();
		if($envData){
			$envData        = explode("\n", $envData);
			foreach($envData as $intDex=>$data){
				$tmpData    = explode("=", $data);
				if(count($tmpData)>1){
					$key    = trim($tmpData[0]);
					$envObject->$key = trim($tmpData[1]);
				}
			}
		}
		return $envObject;

	}
	
	public static function getCurrentPageURL($justBase=false) {
		$pageURL = 'http';
		
		if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on"))
			$pageURL .= "s";
		
		$pageURL .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80")
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		
		else
			$pageURL .= $_SERVER["SERVER_NAME"];
		
		if(!$justBase){
			$pageURL .= $_SERVER["REQUEST_URI"];
		}else{
			if($_SERVER["SERVER_NAME"] == "127.0.0.1" || $_SERVER["SERVER_NAME"] == "localhost" ){
				$requestURI             = $_SERVER["REQUEST_URI"];
				$arrServerNameExtract   = preg_split("#\/#", preg_replace("#^\/#", "", $requestURI));
				$serverNameExtract      = array_shift($arrServerNameExtract);
				$pageURL               .= "/" . $serverNameExtract;
			}
		}
		
		if ( function_exists('apply_filters') ) apply_filters('wppb_curpageurl', $pageURL);
		return $pageURL;
	}
	
	public static function getActivePageURL($justBase=false) {
		$pageURL = 'http';
		
		if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on"))
			$pageURL .= "s";
		
		$pageURL .= "://";
		
		if ($_SERVER["SERVER_PORT"] != "80")
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		
		else
			$pageURL .= $_SERVER["SERVER_NAME"];
		
		if(!$justBase){
			$pageURL .= $_SERVER["REQUEST_URI"];
		}else{
			if($_SERVER["SERVER_NAME"] == "127.0.0.1" || $_SERVER["SERVER_NAME"] == "localhost" ){
				$requestURI             = $_SERVER["REQUEST_URI"];
				$arrServerNameExtract   = preg_split("#\/#", preg_replace("#^\/#", "", $requestURI));
				$serverNameExtract      = array_shift($arrServerNameExtract);
				$pageURL               .= "/" . $serverNameExtract;
			}
		}
		
		if ( function_exists('apply_filters') ) apply_filters('wppb_curpageurl', $pageURL);
		return $pageURL;
	}
	
	public static function generateRandomHash($length = 6) {
		$characters     = '0123456789ABCDEF';
		$randomString   = '';
		
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		
		return $randomString;
	}

	public static function generateRandomHexHash($length = 6) {
		return self::generateRandomHash($length);
	}

	public static function rinseFieldName($fieldName){
		$arrName    = preg_split("#[_\-\s]+#",    $fieldName);
		$arrName    = array_map("ucfirst", array_filter($arrName));
		$strName    = implode("", $arrName);
		return $strName;
	}


	public static function uploadCSVFiles($clientDirName="kunden", $json=false, $saveHashed=true){
		$fileData               = isset($_FILES['pz_item_photo'])   ? $_FILES['pz_item_photo']  : [];
		$fileSize               = isset($fileData['size'])          ? $fileData['size']         : null;
		$fileError              = isset($fileData['error'])         ? $fileData['error']        : null;
		$responseData           = ["fileName"=>"", "fileLocation"=>"", "status"=>0, "id"=>null, "hash"=>null];
		$tmpDocPath             = PLG_TEMP_STORE . "TMP/TMP.txt";

		self::deleteFileOrEmptyFileContentOlderThanXSeconds($tmpDocPath, 1, 180);
		$tmpDocStr              = (file_exists($tmpDocPath) && $str = file_get_contents($tmpDocPath) ) ? unserialize($str) : [];

		if(!empty($fileData) && !$fileError){
			$pixData        = self::arrayToObjectRecursive($fileData);
			$fileExt        = pathinfo($pixData->name, PATHINFO_EXTENSION);
			$randHash       = ($saveHashed) ? self::generateRandomHash(60) : pathinfo($pixData->name, PATHINFO_FILENAME);        //28);
			$uploadPath     = PLG_TEMP_STORE . "{$clientDirName}" . DIRECTORY_SEPARATOR;

			if(self::confirmDataUploadPath($uploadPath)){
				$filePath   = realpath($uploadPath) . "/{$randHash}.{$fileExt}";

				$status = move_uploaded_file($pixData->tmp_name, $filePath);
				if($status){
					$responseData    = [
						"fileName"      => "{$randHash}.{$fileExt}",
						"fileRealName"  => $fileData['name'],
						"fileLocation"  => $filePath,
						"status"        => 1,
						"hash"          => $randHash,
					];

					$fileByteSize               = self::getFileByteSize($fileSize);
					$responseData['fileType']   = $fileExt;
					$responseData['fileSize']   = $fileByteSize;
					$responseData['url']        = APP_BASE_URL . preg_replace("#(.*)(fileadmin)#", "/$2",  realpath($filePath));
					$responseData['oKIcon']     = ICONS_URI . "ok_icon.png";
					$responseData['notOKIcon']  = ICONS_URI . "not_ok_icon.png";
					$responseData['icon']       = file_exists(ICONS_BASE . "pz-file-icon-{$fileExt}.png") ?
						ICONS_URI . "pz-file-icon-{$fileExt}.png" :
						ICONS_URI ."pz-file-icon-generic.png" ;
				}
			}
		}
		$tmpDocStr[]                    = $responseData;
		$serialized                     = serialize($tmpDocStr);
		$responseData['serialized']     = $serialized;
		self::writeFile($tmpDocPath, $serialized);
		return ($json)? json_encode($responseData) : $responseData;
	}



	public static function deleteTemporarilyUploadedCSVFile($filePath=null) {
		if(!$filePath){return json_encode([]);}
		$response       = ['status' => 'Could not delete CSV File "' . basename($filePath) . '"'];
		$tmpDocPath     = PLG_TEMP_STORE . "TMP/TMP.txt";
		$rayTmpCsvData  = (file_exists($tmpDocPath) && ($str = file_get_contents($tmpDocPath)) ) ? unserialize($str) : [];

		if($rayTmpCsvData){
			foreach($rayTmpCsvData as $k=>$v){
				if($filePath == $v['fileLocation']){
					if(file_exists($v['fileLocation'])){
						unlink($v['fileLocation']);
					}
					unset($rayTmpCsvData[$k]);
				}
			}
		}
		if(empty($rayTmpCsvData) || sizeof($rayTmpCsvData)<1){
			unlink($tmpDocPath);
		}else{
			self::writeFile($tmpDocPath, serialize($rayTmpCsvData));
		}

		$response['status'] = '"' . basename($filePath) . '" successfully deleted...';
		return json_encode($response);
	}

	public static function deleteOldUploadedCSVFile($csvFileID=null) {
		/**@var EntityManager $eMan */
		/**@var PzCsvFileEntity $csvFileRepo */
		if(!$csvFileID){return json_encode([]);}
		$eMan           = $GLOBALS['container']['E_MAN'];
		$csvFileRepo    = $eMan->getRepository('CodePool\Pz\DataObjects\PzCsvFileEntity')->find($csvFileID);
		$response       = ['status' => 'Could not delete CSV File with ID"' . $csvFileID . '"'];

		if($csvFileRepo){
			$csvFile    = realpath(SITE_ROOT . $csvFileRepo->getFilePath());
			if($csvFile && file_exists($csvFile)){
				unlink($csvFile);
			}
			$eMan->remove($csvFileRepo);
			$eMan->flush();

			$response['status'] = '"' . basename($csvFile) . '" successfully deleted...';
		}
		return json_encode($response);
	}
	
	public static function handleEntryTrashingAndUpdating($task, $csvFileID, $field, $fieldVal, $updatePackets=null){
		/**@var EntityManager $eMan */
		/**@var PzCsvFileEntity $csvFileRepo */
		if(!$csvFileID || !$task){return json_encode([]);}
		$eMan           = $GLOBALS['container']['E_MAN'];
		$csvFileRepo    = $eMan->getRepository('CodePool\Pz\DataObjects\PzCsvFileEntity')->find($csvFileID);
		$response       = ['status' => 'Could not process the Data for this Entry.'];

		if($csvFileRepo){
			$csvFile    = realpath(SITE_ROOT . $csvFileRepo->getFilePath());
			$csvData    = new Csv($csvFile);
			$rayDataValues  = $csvData->data;
			$rayHeadingList = $csvData->titles;
			foreach ($rayDataValues as $sKey=>&$rayData){
				if($rayData[$field] == $fieldVal){
					if($task == "update"){
						if($updatePackets){
							foreach($updatePackets as $fldKey=>$rayValues){
								$rayData[$fldKey]   = $rayValues['updateValue'];
							}
						}
						$response['status'] = "Entry updated successfully!";
					}else{
						unset($rayDataValues[$sKey]);
						$response['status'] = "Entry deleted successfully!";
					}
					$csvData->save($csvFile, $rayDataValues, false, $rayHeadingList);
					break;
				}
			}
		}
		return json_encode($response);
	}

	private static function confirmDataUploadPath($path){
		if(file_exists(realpath($path))) {
			return $path;
		}
		$path = (mkdir($path, 0777, true)) ? $path : false;
		return $path;
	}

	public static function getFileByteSize($bytes, $type="b2m") {
		switch ($type) {
			case "b2k":     // BYTES TO KILOBYTES
				$suffix = " KB";
				$result = (int)$bytes / 1000;
				break;
			case "b2m":     // BYTES TO MEGABYTES
				$suffix = " MB";
				$result =  (int)$bytes / (1000*1000);
				break;
			case "b2b":     // BITS TO BYTES
				$suffix = " Bytes";
				$result = $bytes / 8 ;
				break;
			default:
				$suffix = " KB";
				$result =  (int)$bytes / (1000);
				break;
		}
		if($result < 1 && $suffix == " MB"){
			$result = $result * 1000; // BYTES TO KILOBYTES
			$suffix = " KB";
		}
		return number_format($result, 2, ".", "'") . $suffix;
	}


	/**
	 * @param $string
	 * @param string $extensionName
	 *
	 * @return mixed
	 */
	public static function translateString($string, $extensionName="poiz_csv_lister") {
		preg_match_all("#(\<.*?\>)#", $string, $matches1);
		preg_match_all("#(\>.*?\<)#", $string, $matches2);
		$translated = "";

		if(isset($matches1[0]) && !empty($matches1[0])){
			foreach ($matches1[0] as $k=>$v){
				$translated .= $v;
				if(isset($matches2[0][$k])){
					$transStr       = trim(trim($matches2[0][$k]), '><');
					if($transStr){
						$translated.= \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($transStr, $extensionName);
					}
				}
			}
		}else{
			$translated = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($string, $extensionName);
		}
		return $translated;
	}

}