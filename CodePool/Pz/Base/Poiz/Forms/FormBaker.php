<?php

namespace CodePool\Pz\Base\Poiz\Forms;

use CodePool\Pz\Base\Poiz\Forms\Validator;
use CodePool\Pz\Poiz\Bridges\Octopus;
use CodePool\Pz\Poiz\Bridges\PoizPluginHelper;

/**
 * Class FormBaker
 * @package CodePool\Pz\Base\Poiz\Forms
 */
Class FormBaker {
	/**
	 * @var \CodePool\Pz\Base\Poiz\Forms\Validator
	 */
	protected $validator;

	/**
	 * @var array
	 */
	protected $propertyTypes            = array();

	/**
	 * @var string
	 */
	protected $fullyQualifiedClassName  = 'Application\DataObjects\Item';

	/**
	 * @var string
	 */
	protected $labelClass               = "col-md-12 no-lr-pad lbl-box pz-form-label";

	/**
	 * @var string
	 */
	protected $formElementClass         = "input-box form-control";

	/**
	 * @var string
	 */
	protected $formElementWrapperClass  = "col-md-12 no-lr-pad elem-box";


	/**
	 * @var string
	 */
	protected $formGroupWrapperClass    = "form-group col-md-12";

	/**
	 * @var array
	 */
	protected $arrPostValues            = array();

	/**
	 * @var array
	 */
	protected $arrErrorFields           = array();

	/**
	 * @var array
	 */
	protected $validationStrategies     = array();

	/**
	 * @var array
	 */
	protected $controller               = array();

	/**
	 * @var string
	 */
	protected $blockContent             = "";

	##VALIDATION STRATEGIES##
	########################################################################
	########################################################################
	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_TEL          = "tel";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_FAX          = "fax";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_INT          = "int";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_NUM          = "num";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_RAW          = "raw";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_DATE         = "date";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_BOOL         = "bool";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_MISC         = "misc";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_CITY         = "city";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_HTML         = "html";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_STATE        = "state";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_EMAIL        = "email";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_REGEX        = "regex";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_ALNUM        = "alnum";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_STRING       = "string";

	/**
	 * @var string
	 */
	public static $VALIDATION_STRATEGY_PASSWORD     = "password";


	/**
	 * @var string
	 */
	public static $formScripts                      = "";

	/**
	 * @var string
	 */
	public static $formStyles                       = "";
	########################################################################
	########################################################################

	/**
	 * FormBaker constructor.
	 *
	 * @param string $fullyQualifiedClassName
	 */
	public function __construct($fullyQualifiedClassName='Application\DataObjects\Item'){
		$this->fullyQualifiedClassName  = $fullyQualifiedClassName;
		$this->validator                = new Validator();
	}

	/**
	 * @param string|mixed|null $fullyQualifiedClassName
	 *
	 * @return array
	 * @throws \ReflectionException
	 */
	protected function getClassPropertyInfo($fullyQualifiedClassName=null){
		$fullyQualifiedClassName        = (!$fullyQualifiedClassName) ? $this->fullyQualifiedClassName : $fullyQualifiedClassName;
		$arrClassProps                  = [];
		$refClass                       = new \ReflectionClass($fullyQualifiedClassName);

		foreach ($refClass->getProperties() as &$refProperty) {
			$objClassProps          = new \stdClass();
			$objClassProps->name    = $refProperty->getName();
			if (preg_match('/@var\s+([^\s]+)/', $refProperty->getDocComment(), $matches)) {
				list(, $type) = $matches;
				$objClassProps->type                = $type;
			}
			if (preg_match('/\#\#FormLabel\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $label) = $matches;
				$objClassProps->label               = $label;
			}
			if (preg_match('/\#\#FormInputLabel\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $label) = $matches;
				$objClassProps->label               = $label;
			}
			if (preg_match('/\#\#FormDZoneAllow\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $allowedFiles)               = $matches;
				$objClassProps->allowedFiles        = ($a=$allowedFiles) ? array_filter(explode(",", $a)) : $a;
			}
			if (preg_match('/\#\#FormFieldHint\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $inputFieldHint)             = $matches;
				$objClassProps->inputFieldHint      = $inputFieldHint;
			}
			if (preg_match('/\#\#FormPlaceholder\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $placeholder) = $matches;
				$objClassProps->placeholder         = $placeholder;
			}
			if (preg_match('/\#\#FormInputPrepend\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $prepend) = $matches;
				$objClassProps->prepend         = $prepend;
			}
			if (preg_match('/\#\#FormInputAppend\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $append) = $matches;
				$objClassProps->append         = $append;
			}
			if (preg_match('/\#\#FormInputType\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $inputType) = $matches;
				$objClassProps->inputType           = $inputType;
			}
			if (preg_match('/\#\#FormInputOptions\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $inputOptions) = $matches;
				$objClassProps->inputOptions        = $inputOptions;
			}
			if (preg_match('/\#\#FormInputCodeLang\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $codeLang)   = $matches;
				$objClassProps->codeLang            = $codeLang;
			}
			if (preg_match('/\#\#FormInputCodeTheme\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $codeEditorTheme)            = $matches;
				$objClassProps->codeEditorTheme           = $codeEditorTheme;
			}
			if (preg_match('/\#\#FormInputDzURL\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $dzURL)      = $matches;
				$objClassProps->dzURL               = $dzURL;
			}
			if (preg_match('/\#\#FormInputRequired\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $inputRequired) = $matches;
				$objClassProps->inputRequired       = $inputRequired;
			}
			if (preg_match('/\#\#FormInputFixedValue\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $inputFixedValue)            = $matches;
				$objClassProps->inputFixedValue     = $inputFixedValue;
			}
			if (preg_match('/\#\#FormInputState\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $inputState) = $matches;
				$objClassProps->inputState          = $inputState;
			}
			if (preg_match('/\#\#FormElementClass\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $cssClassExtra) = $matches;
				$objClassProps->cssClassExtra       = $cssClassExtra;
			}
			if (preg_match('/\#\#FormBlockWrapperClass\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $blockWrapperClass) = $matches;
				$objClassProps->blockWrapperClass   = $blockWrapperClass;
			}
			if (preg_match('/\#\#FormElementData\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $dataAttributes) = $matches;
				$objClassProps->dataAttributes      = $dataAttributes;
			}
			if (preg_match('/\#\#FormRawContent\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $rawContent) = $matches;
				$objClassProps->rawContent          = $rawContent;
			}
			if (preg_match('/\#\#FormInputMax\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $rangeMax) = $matches;
				$objClassProps->rangeMax            = $rangeMax;
			}
			if (preg_match('/\#\#FormInputMin\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $rangeMin) = $matches;
				$objClassProps->rangeMin            = $rangeMin;
			}
			if (preg_match('/\#\#FormValidationStrategy\s+(.*)/', $refProperty->getDocComment(), $matches)) {
				list(, $validationStrategy)         = $matches;
				$objClassProps->validationStrategy  = $validationStrategy;
			}
			$arrClassProps[]                        = $objClassProps;
		}
		return $arrClassProps;
	}

	/**
	 * @param $inputOptions
	 * @param $inputType
	 * @param null $default
	 *
	 * @return string
	 */
	protected function getOptions($inputOptions, $inputType, $default=null){
		if(is_bool($default)){
			$default    = ($default==true)?"1":"0";
		}
		$options    = "";
		if($inputType == 'select'){
			foreach($inputOptions as $optKey=>$optVal){
				if ( array_key_exists($default, $inputOptions) && $optKey==$default ) {
					$selected_status = "selected=selected";
				}else{
					$selected_status = "";
				}
				$options .= "<option value='{$optKey}' {$selected_status}>{$optVal}</option>\n";
			}
		}else if($inputType == 'selectM') {
			foreach ($inputOptions as $optKey => $optVal) {
				$selected_status = "";
				if(is_array($default)){
					foreach ($default as $key=>$val ) {
						if (array_key_exists($key, $inputOptions) && $key == $default) {
							$selected_status = "selected=selected";
						}
						else {
							$selected_status = "";
						}
					}
				}
				$options .= "<option value='{$optKey}' {$selected_status}>{$optVal}</option>\n";
			}
		}
		return $options;
	}

	/**
	 * @return array
	 */
	protected function fetchDoctrinePropertyTypes(){
		$propertyTypes  = array(
			"int"           => "INT",
			"float"         => "DOUBLE",
			"bigint"        => "BIGINT",
			"decimal"       => "NUMERIC",
			"integer"       => "INT",
			"boolean"       => "TINYINT",
			"smallint"      => "SMALLINT",
			"string"        => "TEXT",
			"\DateTime"     => "DATETIME",
			"\Date"         => "DATE",
			"array"         => "TEXT",
			"resource"      => "VARBINARY",
			"blob"          => "BLOB",
			"object"        => "TEXT",
		);
		return $propertyTypes;
	}

	/**
	 * @param $objData
	 *
	 * @return string
	 */
	protected function createHTMLFormElement($objData){
		$formElementHTML    = "";

		if(isset($objData->type) && isset($objData->inputType)){
			$inputValue         = $objData->inputValue;
			$iOptions           = "";
			$allowedFiles       = null;
			$required           = "";
			$requiredClass      = "";
			$dataAttributes     = "";
			$iValue             = "";
			$iState             = "";
			$errorClass         = "";
			$placeholder        = "";
			$rawContent         = "";
			$prepend            = "";
			$append             = "";
			$extraWrapperClass  = isset($objData->blockWrapperClass) ? $objData->blockWrapperClass : "" ;
			$formElementHTML   .= "<div class='{$this->formGroupWrapperClass} {$extraWrapperClass}'>" . PHP_EOL;

			if(isset($objData->inputRequired) && $objData->inputRequired){
				$required      .= " required ";
				$requiredClass .= " pz-required ";
			}

			if($inputValue === "CURRENT_TIMESTAMP"){
				$inputValue = date("Y-m-d", time());
			}else if($inputValue instanceof \DateTime){
				$inputValue = $inputValue->format("Y-m-d H:i:s");
			}

			if(isset($objData->inputOptions)){
				$inputOptions   = call_user_func($objData->inputOptions);
				if($objData->inputType == "select" || $objData->inputType == "selectM") {
					$iOptions = $this->getOptions($inputOptions, $objData->inputType, $inputValue);
				}else{
					$iOptions = $inputOptions;
				}
			}

			if(isset($objData->inputState)){
				$iState         = $objData->inputState;
			}

			if(isset($objData->inputFixedValue)){
				$iValue         = $objData->inputFixedValue;
				if(is_callable($iValue)){
					$iValue    = call_user_func($prepend);
				}
			}

			if(isset($objData->placeholder)){
				$placeholder        = $objData->placeholder;
				if( ( isset($objData->inputRequired) && $objData->inputRequired) &&
					( !isset($objData->label) || empty($objData->label)) ) {
					$placeholder   .= "*";
				}
			}

			if(isset($objData->prepend)){
				$prepend        = $objData->prepend;
				if(is_callable($prepend)){
					$prepend    = call_user_func($prepend, $inputValue);
				}
			}

			if(isset($objData->append)){
				$append    = $objData->append;
				if(is_callable($append)){
					$append = call_user_func($append, $inputValue);
				}
			}

			if(!empty($this->arrErrorFields)){
				if(isset($this->arrErrorFields[$objData->name]) ||
					in_array($objData->name, $this->arrErrorFields) ||
					array_key_exists($objData->name, $this->arrErrorFields)){
					$errorClass =  "has-error ";
				}
			}

			if(isset($objData->cssClassExtra)){
				$errorClass    .= $objData->cssClassExtra . " ";
			}

			if('hidden' != $objData->inputType){
				if(isset($objData->label) && $objData->label) {
					$formElementHTML .= "<label for='{$objData->name}' id='lbl-{$objData->name}' class='lbl-{$objData->name} " . $this->labelClass . "'>" . Octopus::translateString($objData->label);
					$formElementHTML .= ($required != "") ? "<span class='pz-asterisks {$errorClass}'>*</span>:" : "";
					if (isset($objData->inputFieldHint) && $objData->inputFieldHint) {
						$formElementHTML .= Octopus::translateString($objData->inputFieldHint);
					}
					$formElementHTML .= "</label>" . PHP_EOL;
				}
			}

			if(isset($objData->allowedFiles )){
				$allowedFiles = $objData->allowedFiles;
			}

			if(isset($objData->dataAttributes)){
				$dataAttributes = $objData->dataAttributes;
			}

			if(isset($objData->rawContent)){
				if(is_callable($objData->rawContent)){
					if($inputValue) {
						$rawContent = call_user_func($objData->rawContent, $inputValue);
					}else {
						$rawContent = call_user_func($objData->rawContent);
					}
				}else {
					$this->blockContent = (isset($objData->inputFixedValue) && $a=$objData->inputFixedValue) ? $a : "";
					$rawContent         = "";
				}
				if(!is_string($inputValue) || !is_numeric($inputValue)){
					$inputValue = "";
				}
			}

			$inputValue         = ($iV=$inputValue)?$iV : $iValue;

			$formElementHTML   .= "<div class='{$this->formElementWrapperClass}'>" . PHP_EOL;
			if($prepend) {
				$formElementHTML .= "<section class='pz-prepended'>" . PHP_EOL;
				$formElementHTML .= $prepend . PHP_EOL;
				$formElementHTML .= "</section>" . PHP_EOL;
				$formElementHTML .= "<div class='clear-float clearfix' ></div>" . PHP_EOL;
			}
			switch($objData->inputType){
				case "text":
				case "time":
				case "hidden":
				case "search":
				case "tel":
				case "email":
				case "file":
					$formElementHTML .= "<input $required {$iState} value='{$inputValue}' type='{$objData->inputType}' name='{$objData->aggregator}[{$objData->name}]' id='{$objData->name}' class='{$errorClass}{$objData->name} {$requiredClass} " . $this->formElementClass . "' placeholder='{$placeholder}' />";
					break;
					
				case "number":
					$max              = (isset($objData->rangeMax) && $objData->rangeMax) ? "max='".trim($objData->rangeMax) . "'" : "";
					$min              = (isset($objData->rangeMin) && $objData->rangeMin) ? "min='".trim($objData->rangeMin) . "'" : "";
					$formElementHTML .= "<input $required {$iState} value='{$inputValue}' type='{$objData->inputType}' {$min} {$max} name='{$objData->aggregator}[{$objData->name}]' id='{$objData->name}' class='{$errorClass}{$objData->name} {$requiredClass} " . $this->formElementClass . "' placeholder='{$placeholder}' />";
					break;
					
				case "password":
					$formElementHTML .= "<input $required {$iState} value='' type='{$objData->inputType}' name='{$objData->aggregator}[{$objData->name}]' id='{$objData->name}' class='{$errorClass}{$objData->name} {$requiredClass} " . $this->formElementClass . "' placeholder='{$placeholder}' />";
					break;
					
				case "date":
				case "\\datetime":
				case "\\Datetime":
					$formElementHTML .= "<input $required {$iState} value='{$inputValue}' type='{$objData->inputType}' name='{$objData->aggregator}[{$objData->name}]' id='{$objData->name}' class='{$errorClass}{$objData->name} {$requiredClass} " . $this->formElementClass . "' placeholder='{$placeholder}' />";
					break;
					
				case "textarea":
					$formElementHTML .= "<textarea $required {$iState} name='{$objData->aggregator}[{$objData->name}]' id='{$objData->name}' class='{$errorClass}{$objData->name} {$requiredClass} " . $this->formElementClass . "' placeholder='{$placeholder}' >{$inputValue}</textarea>";
					break;
					
				case "select":
					$formElementHTML .= "<select $required {$iState} name='{$objData->aggregator}[{$objData->name}]' id='{$objData->name}' class='{$errorClass}{$objData->name} {$requiredClass} " . $this->formElementClass . "' >";
					$formElementHTML .= $iOptions;
					$formElementHTML .= "</select>";
					break;
					
				case "selectM":
					$formElementHTML .= "<select $required multiple {$iState} name='{$objData->aggregator}[{$objData->name}][]' id='{$objData->name}' class='{$errorClass}{$objData->name} " . $this->formElementClass . "  {$requiredClass}' >";
					$formElementHTML .= $iOptions;
					$formElementHTML .= "</select>";
					break;
					
				case "checkbox":
				case "radio":
					$payload    = [ 'iState'=>$iState, 'inputValue'=>$inputValue, 'inputRequired'=>$required, 'type'=>$objData->inputType, 'iOptions'=>$iOptions,
						'class'=>$errorClass . $objData->name . " " . $this->formElementClass .  $requiredClass, 'id'=>$objData->name,
						'placeholder'=>$placeholder, 'name'=>$objData->aggregator."[{$objData->name}]"
					];
					$formElementHTML .= $this->getCheckBoxRadioInputs($payload);
					break;
					
				case "div":
				case "section":
				case "aside":
				case "footer":
				case "p":
				case "h1":
				case "h2":
				case "h3":
				case "h4":
				case "h5":
				case "h6":
					$formElementHTML .= "<{$objData->inputType} data-value='{$inputValue}' id='{$objData->name}' {$dataAttributes} class='{$errorClass}{$objData->name}'>{$rawContent}{$this->blockContent}</{$objData->inputType}>";
					break;
				case "range":
					$rangeMax         = (isset($objData->rangeMax) && $objData->rangeMax) ? trim($objData->rangeMax) : "10";
					$rangeMin         = (isset($objData->rangeMin) && $objData->rangeMin) ? trim($objData->rangeMin) : "1";
					$formElementHTML .= "<div class='range-wrapper'><input {$iState} value='{$inputValue}' type='{$objData->inputType}' name='{$objData->aggregator}[{$objData->name}]' ";
					$formElementHTML .= "id='{$objData->name}' class='{$errorClass}{$objData->name} min='{$rangeMin}' max='{$rangeMax}' list='{$objData->name}-list' value='{$inputValue}' " ;
					$formElementHTML .= $this->formElementClass . "' placeholder='{$placeholder}' /></div>";
					$formElementHTML .= "<div class='range-value' style='text-align:center;'>{$inputValue}</div>";
					$formElementHTML .= $this->getSliderDataList($rangeMin, $rangeMax, $objData->name."-list");
					break;

				case "dz":
				case "drop_zone":
				case "dropZone":
				case "DropZone":
					$ddFeedBack         = self::buildDropZoneFeedBack(PLG_TEMP_STORE . "TMP/TMP.txt");
					$hiddenField        = "";
					$formElementHTML   .= PoizPluginHelper::dropZoneConfig();
					$dropCSV            = Octopus::translateString('poiz_csv_lister.miscellaneous.drop_csv');
					$uploadInfo         = Octopus::translateString('poiz_csv_lister.miscellaneous.upload_info');
					$formElementHTML .=<<<MGK
<div class="pz-drop-down-wrapper">
    <div data-value="" id="file_upload_info" class="file_upload_info col-md-9 file_upload">
        <h3 class="pz-drag-drop-area">{$uploadInfo}</h3>

        <div class="clear-float" style="clear:both;margin-top:0;"></div>
        <div class="" id="pz-block-list-{$objData->name}" style="width:100%;padding:0;margin:0;box-sizing:border-box;max-width:100%;margin-top:5px;overflow: scroll;">
            {$ddFeedBack}
        </div>
        <div class="clear-float" style="clear:both;margin-top:20px;"></div>
    </div>
    <div data-value="" id="{$objData->name}" {$dataAttributes} class="pz-drop-zone col-md-3 {$errorClass}{$objData->name}" data-url="{$objData->dzURL}">
    	{$hiddenField}
        <h3 class="pz-drag-drop-area">{$dropCSV}<span class="fa fa-dropbox"></span></h3>
        <aside id="uploadTrackBlock" class="uploadTrackBlock">
            <aside id="uploadTrackBg" class="uploadTrackBg"></aside>
            <div id="uploadTrack" class="uploadTrack"></div>
        </aside>
        <div class="clear_float clear-float clearfix"></div>
    </div>
    <div class="form-group col-lg-3 pull-right">
    </div>
    <div class="clear_float clear-float clearfix"></div>
</div>
MGK;
					$formElementHTML .= PoizPluginHelper::dropZoneConfigSupplemental($objData->name, $allowedFiles);
					break;

				default:
					$formElementHTML .= "<input {$iState} value='{$inputValue}' type='text' name='{$objData->aggregator}[{$objData->name}]' id='{$objData->name}' class='{$errorClass}{$objData->name} " . $this->formElementClass . "' placeholder='{$placeholder}' />";
					break;
			}
			
			if($append) {
				$formElementHTML .= "<section class='pz-appended'>" . PHP_EOL;
				$formElementHTML .= $append . PHP_EOL;
				$formElementHTML .= "</section>" . PHP_EOL;
				$formElementHTML .= "<div class='clear-float clearfix' ></div>" . PHP_EOL;
			}
			$formElementHTML .= "</div>" . PHP_EOL;
			$formElementHTML .= "</div>" . PHP_EOL;
			$formElementHTML .= "<div class=\"clear_float\"></div>" . PHP_EOL;
		}
		return $formElementHTML;
	}

	/**
	 * @param string $tmpDocPath
	 *
	 * @return string
	 */
	protected function buildDropZoneFeedBack($tmpDocPath=UPLOADS_DIR . "TMP/TMP.txt"){
		$tmpDocStr              = (file_exists($tmpDocPath) && $str = file_get_contents($tmpDocPath) ) ? unserialize($str) : [];
		$ddFeedBack             = "<ul class=\"list-group list-unstyled pz-info-list\" id=\"pz-info-list\" style=\"margin-top:0;\">";
		if($tmpDocPath){
			foreach ($tmpDocStr as $iKey=>$arrVal) {
				if($arrVal['hash']) {
					$ddFeedBack .= <<<DDFB
<li class="list-group-item col-md-12" style="letter-spacing:0.035em;font-size:11px;word-wrap: break-word;font-weight:300;position:relative;">
	<span class="fa fa-trash pz-trash-uploaded" style="padding:0 5px;color:#b53fb4;position:absolute;display:block;font-size: 16px;top:5px;right:5px;cursor:pointer;z-index:9999;" data-file-path="{$arrVal['fileLocation']}" data-action="deleteUploadedFileByPath"></span>
	<div class="col-md-2" style="padding:0 5px;font-weight:700;color:#007c9a;">
		<img class="img-responsive thumbnail" src="{$arrVal['icon']}" alt="Icon" style="margin-bottom: 0;">
	</div>
	<div class="col-md-10" style="font-weight:300;color:#b53fb4;padding-top:5px;">
		<strong style="">{$arrVal['fileRealName']}</strong><br>
		<strong style="color:#212121;">{$arrVal['fileSize']}</strong>  <strong style="color:#AB2121;">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: rgb(3, 139, 170);text-decoration: overline;"><i class="fa fa-tag"></i>{$arrVal['fileType']}</span></strong><br>
		<strong style="color:#613fb5;">Machine Name: </strong>
		<em style="color:#4d4d4d;">{$arrVal['fileName']}</em>
	</div>
</li>
DDFB;
				}
			}
		}
		$ddFeedBack .= " </ul>";
		return $ddFeedBack;
	}

	/**
	 * @param $payload
	 *
	 * @return string
	 */
	protected function getCheckBoxRadioInputs($payload){
		$options            = $payload['iOptions'];
		$inputGroup         = "";

		if(!empty($options)){
			$inputGroup .= "<div class='pz-checkbox-radio-groups-wrapper'>" . PHP_EOL;
			foreach($options as $value=>$label){
				$subClass   = strtolower(str_replace(" ", "-", $value));
				$uniqueID   = "pz-" . $payload['type'] . "-" . $subClass;
				if(!is_array($payload['inputValue'])) {
					$checked = ($payload['inputValue'] == $value) ? " checked='checked' " : "";
				}else{
					$checked = (in_array($value, $payload['inputValue'])) ? " checked='checked' " : "";
				}
				$labelClass = "pz-" . $payload['type'] . "-lbl lbl-{$subClass}";
				$inputGroup.= "<div class='pz-checkbox-radio-group-box'>" . PHP_EOL;
				$inputGroup.= "<input "            . $payload['iState']        . "
									value='"        . $value                    . "'
									type='"         . $payload['type']          . "'
		                            name='"         . $payload['name']          . "[]'
		                            id='"           . $uniqueID                 . "'
		                            placeholder='"  . $payload['placeholder']   . "'
		                            class='"        . $payload['class']         . " pz-{$payload['type']} pz-{$payload['type']}-{$subClass}' {$checked} />";
				$inputGroup.= "<label for='{$uniqueID}' class='{$labelClass}'>{$label}</label>" . PHP_EOL;
				$inputGroup.= "</div>" . PHP_EOL;
			}
			$inputGroup .= "</div>" . PHP_EOL;
		}
		return $inputGroup;
	}

	/**
	 * @param null $objDoctrineEntity
	 * @param array $bypassFields
	 * @param bool $stripTags
	 *
	 * @return array
	 * @throws \ReflectionException
	 */
	public function validate($objDoctrineEntity=null, array $bypassFields=array(), $stripTags=true){
		$fullyQualifiedClassName= $this->fullyQualifiedClassName;
		$parts                  = preg_split("#\\\#", $fullyQualifiedClassName);
		$aggregator             = strtolower(end($parts));
		$arrClassPropsTypeName  = $this->getClassPropertyInfo($fullyQualifiedClassName);

		foreach($arrClassPropsTypeName as $intKey=>$objInfo){
			$strategy       = isset($objInfo->validationStrategy)?$objInfo->validationStrategy : null;
			$inputName      = $objInfo->name;
			if(in_array($inputName, $bypassFields)){
				continue;
			}
			if(!is_null($strategy)){
				if(method_exists($objDoctrineEntity, "is".$this->rinseFieldName($inputName))){
					$strGetter  = "is" . $this->rinseFieldName($inputName);
				}else{
					$strGetter  = "get" . $this->rinseFieldName($inputName);
				}
				$inputVal   = is_string($objDoctrineEntity->$strGetter())?trim($objDoctrineEntity->$strGetter()):$objDoctrineEntity->$strGetter();
				if(!preg_match("#REGEX#", $strategy)){
					$validationStrategy = self::$$strategy;
					$regex              = null;
				}else{
					preg_match("#(\{.*\})$#", $strategy, $matches);
					list(, $regex)  = $matches;
					$regex          = trim($regex, '\t\n\r\{\}');
					$validationStrategy = self::$VALIDATION_STRATEGY_REGEX;
				}
				$inputVal       = $this->validator->sanitize($inputVal, $stripTags);
				$this->validator->validate($inputVal, $validationStrategy, $inputName, $regex);
			}
		}
		return ($this->validator->getValidationBasket($aggregator));
	}

	/**
	 * @param array $formOptions
	 * @param bool $asArray
	 * @param null $objDoctrineEntity
	 *
	 * @return array|string
	 * @throws \ReflectionException
	 */
	public function buildFormFromDoctrineClass($formOptions=array(), $asArray=false, $objDoctrineEntity=null){
		$formOpts               = array('method'=>'post', 'action'=>'', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data', 'name'=>'doctrine_form');
		$formOptions            = array_merge($formOpts, $formOptions);
		$arrHTMLForm            = array();
		$fullyQualifiedClassName= $this->fullyQualifiedClassName;
		$parts                  = preg_split("#\\\#", $fullyQualifiedClassName);
		$aggregator             = strtolower(end($parts));
		$arrClassPropsTypeName  = $this->getClassPropertyInfo($fullyQualifiedClassName);
		$arrPHPMySQLPropTypes   = $this->fetchDoctrinePropertyTypes();
		$strHTMLForm            = "<form method='{$formOptions['method']}' enctype='{$formOptions['enctype']}' name='{$formOptions['name']}' action='{$formOptions['action']}' class='{$formOptions['class']}' >" . PHP_EOL;
		$arrHTMLForm['formOpen']= $strHTMLForm;
		foreach($arrClassPropsTypeName as $intKey=>$objInfo){
			$propertyType   = isset($objInfo->type)?$objInfo->type:"string";
			$inputName      = $objInfo->name;
			foreach($arrPHPMySQLPropTypes as $PHPType=>$MySQLEquivalent){
				if($propertyType == $PHPType || strtolower($propertyType) == $PHPType ){
					$getter1                = "get" . $this->rinseFieldName($inputName);
					$getter2                = "is"  . $this->rinseFieldName($inputName);
					$objEntity              = new $fullyQualifiedClassName();
					$classMethods           = $this->fetchClassMethods();
					$objInfo->aggregator    = $aggregator;
					if(in_array($getter1, $classMethods)){
						$objInfo->inputValue    = $objEntity->$getter1();
						if($objDoctrineEntity) {
							$objInfo->inputValue = $objDoctrineEntity->$getter1();
						}
					}else{
						$objInfo->inputValue    = $objEntity->$getter2();
						if($objDoctrineEntity) {
							$objInfo->inputValue = $objDoctrineEntity->$getter2();
						}
					}
					$htmlFormElement            = $this->createHTMLFormElement($objInfo);
					$strHTMLForm               .= $htmlFormElement;
					$arrHTMLForm[$inputName]    = $htmlFormElement;
				}
			}
		}

		$go         = isset($formOptions['btnLabel']) ? Octopus::translateString($formOptions['btnLabel']) : "Absenden";
		$btnID      = isset($formOptions['btnID']) ? $formOptions['btnID'] : "submit";
		$btnClass   = isset($formOptions['btnClass']) ? $formOptions['btnClass'] : "submit";
		$submit     = "<div class='{$this->formGroupWrapperClass}' style=''>" . PHP_EOL;
		$submit    .= "<input type='submit' name='submit' class='form-control {$btnClass} {$btnID}' id='{$btnID}' value='{$go}' />";
		$submit    .= "</div>" . PHP_EOL;

		$arrHTMLForm["submit"]      = $submit;
		$arrHTMLForm["formClose"]   =  "</form>";
		if($asArray) { return $arrHTMLForm; }
		return $strHTMLForm . $submit . "</form>" . PHP_EOL;
	}

	/**
	 * @param $fieldName
	 *
	 * @return string
	 */
	protected function rinseFieldName($fieldName){
		$arrName    = preg_split("#[_\-\s]+#",    $fieldName);
		$arrName    = array_map("ucfirst", $arrName);;
		$strName    = implode("", $arrName);
		return $strName;
	}

	/**
	 * @return array
	 * @throws \ReflectionException
	 */
	protected function fetchClassMethods(){
		$arrClassMethods    = [];
		$refClass           = new \ReflectionClass($this->fullyQualifiedClassName);
		$classMethods       = $refClass->getMethods();
		foreach($classMethods as $method){
			$arrClassMethods[]  = $method->getName();
		}
		return $arrClassMethods;
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->arrPostValues;
	}

	/**
	 * @param array $arrPostValues
	 */
	public function setData($arrPostValues) {
		$this->arrPostValues = $arrPostValues;
	}

	/**
	 * @param $blockData
	 */
	public function setBlockContent($blockData){
		$this->blockContent     = $blockData;
		if(!empty($blockData) && is_array($blockData)) {
			$this->blockContent = implode("\n", $blockData);
		}
	}

	/**
	 * @param $arrErrorFields
	 */
	public function setErrorFields($arrErrorFields) {
		$this->arrErrorFields = $arrErrorFields;
	}

	/**
	 * @return array
	 */
	public function getValidationStrategies() {
		return $this->validationStrategies;
	}


	/**
	 * @param $min
	 * @param $max
	 * @param string $id
	 * @param bool $optionsOnly
	 *
	 * @return string
	 */
	protected function getSliderDataList($min, $max, $id='item-quantity', $optionsOnly=false){
		$dataList   = (!$optionsOnly) ? "<datalist id='{$id}'>"  . PHP_EOL : "";
		for($i=$min; $i<=$max; $i++){
			$dataList  .= "<option value='{$i}'>{$i}</option>" . PHP_EOL ;
		}
		$dataList  .= (!$optionsOnly) ? "</datalist>" . PHP_EOL : "";
		return $dataList;
	}
}

/**
 * @param $text
 * @param string $domain
 *
 * @return mixed
 */
function __($text, $domain='poiz'){
	return $text;
}