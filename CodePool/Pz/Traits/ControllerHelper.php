<?php
	
	namespace CodePool\Pz\Traits;
	
	use Aura\Session\SessionFactory;
	use Carbon\Carbon;
	use CodePool\Pz\Poiz\Bridges\Octopus;
	use POiz\PoizCsvLister\Controller\PzCSVListerController;
	use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
	
	trait ControllerHelper {
		
		/**
		 * @param null $action
		 * @param array $arguments
		 * @param null $controller
		 * @param null $extensionName
		 * @param null $pluginName
		 * @param null $pageUid
		 * @param int $pageType
		 * @param bool $noCache
		 * @param bool $noCacheHash
		 * @param string $section
		 * @param string $format
		 * @param bool $linkAccessRestrictedPages
		 * @param array $additionalParams
		 * @param bool $absolute
		 * @param bool $addQueryString
		 * @param array $argumentsToBeExcludedFromQueryString
		 * @param null $addQueryStringMethod
		 *
		 * @return string
		 */
		public function makeLink($action = null, array $arguments = [], $controller = null, $extensionName = null, $pluginName = null, $pageUid = null, $pageType = 0, $noCache = false, $noCacheHash = false, $section = '', $format = '', $linkAccessRestrictedPages = false, array $additionalParams = [], $absolute = false, $addQueryString = false, array $argumentsToBeExcludedFromQueryString = [], $addQueryStringMethod = null) {
			$pageUid = (!$pageUid) ? $GLOBALS['TSFE']->id : $pageUid;
			return self::generateLink($action, $arguments, $controller, $extensionName, $pluginName, $pageUid, $pageType,  $noCache, $noCacheHash, $section, $format, $linkAccessRestrictedPages, $additionalParams, $absolute, $addQueryString, $argumentsToBeExcludedFromQueryString, $addQueryStringMethod);
		}
		
		/**
		 * @param string|mixed $suffix
		 */
		public static function addActionPageToSession($suffix=null) {
			$actionPage         = Octopus::getCurrentPageURL();
			$nameSpace          = 'POiz\PoizCsvLister\Controller\PzCSVListerManagerController';
			$session_factory    = new SessionFactory();
			$session            = $session_factory->newInstance($_COOKIE);
			$segment            = $session->getSegment($nameSpace);
			$history            = $segment->get('history', []);
			$history[]          = $actionPage;
			$segment->set('history', $history);
		}
		
		/**
		 * @return mixed|null
		 */
		public static function getPrevPage() {
			$nameSpace          = 'POiz\PoizCsvLister\Controller\PzCSVListerManagerController';
			$session_factory    = new SessionFactory();
			$session            = $session_factory->newInstance($_COOKIE);
			$segment            = $session->getSegment($nameSpace);
			$history            = $segment->get('history', []);
			if(!empty($history)){
				$offSet         = ($a = count($history) - 2) < 0 ? 0 : $a;
				$link           = $history[$offSet];
				unset($history[$offSet]);
				if($link ==count($history) - 1){
					unset($history[count($history) - 1]);
				}
				$history        = array_values($history);
				$segment->set('history', $history);
				return $link;
			}
			return null;
		}
		
		/**
		 * @param string $requestType
		 * @param string $formKey
		 *
		 * @return mixed|null
		 */
		public static function getArgumentsFromRequestVars($requestType="POST", $formKey="tx_poizcsvlister_pzcsvlister"){
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
		
		/**
		 * @param array $arrErrors
		 *
		 * @return array
		 */
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
		
		/**
		 * @param int $length
		 *
		 * @return string
		 */
		protected static function generateRandomHash($length = 6) {
			$characters = '0123456789ABCDEF';
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randomString;
		}
		
		/**
		 * @param $arrPayload
		 *
		 * @return mixed
		 */
		public static function getBlockFormUIDisplay($arrPayload) {
			$form           = $arrPayload['form'];
			$payload        = $arrPayload['payload'];
			$formIsDirty    = $arrPayload['formIsDirty'];
			$errors         = $arrPayload['errors'];
			$csrfToken      = $arrPayload['csrfToken'];
			$state          = $arrPayload['status'];
			
			$status         = false;
			$htmlForm       = !$formIsDirty ? '<div class=\'pz-form-wrapper\'>' . PHP_EOL : '';
			if ($errors) {
				$htmlForm .= '<div class=\'error-box ' . self::ERROR_WRAPPER_CLASS . ' \'>' . PHP_EOL;
				$htmlForm .= $errors . PHP_EOL;
				$htmlForm .= '</div>' . PHP_EOL;
			}
			$submit  = $form['submit'];
			$submit .= "<input type='hidden' name='csrfToken' value='{$csrfToken}' />";
			unset($form['submit']);
			$formOpen = $form['formOpen'];
			unset($form['formOpen']);
			$formClose = $form['formClose'];
			unset($form['formClose']);
			$htmlForm .= $formOpen . PHP_EOL;
			foreach ($form as $formKey => $formContent) {
				$htmlForm .= $formContent . PHP_EOL;
			}
			$htmlForm .= $submit . PHP_EOL;
			$htmlForm .= $formClose . PHP_EOL;
			$htmlForm .= !$formIsDirty ? '</div>' . PHP_EOL : '';
			
			if ($_POST && $_POST['submit']) {
				if (!$errors) {
					$feedBack   = '<div class=\'col-md-12\'>' . PHP_EOL;
					$feedBack  .= '<h2 class=\'pz-thank-you-heading\' style=\'text-align:center; font-weight:300; color:green;\'>' . PHP_EOL;
					$iLName     = method_exists($payload, "getName") ? "Die Eintrag: <strong>«{$payload->getName()}»</strong>" : "Die Daten ";
					$feedBack  .= "{$iLName} wurde erfolgreich {$state}!" . PHP_EOL;
					$feedBack  .= '</h2>' . PHP_EOL;
					$feedBack  .= '</div>' . PHP_EOL;
					$htmlForm   = $feedBack . $htmlForm;
					$status     = true;
				}
			}
			$arrPayload['status'] = $status;
			$arrPayload['form'] = $htmlForm;
			$arrPayload['html'] = $htmlForm;
			return $arrPayload;
		}
		
		/**
		 * @return mixed
		 */
		public static  function InitializeSession(){
			if ( (session_status() == PHP_SESSION_NONE) || (session_id() == '') ) {
				session_start();
			}
			return $_SESSION;
		}
		
		/**
		 * @param $segment
		 * @param $csrfToken
		 * @param $segmentName
		 *
		 * @return mixed
		 */
		public static  function reInitializeSessionSegment(&$segment, $csrfToken, $segmentName){
			$segment['csrfToken']   =  $csrfToken;
			$segment['endTime']     =  null;
			$segment['timeDiff']    =  0;
			$segment['startTime']   =  time();
			$_SESSION[$segmentName] = $segment;
			return $segment;
		}
		
		//TODO:  REFACTOR pzProcessCSRFToken....
		/**
		 * @param int $generateCheckOrDelete
		 * @param null $csrfTokenX
		 *
		 * @return bool|null
		 */
		public static function pzProcessCSRFToken($generateCheckOrDelete=1, $csrfTokenX=null){    // 1=>GENERATE, 2=>CHECK
			$sessionArray       = self::InitializeSession();
			$csrfToken          = self::generateRandomHash(128);
			$segmentName        = 'POiz\PoizCsvLister\Controller\PzCSVListerManagerController';
			$savedToken         = null;
			$lifeTime           = 60*5;
			
			if(!isset($sessionArray[$segmentName])){
				$segment                    = [];
				$sessionArray[$segmentName] = self::reInitializeSessionSegment($segment, $csrfToken, $segmentName);
				return true;
			}
			
			$segment            = $sessionArray[$segmentName];
			
			if(isset($segment['startTime']) && $segment['startTime'] ){
				$segment['endTime']     = time();
				$segment['timeDiff']    = (int)$segment['endTime'] -  (int)$segment['startTime'];
				$_SESSION[$segmentName] = $segment;
			}
			
			
			if(isset($segment['timeDiff']) &&  $segment['timeDiff'] >= $lifeTime){
				$segment                = self::reInitializeSessionSegment($segment, $csrfToken, $segmentName);
				return $segment['csrfToken'];
			}
			
			if($generateCheckOrDelete == 1){
				$segment                = self::reInitializeSessionSegment($segment, $csrfToken, $segmentName);
				return $segment['csrfToken'];
			}elseif($generateCheckOrDelete==2){
				if($csrfTokenX){
					$savedToken         = isset($segment['csrfToken']) ? $segment['csrfToken'] : null;
					return ($savedToken === $csrfTokenX) ? true : $savedToken;
				}else{
					return $segment['csrfToken'];
				}
			}
		}
		
		/**
		 * @param null $action
		 * @param array $arguments
		 * @param null $controller
		 * @param null $extensionName
		 * @param null $pluginName
		 * @param null $pageUid
		 * @param int $pageType
		 * @param bool $noCache
		 * @param bool $noCacheHash
		 * @param string $section
		 * @param string $format
		 * @param bool $linkAccessRestrictedPages
		 * @param array $additionalParams
		 * @param bool $absolute
		 * @param bool $addQueryString
		 * @param array $argumentsToBeExcludedFromQueryString
		 * @param null $addQueryStringMethod
		 *
		 * @return string
		 */
		public static function generateLink($action = null, array $arguments = [], $controller = null, $extensionName = null, $pluginName = null, $pageUid = null, $pageType = 0, $noCache = false, $noCacheHash = false, $section = '', $format = '', $linkAccessRestrictedPages = false, array $additionalParams = [], $absolute = false, $addQueryString = false, array $argumentsToBeExcludedFromQueryString = [], $addQueryStringMethod = null) {
			$pageUid = (!$pageUid) ? $GLOBALS['TSFE']->id : $pageUid;
			if(self::$renderingContext){
				$context = self::$renderingContext;
			}else{
				$dis        = self::$instance;
				$context    = $dis->controllerContext;
			}
			if($context->getUriBuilder()) {
				$uri = $context->getUriBuilder()->reset()
				               ->setTargetPageUid($pageUid)
				               ->setTargetPageType($pageType)
				               ->setNoCache($noCache)
				               ->setUseCacheHash(!$noCacheHash)
				               ->setSection($section)
				               ->setFormat($format)
				               ->setLinkAccessRestrictedPages($linkAccessRestrictedPages)
				               ->setArguments($additionalParams)
				               ->setCreateAbsoluteUri($absolute)
				               ->setAddQueryString($addQueryString)
				               ->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
				               ->setAddQueryStringMethod($addQueryStringMethod)
				               ->uriFor($action, $arguments, $controller, $extensionName, $pluginName);
				
				return $uri;
			}
			return "";
		}
		
		/**
		 * action render
		 *
		 * @return string
		 */
		public static function prependRawScriptsToHTML(&$html, $arrKeys=['dz_js', 'jq', 'cpl_js', 'cpl_css', 'fa', 'tbs', 'jqe']) {
			$scripts  = "";
			$scripts .= in_array('jq', $arrKeys)? "<script type='text/javascript' src='" . EXT_URL . "Resources/Public/Assets/js/lib/jquery-3.1.1.min.js'></script>" : "";
			$scripts .= in_array('cs_tt', $arrKeys)? "<script type='text/javascript' src='" . EXT_URL . "Resources/Public/Assets/js/jquery.poiz_tooltip.js'></script>" : "";
			$scripts .= in_array('dz_js', $arrKeys)? "<script type='text/javascript' src='" . EXT_URL . "Resources/Public/Assets/js/dropzoneX.js'></script>" : "";
			$scripts .= in_array('cpl_js', $arrKeys)? "<script type='text/javascript' src='" . EXT_URL . "Resources/Public/Assets/js/complot.js'></script>" : "";
			
			$scripts .= in_array('tbs', $arrKeys)? "<link type='text/css' rel='stylesheet' media='all' href='" . EXT_URL . "Resources/Public/Assets/css/bootstrap.min.css' />" : "";
			$scripts .= in_array('fa', $arrKeys)? "<link type='text/css' rel='stylesheet' media='all' href='" . EXT_URL . "Resources/Public/Assets/css/font-awesome.min.css' />" : "";
			$scripts .= in_array('cpl_css', $arrKeys)? "<link type='text/css' rel='stylesheet' media='all' href='" . EXT_URL . "Resources/Public/Assets/css/complot.css' />" : "";
			$html    = $scripts . $html;
			
			return $html;
		}
		
		/**
		 * @param ActionController $ctr
		 * @param array $scriptKeys
		 */
		public static function addHeaderScripts(ActionController $ctr, $scriptKeys=['dz_js', 'jq', 'cpl_js', 'cpl_css', 'fa', 'tbs', 'jqe']) {
			$scripts    = [
				'jq'        => "<script type='text/javascript' src='" . EXT_URL . "Resources/Public/Assets/js/lib/jquery-3.1.1.min.js'></script>",
				'jqe'       => "<script type='text/javascript' src='" . EXT_URL . "Resources/Public/Assets/js/lib/jquery.easing.1.3.js'></script>",
				'dz_js'     => "<script type='text/javascript' src='" . EXT_URL . "Resources/Public/Assets/js/dropzoneX.js'></script>",
				'cpl_js'    => "<script type='text/javascript' src='" . EXT_URL . "Resources/Public/Assets/js/complot.js'></script>",
				
				'tbs'       => "<link type='text/css' rel='stylesheet' media='all' href='" . EXT_URL . "Resources/Public/Assets/css/bootstrap.min.css' />",
				'fa'        => "<link type='text/css' rel='stylesheet' media='all' href='" . EXT_URL . "Resources/Public/Assets/css/font-awesome.min.css' />",
				'cpl_css'   => "<link type='text/css' rel='stylesheet' media='all' href='" . EXT_URL . "Resources/Public/Assets/css/complot.css' />",
			];
			
			if( is_array($scriptKeys) && !empty($scriptKeys)){
				foreach($scriptKeys as $scriptKey){
					if(array_key_exists($scriptKey, $scripts)){
						$ctr->response->addAdditionalHeaderData($scripts[$scriptKey]);
					}
				}
			}
		}
		
		/**
		 * @param $arrCsvData
		 * @param array $config
		 *
		 * @return string
		 */
		protected function renderObjectInTableView($arrCsvData, array $config=array()){
			$defaults       = array(
				'tblID'         => "",
				'tblClass'      => "table table-striped table-bordered table-hover table-condensed",
				'tHeadID'       => "",
				'tHeadClass'    => "",
				'tBodyID'       => "",
				'tBodyClass'    => "",
				'trClass'       => "",
				'thClass'       => "",
				'tdClass'       => "pz-cell",
			);
			$config         = array_merge($defaults, $config);
			$arrDataValues  = $arrCsvData->data;
			$arrHeadingList = $arrCsvData->titles;
			
			$lang           = $GLOBALS['TSFE']->lang;   // sys_language_isocode
			$locale         = self::getLocaleStringFromLang($lang);
			$dataTable      = "<div class=\"table-responsive\">";
			
			if($arrDataValues && $arrHeadingList) {
				$dataTable .= "<table class='" . $config['tblClass'] . "' id='" . $config['tblID'] . "'>";
				$dataTable .= "<thead class='" . $config['tHeadClass'] . "' id='" . $config['tHeadID'] . "'>";
				$dataTable .= "<tr class='" . $config['trClass'] . "' >";
				
				foreach ( $arrHeadingList as $iKey => $strTitle ) {
					$dataTable .= "<th class='" . $config['thClass'] . "'>{$strTitle}</th>";
				}
				$dataTable .= "</tr>";
				$dataTable .= "</thead>";
				$dataTable .= "<tbody class='" . $config['tBodyClass'] . "' id='" . $config['tBodyID'] . "'>";
				
				foreach ( $arrDataValues as $iKey => $arrData ) {
					$dataTable .= "<tr class='" . $config['trClass'] . "' >";
					foreach ( $arrData as $iKey2 => $propVal ) {
						if(
							(isset($this->settings['forms']['render_pix_uri_as_pix']) &&
							 ($this->settings['forms']['render_pix_uri_as_pix'] == "1")) ||
							TYPO3_MODE == 'BE'){
							if(preg_match("#\.(png|jpg|bmp|gif)$#", $propVal)){
								$propValClone   = $propVal;
								$pixAlt         = basename($propVal);
								$pixAltD        = '';
								if(!$this->urlIsValid($propVal)){
									if(!$this->urlIsValid(Octopus::getCurrentPageURL(true) . $propVal)) {
										$propVal = ICONS_URI . "no-image-icon.png";
										$pixAltD    = "<small>{$pixAlt}</small>";
									}
								}
								$propVal    = "<img src='{$propVal}' class='thumbnail pz-thumb' alt='{$pixAlt}' data-image-path='{$propValClone}' />" . $pixAltD;
							}
						}
						if ($dt = \DateTime::createFromFormat('Y-m-d H:i:s', $propVal) !== FALSE) {
							$carbonInst = Carbon::instance(new \DateTime($propVal));
							$carbon     = $carbonInst->locale($locale);
							$propVal    = $carbon->isoFormat('dddd, D\. MMMM YYYY \- hh:mm:ss');
							$propVal   .= "<br /><small>" . $carbon->diffForHumans() . "</small>";
						}
						
						$dataTable .= "<td class='" . $config['tdClass'] . "'>{$propVal}</td>";
					}
					$dataTable .= "</tr>";
				}
				$dataTable .= "</tbody>";
				$dataTable .= "</table>";
			}
			
			$dataTable      .= "</div>";
			
			return $dataTable;
		}
		
		/**
		 * @param $arrCsvData
		 * @param array $config
		 *
		 * @return string
		 */
		protected function renderObjectInTableViewBE($arrCsvData, array $config=array(), $csvID=null, $filePath=null){
			$defaults       = array(
				'tblID'         => "",
				'tblClass'      => "table table-striped table-bordered table-hover table-condensed",
				'tHeadID'       => "",
				'tHeadClass'    => "",
				'tBodyID'       => "",
				'tBodyClass'    => "",
				'trClass'       => "",
				'thClass'       => "",
				'tdClass'       => "pz-cell",
			);
			$config         = array_merge($defaults, $config);
			$arrDataValues  = $arrCsvData->data;
			$arrHeadingList = $arrCsvData->titles;
			$processor      = "/typo3conf/ext/poiz_csv_lister/CodePool/Pz/Ajax/Ajax.php";
			$dataTable      = "<div class=\"table-responsive\">";
			$arrHeadingList['Actions']  = "Actions";
			$lang           = $GLOBALS['BE_USER']->user['lang'];
			$locale         = self::getLocaleStringFromLang($lang);
			if($arrDataValues && $arrHeadingList) {
				$dataTable .= "<table class='" . $config['tblClass'] . "' id='" . $config['tblID'] . "'>";
				$dataTable .= "<thead class='" . $config['tHeadClass'] . "' id='" . $config['tHeadID'] . "'>";
				$dataTable .= "<tr class='" . $config['trClass'] . "' >";
				
				foreach ( $arrHeadingList as $iKey => $strTitle ) {
					$dataTable .= "<th class='" . $config['thClass'] . "'>{$strTitle}</th>";
				}
				$dataTable .= "</tr>";
				$dataTable .= "</thead>";
				$dataTable .= "<tbody class='" . $config['tBodyClass'] . "' id='" . $config['tBodyID'] . "'>";
				
				foreach ( $arrDataValues as $iKey => $arrData ) {
					$arrData['actions'] = null;
					$actionField        = $arrHeadingList[0];
					$rowID              = "pz-csv-t-row-{$iKey}";
					$dataTable .= "<tr class='" . $config['trClass'] . "' id='{$rowID}' >";
					foreach ( $arrData as $iKey2 => $propVal ) {
						if(preg_match("#\.(png|jpg|bmp|gif)$#", $propVal)){
							$propValClone   = $propVal;
							$pixAlt         = basename($propVal);
							$pixAltD        = '';
							if(!$this->urlIsValid($propVal)){
								if(!$this->urlIsValid(Octopus::getCurrentPageURL(true) . $propVal)) {
									$propVal = ICONS_URI . "no-image-icon.png";
									$pixAltD    = "<small>{$pixAlt}</small>";
								}
							}
							$propVal    = "<img src='{$propVal}' class='thumbnail pz-thumb' alt='{$pixAlt}' data-image-path='{$propValClone}' />" . $pixAltD;
						}
						if($iKey2 == 'actions'){
							$actionFieldVal     = $arrData[$actionField];
							$dataChunks         = " data-field='{$actionField}' data-field-val='{$actionFieldVal}' data-uid='{$csvID}' data-row-id='#{$rowID}' data-path='{$filePath}'";
							$propVal = "<div class='pz-actions-box col-md-12 no-lr-pad'>";
							$propVal.= "<span class='pz-action fa fa-save col-md-6 no-lr-pad'  data-processor='{$processor}' data-action='updateEntry' {$dataChunks} data-tip='Update Entry'></span>";
							$propVal.= "<span class='pz-action fa fa-trash col-md-6 no-lr-pad' data-processor='{$processor}' data-action='trashEntry'  {$dataChunks} data-tip='Delete Entry'></span>";
							$propVal.= "</div>";
						}
						$dataTable .= "<td class='" . $config['tdClass'] . "'>" . PHP_EOL;
						if(!preg_match("#<img|pz\-actions\-box#", $propVal)){
							//$dataTable .= "<textarea class='pz-csv-field form-control' rows='1'>{$propVal}</textarea>" . PHP_EOL;
							$dataTable .= "<input name='' type='text' class='pz-csv-field form-control' value='" . str_replace("'", "&#39;", htmlentities($propVal)) . "' data-update-field='{$iKey2}'  data-tip='Click to Edit'/>" . PHP_EOL;
							if ($dt = \DateTime::createFromFormat('Y-m-d H:i:s', $propVal) !== FALSE) {
								$carbonInst = Carbon::instance(new \DateTime($propVal));
								$carbon     = $carbonInst->locale($locale);
								$dataTable .= "<small>" . $carbon->isoFormat('dddd, D\. MMMM YYYY \- hh:mm:ss') . "</small><br />";
								$dataTable .= "<small>" . $carbon->locale($locale)->diffForHumans() . "</small>";
							}
						}else{
							$dataTable .= $propVal;
						}
						$dataTable .= "</td>" . PHP_EOL;
					}
					$dataTable .= "</tr>";
				}
				$dataTable .= "</tbody>";
				$dataTable .= "</table>";
			}
			
			$dataTable      .= "</div>";
			
			return $dataTable;
		}
		
		public static function getLocaleStringFromLang($lang){
			/** @var  $conn \Doctrine\DBAL\Connection */
			$conn   = $GLOBALS['eMan']->getConnection();
			$sql    = "SELECT DISTINCT lg_collate_locale AS locale FROM `static_languages` WHERE deleted=:DEL ";
			$sql   .= "AND lg_iso_2=:LANG_UC OR lg_typo3=:LANG_LC";
			$stm    = $conn->prepare($sql);
			try{
				$stm->execute(['DEL'=>0, 'LANG_UC'=>strtoupper($lang), 'LANG_LC'=>strtolower($lang)]);
				$result = $stm->fetchAll(\PDO::FETCH_OBJ);
				return($result) ? $result->locale : null;
			}catch (\Exception $e){
			
			}
			return $lang . "_" . strtoupper($lang);
			
		}
		/**
		 * @param string $section
		 * @param null $uid
		 *
		 * @return string
		 */
		protected function getCSVFilesAsList ($section="BE", $uid=null) {
			$this->injectControllerDependencies($section);
			self::$renderingContext = $this->getControllerContext();
			$sql            = "SELECT * FROM `" . TBL_CSV_FILE . "` ";
			$dBal           = $this->eMan->getConnection();
			$stmt           = $dBal->executeQuery($sql, []);
			$csvFiles       = $stmt->fetchAll(\PDO::FETCH_OBJ);
			$output         = "";
			if($csvFiles){
				$output    .= "<ul class='list-group list-unstyled'>";
				foreach($csvFiles as $csvFile){
					$active = $csvFile->uid == $uid ? "pz-active" : "";
					$link   = self::makeLink("viewList", ['uid'=>$csvFile->uid, 'filePath'=>$csvFile->file_path, 'fileName'=>$csvFile->file_name]);
					$output.= "<li class='list-group-item pz-list-group {$active}'>";
					$output.= "<a class='pz-link' href='{$link}'>{$csvFile->file_name}</a>";
					$output.= "</li>";
				}
				$output	   .= "</ul>";
			}
			return $output;
		}
		
		/**
		 * @param string $section
		 */
		private function injectControllerDependencies($section='BE') {
			self::addActionPageToSession($section);
			
			$objectManager               = (!$this->objectManager)      ? $this->objectManager : $this->objectManager;
			$this->persistenceManager    = (!$this->persistenceManager) ? $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')  : $this->persistenceManager;
			$this->csvFileRepository     = (!$this->csvFileRepository)  ? $objectManager->get('POiz\\PoizCsvLister\\Domain\\Repository\\PzCSVFileRepository')   : $this->csvFileRepository;
		}
		
		/**
		 * @return array
		 */
		protected function getHTMLTableConfig(){
			return [
				'tblID'         => "pz-csv-table-fe",
				'tblClass'      => "pz-csv-table table table-striped table-bordered table-hover table-condensed",
				'tHeadID'       => "pz-csv-t-head",
				'tHeadClass'    => "pz-csv-t-head",
				'tBodyID'       => "pz-csv-t-body",
				'tBodyClass'    => "pz-csv-t-body",
				'trClass'       => "pz-csv-t-row pz-t-row",
				'thClass'       => "pz-csv-th",
				'tdClass'       => "pz-csv-t-cell",
			];
		}
		private function urlIsValid($url){
			$ch     = curl_init($url);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			if($code == 200){
				$status = true;
			}else{
				$status = false;
			}
			curl_close($ch);
			return $status;
		}
		
	}