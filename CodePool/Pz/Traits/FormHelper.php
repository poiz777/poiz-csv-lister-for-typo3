<?php
	/**
	 * Author      : Poiz
	 * FileName    : ControllerHelper.php
	 */
	
	namespace CodePool\Pz\Traits;

	use CodePool\Pz\FormObjects\PzCSVFileForm;
	use Doctrine\DBAL\Connection;
	use Doctrine\ORM\EntityManager;

	require_once  __DIR__ . "/../../../config/pimple_config.php";
	
	trait FormHelper {
		
		public function autoSetClassProps($props){
			if( (is_array($props) || is_object($props)) && $props ){
				foreach($props as $propName=>$propValue){
					$gsName                     = $this->rinseFieldName($propName);
					$setterMethod               = "set" . $gsName;
					if(property_exists($this, $propName)){
						if(method_exists($this, $setterMethod)){
							$this->$setterMethod($propValue);
						}else{
							$this->$propName			= $propValue;
						}
						$this->entityBank[$propName]	= $propValue;
					}
				}
			}
		}
		
		public function initializeProperties($object){
			foreach ($object as $prop=>$propVal) {
				if(property_exists($this, $prop)){
					if($prop == "entityBank" || preg_match("#^_#", $prop)){ continue; }
					$this->$prop				= $propVal;
					$this->entityBank[$prop]	= $propVal;
				}
			}
			return $this;
		}
		
		protected function rinseFieldName($fieldName){
			$arrName    = preg_split("#[_\-\s]+#",    $fieldName);
			$arrName    = array_map("ucfirst", $arrName);;
			$strName    = implode("", $arrName);
			return $strName;
		}
		
		public function autoSetClassProperties($arrData){
			if(!is_null($arrData)){
				foreach($arrData as $prop=>$val){
					if(property_exists($this, $prop)){
						$this->$prop				= $val;
						$this->entityBank[$prop]    = $val;
					}
				}
			}
		}
		
		protected function getClassProperties($fullyQualifiedClassName){
			$arrClassProps                  = [];
			$refClass                       = new \ReflectionClass($fullyQualifiedClassName);
			
			foreach ($refClass->getProperties() as &$refProperty) {
				$arrClassProps[]        = $refProperty->getName();
			}
			return $arrClassProps;
		}
		
		public function initializeEntityBank(){
			$refClass					= new \ReflectionClass($this);
			
			foreach ($refClass->getProperties() as &$refProperty) {
				$key					= $refProperty->getName();
				$this->entityBank[$key]	= $this->$key;
			}
			return $this->entityBank;
		}
		
		public function objectToArrayRecursive($object, &$return_array=null){
			if(!is_object($object) || empty($object)) return null;
			$return_array = (!$return_array) ? [] : $return_array;
			foreach($object as $key=>$val){
				if(is_object($val)){
					$return_array[$key] = [];
					$this->objectToArrayRecursive($val, $return_array[$key]);
				}else{
					$return_array[$key]		= $val;
				}
			}
			return $return_array;
		}
		
		public function arrayToObjectRecursive($array, &$objReturn=null){
			if(!is_array($array) || empty($array)) return null;
			$objReturn = (!$objReturn) ? new \stdClass() : $objReturn;
			foreach($array as $key=>$val){
				if(is_array($val)){
					$objReturn->$key = new \stdClass();
					$this->arrayToObjectRecursive($val, $objReturn->$key);
				}else{
					$objReturn->$key		= $val;
				}
			}
			return $objReturn;
		}
		
		public function recursiveArrayFind($key, $data){
			if(array_key_exists($key, $data)){
				return $data[$key];
			}else{
				if(is_array($data)){
					foreach($data as $k=>$value){
						if($k == $key){
							return $value;
						}else if(is_array($value)){
							return $this->recursiveArrayFind($key, $value);
						}
					}
				}
			}
			return null;
		}
		
		public function getEntityBank() {
			return $this->entityBank;
		}
		
		public static function cmp($a, $b){
			return $a->name > $b->name;
		}
		
		public static function getFormElementConfig($settings=[]){
			$config = [
				'fieldID'           => "location_id",
				'fieldType'         => "select",
				'fieldData'         => " data-change-action='getGalleryByLocation' ",
				'fieldName'         => "location_id",
				'fieldValue'        => "",
				'fieldClass'        => "pz-location location_id form-control",
				'fieldState'        => "",
				'fieldLabel'        => "Ort:",
				'fieldOptions'      => [],
				'fieldRequired'     => 0,
				'fieldRawContent'   => null,
				'fieldPlaceholder'  => null,
			];
			return array_merge($config, $settings);
		}
		
		public static function getAvailabilityOptions(){
			return [
				"1" =>"Available",
				"0" =>"Not Available",
			];
		}
		
		public static function getYesNoOptions(){
			return [
				"1" =>"Yes",
				"0" =>"No",
			];
		}
		
		public static function getTrueFalseOptions(){
			return [
				"1" =>"TRUE",
				"0" =>"FALSE",
			];
		}

		public static function screenOffErrorsWithIDWithinKeys(array $arrErrors) {
			$arrScreenedErrors = array();
			foreach ($arrErrors as $errorKey => $strErrorMessage) {
				if (preg_match('#(^id)|(.*Id$)#', $errorKey)) {
					continue;
				}
				else {
					$arrScreenedErrors[$errorKey] = $strErrorMessage;
				}
			}
			return $arrScreenedErrors;
		}
		
		public static function buildCSVLibrary($instance=null) {
			/**@var PzCSVFileForm $instance */
			/**@var EntityManager $eMan */
			/**@var Connection $dBal */
			$processor  = AJAX_URI;
			$instance   = (!$instance) ? new PzCSVFileForm(): $instance;
			$eMan       = $GLOBALS['container']['E_MAN'];
			$dBal       = $eMan->getConnection();
			$sql        = "SELECT T_CSV.* FROM `" . TBL_CSV_FILE . "` AS T_CSV ";
			$sql       .= " WHERE T_CSV.uid=:UID";
			$stm        = $dBal->executeQuery($sql, ['UID'=>$instance->getUid()]);
			$arrPix     = $stm->fetchAll(\PDO::FETCH_OBJ);
			$csvHtml    = "";
			if($arrPix){
				foreach($arrPix as $csvKey=>$csvData){
					$csvDataLoad    = " data-file-id='{$csvData->uid}' data-file-path='{$csvData->file_path}' data-processor='{$processor}' data-action='deleteUploadedFileByUID'";
					$style    = "padding:5px 15px;background-color:#FFFFFF;margin-bottom:2px;border:solid 1px gray;";
					$csvHtml .= "<div class='col-md-12' style=\"position:relative;{$style}\" >";
					$csvHtml .= "<div class='col-md-1' {$csvDataLoad}>{$csvData->uid}</div>";
					$csvHtml .= "<div class='col-md-11' {$csvDataLoad}>" . basename($csvData->file_path) . "</div>";
					$csvHtml .= "<div class='pz-trash-pix' {$csvDataLoad} style='position:absolute;top:0;right:15px;font-size:1.3em;cursor:pointer;width:30px;background:rgba(255,255,255,0.5);text-align:center;line-height:0;padding:5px;border-radius:4px;'>";
					$csvHtml .= "<i class='fa fa-trash pz-trash-pix-icon' {$csvDataLoad} ></i>";
					$csvHtml .= "</div>";
					$csvHtml .= "</div>";
				}
			}
			return $csvHtml;
		}

		protected static function fetchAllFromTable($table=TBL_CSV_LISTER) {
			/**@var EntityManager $eMan */
			$eMan       = $GLOBALS['container']['E_MAN'];
			$dBal       = $eMan->getConnection();
			$sql        = "SELECT TBL.* FROM `" . $table . "` AS TBL";
			$stm        = $dBal->executeQuery($sql, []);
			$result     = $stm->fetchAll(\PDO::FETCH_OBJ);
			return $result;
		}

	}