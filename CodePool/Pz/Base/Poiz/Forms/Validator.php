<?php

namespace CodePool\Pz\Base\Poiz\Forms;

use CodePool\Pz\Base\Poiz\Forms\ErrorLogger;

class Validator {
	/**
	 * @var ErrorLogger
	 */
	protected $error_bag;

	/**
	 * @var array
	 */
	protected $clean_data = array();

	/**
	 * @var string
	 */
	protected $form_group;

	/**
	 * Validator constructor.
	 */
	public function __construct(){
		$this->error_bag            = new ErrorLogger();
		if(!isset($GLOBALS['global_store'])){
			require __DIR__ . '/Translations/Translations.php';
			if(isset($global_store)){
				$GLOBALS['global_store']    = $global_store;
			}else{
				$GLOBALS['global_store']    = array();
			}
		}
	}

	/**
	 * @return ErrorLogger
	 */
	public function getErrorBag () {
		return $this->error_bag;
	}

	/**
	 * @param $form_fields_array
	 * @param string $form_group
	 *
	 * @return array
	 */
	public function screen($form_fields_array, $form_group="zerm") {
		$this->form_group                       =$form_group;
		$sanitized_ar                           = array();
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$guest_book_payload                 = $_POST[$form_group];

			foreach($form_fields_array as $field_name=>$filter_type){
				$strip_tags                     = true;
				if($filter_type == "html" || $filter_type == "raw"){
					$strip_tags                 = true;
				}
				if($filter_type == "bool"){
					$guest_book_payload[$field_name] = ( ($fv = @$guest_book_payload[$field_name])=='1' )?true:false;
				}
				$sanitized_val                  = $this->sanitize_input(@$guest_book_payload[$field_name], $strip_tags);
				$sanitized_ar[$field_name]      = $this->filter($sanitized_val, $filter_type, $field_name);
			}
		}
		return array("{$this->form_group}"=>$sanitized_ar, 'errors'=>$this->form_has_errors());
	}

	/**
	 * @param $data
	 * @param bool $strip_tags
	 *
	 * @return int|string
	 */
	private function sanitize_input($data, $strip_tags=false) {
		if($data == '0'){
			return 0;
		}
		if($strip_tags){
			$data = strip_tags($data);
		}
		return htmlspecialchars(stripslashes( trim($data) ) );
	}

	/**
	 * @param $sanitized_val
	 * @param $filter_type
	 * @param $error_tag
	 *
	 * @return bool|float|null
	 */
	private function filter($sanitized_val, $filter_type, $error_tag ) {
		$global_store   = $GLOBALS["global_store"];
		$lang           = $global_store['active_lang'];
		$return_data    = null;

		switch($filter_type){
			case "tel":
				if(!preg_match('#^(\+\d{1,3})?\s?(\d{2,4})\s?(\d{2,4})\s?(\d{2,4})\s?(\d{2,4})$#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['tel'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "fax":
				if(!preg_match('#^(\+\d{1,3})?\s?(\d{2,4})\s?(\d{2,4})\s?(\d{2,4})\s?(\d{2,4})$#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['fax'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "int":
				if(!preg_match('#^(\-?\d{1,})(\d)?#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['num'][$lang]);
				}
				$return_data    = doubleval($sanitized_val);
				break;

			case "num":
				if(!preg_match('#^([\-\d]{1,})(\.)?(\d)*$#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['num'][$lang]);
				}
				$return_data    = doubleval($sanitized_val);
				break;

			case "bool":
				$return_data        = boolval($sanitized_val);
				break;

			case "date":
				if(!preg_match('#(\d{4})(\-)(\d{2})(\-)(\d{2})#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['date'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "string":
				if(!preg_match('#(^[a-z])([\w\.\-\(\)\ ])*\w*$ui#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "misc":
				if(!preg_match('#(^[a-zA-z])?([\w\.\-\ ])*\w*$#ui', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "alnum":
				if(!preg_match('#(^[a-z0-9\-\+])([\w\.\-\\:\;\+\(\)\ ])*\w*$#ui', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "city":
			case "state":
				if(!preg_match('#(^[a-z0-9\-\+])([\w\.\-\\:\;\+\(\)\/\ ])*\w*$ui#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "password":
				if(!preg_match('#(^[a-z0-9\-\+_\}\{\(\)])([\w\.\-\\:\;\+\(\)\/\}\{\(\)\ ])*\w*$ui#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "email":
				if (!filter_var($sanitized_val, FILTER_VALIDATE_EMAIL)) {
					$this->error_bag->append_to_log($error_tag, $global_store['error']['email'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "raw":
				$return_data    =  $sanitized_val;
				break;

			case "html":
				$return_data    = htmlspecialchars(stripslashes( trim($sanitized_val) ) );
				break;

			default:
				$return_data    = htmlspecialchars(stripslashes( trim($sanitized_val) ) );
				break;
		}

		return $return_data;
	}

	/**
	 * @param $data
	 * @param bool $strip_tags
	 *
	 * @return int|string
	 */
	public function sanitize($data, $strip_tags=false) {
		if($data == '0'){
			return 0;
		}
		if($strip_tags){
			$data = strip_tags($data);
		}
		return htmlspecialchars(stripslashes( trim($data) ) );
	}

	/**
	 * @param $sanitized_val
	 * @param $filter_type
	 * @param $error_tag
	 *
	 * @return bool|float|null
	 */
	public function validate($sanitized_val, $filter_type, $error_tag ) {
		$global_store   = $GLOBALS["global_store"];
		$lang           = $global_store['active_lang'];
		$return_data    = null;

		switch($filter_type){
			case "tel":
				if(!preg_match('#^(\+\d{1,3})?\s?(\d{2,4})\s?(\d{2,4})\s?(\d{2,4})\s?(\d{2,4})$#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['tel'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "fax":
				if(!preg_match('#^(\+\d{1,3})?\s?(\d{2,4})\s?(\d{2,4})\s?(\d{2,4})\s?(\d{2,4})$#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['fax'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "int":
				if(!preg_match('#^(\-?\d{1,})(\d)?#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['num'][$lang]);
				}
				$return_data    = doubleval($sanitized_val);
				break;

			case "num":
				if(!preg_match('#^([\-\d]{1,})(\.)?(\d)*$#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['num'][$lang]);
				}
				$return_data    = doubleval($sanitized_val);
				break;

			case "bool":
				$return_data        = boolval($sanitized_val);
				break;

			case "date":
				if(!preg_match('#(\d{4})(\-)(\d{2})(\-)(\d{2})#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag, $global_store['error']['date'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "string":
				if(!preg_match('#(^[a-z])([\w\.\-\(\)\ ])*\w*$ui#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "misc":
				if(!preg_match('#(^[a-zA-z])?([\w\.\-\ ])*\w*$#ui', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "alnum":
				if(!preg_match('#(^[a-z0-9\-\+])([\w\.\-\\:\;\+\(\)\ ])*\w*$#ui', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "city":
			case "state":
				if(!preg_match('#(^[a-z0-9\-\+])([\w\.\-\\:\;\+\(\)\/\ ])*\w*$ui#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "password":
				if(!preg_match('#(^[a-z0-9\-\+_\}\{\(\)])([\w\.\-\\:\;\+\(\)\/\}\{\(\)\ ])*\w*$ui#', $sanitized_val)){
					$this->error_bag->append_to_log($error_tag,  $global_store['error']['string'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "email":
				if (!filter_var($sanitized_val, FILTER_VALIDATE_EMAIL)) {
					$this->error_bag->append_to_log($error_tag, $global_store['error']['email'][$lang]);
				}
				$return_data    =  $sanitized_val;
				break;

			case "raw":
				$return_data    =  $sanitized_val;
				break;

			case "html":
				$return_data    = htmlspecialchars(stripslashes( trim($sanitized_val) ) );
				break;

			default:
				$return_data    = htmlspecialchars(stripslashes( trim($sanitized_val) ) );
				break;
		}

		$this->clean_data[$error_tag]      = $sanitized_val;
		return $return_data;
	}

	/**
	 * @param string $key
	 *
	 * @return array
	 */
	public function getValidationBasket($key='form_group'){
		return array($key=>$this->clean_data, 'errors'=>$this->form_has_errors());
	}

	/**
	 * @return array|bool
	 */
	public function form_has_errors(){
		if( ($errs = $this->error_bag->getErrors()) && !empty($errs) ){
			//JUST RETURN THE ERRORS SO YOU CAN DIRECTLY ACCESS IT
			//RATHER THAN JUST THE BOOLEAN TRUE AND YET NEEDING TO
			//MAKE A CALL TO THE getErrors() METHOD AGAIN
			return $errs;   //true;
		}
		return false;
	}

	/**
	 * @param array|mixed $form_fields_array
	 * @param string $form_group
	 *
	 * @return array
	 */
	public function screen_conditionally($form_fields_array, $form_group="zerm") {
		$sanitized_ar = array();
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			$guest_book_payload = $_POST[$form_group];


			if (!empty($guest_book_payload) && !empty($form_fields_array)) {
				foreach ($form_fields_array as $field_name => $filter_type) {
					$strip_tags = TRUE;
					if ($filter_type == "html" || $filter_type == "raw") {
						$strip_tags = TRUE;
					}
					$sanitized_val             = $this->sanitize_input(@$guest_book_payload[$field_name], $strip_tags);
					$sanitized_ar[$field_name] = $this->filter($sanitized_val, $filter_type, $field_name);
				}
			}
		}
		return $sanitized_ar;
	}

	/**
	 * @param mixed $errors
	 * @param bool $addFields
	 *
	 * @return string
	 */
	public function render_errors($errors, $addFields=false){
		$global_store       = $GLOBALS['global_store'];
		$lang               = $GLOBALS['global_store']['active_lang'];
		$error_msg_text = '';
		if(!empty($errors) && sizeof($errors) > 0){
			//RENDER ERRORS AS HTML...
			$error_msg_text     .= "<div class='error_box col-md-12'>\n";
			$error_msg_text     .= "<div class='col-md-12 error_warning'>" . __(@$global_store['error']['recheck_data'][$lang], "cpl") . "</div>\n";

			if($addFields) {
				$error_msg_text     .= "<div class='error_details col-md-12'>\n";
				foreach ($errors as $field_name => $error_msg) {
					$fieldNameTrans = __($field_name, "cpl");
					$error_msg_text .= "<div class='error_combo_wrapper col-md-12'>\n";
					$error_msg_text .= "<span class='error_field_name col-md-4'>" . $fieldNameTrans . ":</span>\n";
					$error_msg_text .= "<span class='error_field_msg col-md-8'>" . __($error_msg, "cpl" ) . "</span>\n";
					$error_msg_text .= "</div>\n";
				}
				$error_msg_text     .= "</div>\n";
			}
			$error_msg_text     .= "</div>\n";
			return $error_msg_text;
		}
		return '';
	}
} 