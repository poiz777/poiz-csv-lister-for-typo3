<?php
namespace POiz\PoizCsvLister\Controller;

use CodePool\Pz\Base\Poiz\Forms\FormBaker;
use CodePool\Pz\Base\Poiz\Forms\Validator;
use CodePool\Pz\FormObjects\PzCSVFileForm;
use CodePool\Pz\Poiz\Bridges\Octopus;
use CodePool\Pz\Traits\ControllerHelper;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use POiz\PoizCsvLister\Domain\Model\PzCSVFile;
use POiz\PoizCsvLister\Domain\Repository\PzCSVFileRepository;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use ParseCsv\Csv;
use CodePool\Pz\DataObjects\PzCsvFileEntity;
use Pimple\Container;

require_once  __DIR__ . "/../../config/pimple_config.php";

/**
 * PzCSVListerManagerController
 */
class PzCSVListerManagerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	use ControllerHelper;

	/**
	 * @var string
	 */
	const ERROR_WRAPPER_CLASS = 'col-md-8';
	
	/**
	 * @var EntityManager
	 */
	protected static $em = NULL;
	
	/**
	 * @var EntityManager
	 */
	protected $eMan = NULL;
	
	/**
	 * @var Container
	 */
	protected $container = NULL;
	
	/**
	 * csvFileRepository
	 *
	 * @var \POiz\PoizCsvLister\Domain\Repository\PzCsvFileRepository
	 */
	protected $csvFileRepository = NULL;
	
	/**
	 * $persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
	 */
	protected $persistenceManager = NULL;
	
	/**
	 * $persistenceManager
	 *
	 * @var PzCSVListerManagerController
	 */
	protected static $instance = NULL;
	
	/**
	 * objectManager
	 *
	 * @var ObjectManager
	 */
	protected $objectManager = NULL;
	
	/**
	 * objectManager
	 *
	 * @var RenderingContext
	 */
	protected static $renderingContext = NULL;

	/**
	 * PzCSVListerManagerController constructor.
	 */
	public function __construct() {
		parent::__construct();
		if(isset($GLOBALS['container'])){
			$this->container    = $GLOBALS['container'];
			$this->eMan         = $this->container['E_MAN'];
			$GLOBALS['eMan']    = $this->eMan;
			self::$em           = $this->eMan;
		}
		self::$instance         = $this;
		$this->objectManager    = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
	}

	public function manageAction(){
		global $BE_USER;
		$this->injectControllerDependencies('BE');
		self::$renderingContext = $this->getControllerContext();

		if (!$BE_USER->isAdmin()) {
			return '<div class=\'well\'>You must be <a href=\'/typo3\' target=\'_blank\'>logged in as Administrator</a> to access this Page!</div>';
		}

		$getVars        = $_GET['tx_poizgallery_web_poizgallerypzgallerymanager'];
		$csvID          = $getVars['csv'];
		$arrFormPayLoad = self::prepareFormBasedAction($this->csvFileRepository, 'CodePool\Pz\\FormObjects\\PzCSVFileForm', 'POiz\\PoizCsvLister\\Domain\\Model\\PzCSVFile', 'poiz_csv_lister.miscellaneous.save', $csvID);
		$html = $arrFormPayLoad['form'];
		self::addHeaderScripts($this,  ['dz_js', 'jq', 'cpl_js', 'cpl_css', 'fa', 'tbs', 'jqe']);
		$html = self::prependRawScriptsToHTML($html);
		$this->view->assign('payload', ['html'=>$html,'activeLink'=>'manage','back'=>self::getPrevPage()]);
	}

	public function buildAction() {
		$this->injectControllerDependencies();
		$sql        = "SELECT T.* FROM `" . TBL_CSV_FILE . "` AS T GROUP BY T.uid ORDER BY T.file_name ASC ";
		$dBal       = $this->eMan->getConnection();
		$stmt       = $dBal->executeQuery($sql, []);
		$objCSV     = $stmt->fetchAll(\PDO::FETCH_OBJ);
		$csvData    = [];
		if ($objCSV) {
			foreach ($objCSV as $csv) {
				$csvData[]  = $this->csvFileRepository->findByUid($csv->uid);
			}
		}

		$html = "";
		$this->view->assign('payload', [
			'csvData'       => $csvData,
			'activeLink'    => 'build',
			'back'          => self::getPrevPage(),
			'html'          => self::prependRawScriptsToHTML($html, ['dz_js', 'jq', 'cpl_js', 'cpl_css', 'fa', 'tbs', 'jqe' ])
		]);
	}

	public function updateFormAction() {
		self::$renderingContext = $this->getControllerContext();
		global $BE_USER;
		if (!$BE_USER->isAdmin()) {
			return '<div class=\'well\'>You must be <a href=\'/typo3\' target=\'_blank\'>logged in as Administrator</a> to access this Page!</div>';
		}
		$this->injectControllerDependencies('BE');
		$getVars        = $_GET['tx_poizcsvlister_web_poizcsvlisterpzcsvlistermanager'];
		$csvID              = $getVars['csv'];
		$arrFormPayLoad = self::prepareFormBasedAction($this->csvFileRepository, 'CodePool\Pz\\FormObjects\\PzCSVFileForm','POiz\\PoizCsvLister\\Domain\\Model\\PzCSVFile', 'poiz_csv_lister.miscellaneous.save', $csvID);
		$html = $arrFormPayLoad['form'];
		self::addHeaderScripts($this,  ['dz_js', 'jq', 'cpl_js', 'cpl_css', 'fa', 'tbs', 'jqe']);
		$html = self::prependRawScriptsToHTML($html);
		$this->view->assign('payload', ['html'=>$html,'activeLink'=>'build','back'=>self::getPrevPage()]);
	}

	public function previewAction() {
		$this->injectControllerDependencies('BE');
		/**@var PzCSVFile $csvFile */
		/**@var Connection $dBal */
		/**@var EntityManager $eMan */
		$csvFiles       = $this->eMan->getRepository('CodePool\Pz\DataObjects\PzCsvFileEntity')->findAll();
		$output         = "";
		if($csvFiles){
			$output    .= "<ul class='list-group list-unstyled'>";
			foreach($csvFiles as $csvFile){
				$link   = self::makeLink("viewList", ['uid'=>$csvFile->getUid(), 'filePath'=>$csvFile->getFilePath(), 'fileName'=>$csvFile->getFileName()]);
				$output.= "<li class='list-group-item pz-list-group'>";
				$output.= "<a class='pz-link' href='{$link}'>{$csvFile->getFileName()}</a>";
				$output.= "</li>";
			}
		}

		$html   = self::prependRawScriptsToHTML($output);
		$this->view->assign('payload', ['html'=> $html,'activeLink'=>'preview','back'=>self::getPrevPage()]);
	}

	public function deleteAction(){
		/**@var PzCsvFileEntity $csvFile */
		/**@var Connection $dBal */
		/**@var EntityManager $eMan */
		self::$renderingContext = $this->getControllerContext();
		global $BE_USER;
		if (!$BE_USER->isAdmin()) {
			return '<div class=\'well\'>You must be <a href=\'/typo3\' target=\'_blank\'>logged in as Administrator</a> to access this Page!</div>';
		}
		$this->injectControllerDependencies('BE');
		$getVars        = $_GET['tx_poizcsvlister_web_poizcsvlisterpzcsvlistermanager'];
		$csvID          = $getVars['csv'];

		// GET THE CSV OBJECT WITH THE ID = $csvID
		$csvFile        =  $this->eMan->getRepository('CodePool\Pz\DataObjects\PzCsvFileEntity')->find($csvID);
		$statusMsg      = "Der Eintrag: \"{$csvFile->getFileName()}\" konnte nicht gelöscht werden!";

		// DELETE THE PHYSICAL CSV FILE IF IT EXIST...
		if($csvFile){
			$csvFilePath    = realpath(SITE_ROOT . $csvFile->getFilePath());
			if($csvFilePath && file_exists($csvFilePath)){
				unlink($csvFilePath);
			}
			// THEN, DELETE THE ENTRY IN DB...
			$this->eMan->remove($csvFile);
			$this->eMan->flush();
			$statusMsg      = "Der Eintrag \"{$csvFile->getFileName()}\" wurde erfolgreich gelöscht!";
		}
		// NOW, REDIRECT TO MANAGEMENT SCREEN...
		return $this->redirect(
			'viewList', 'PoizImageSliderManager', 'pzp_slider', ['status'=>$statusMsg]);

	}

	public function viewListAction(){
		$tableConfig    = [
			'tblID'         => "pz-csv-table-be",
			'tblClass'      => "pz-csv-table table table-striped table-bordered table-hover table-condensed",
			'tHeadID'       => "pz-csv-t-head",
			'tHeadClass'    => "pz-csv-t-head",
			'tBodyID'       => "pz-csv-t-body",
			'tBodyClass'    => "pz-csv-t-body",
			'trClass'       => "pz-csv-t-row pz-t-row",
			'thClass'       => "pz-csv-th",
			'tdClass'       => "pz-csv-t-cell",
		];

		$args       = $this->request->getArguments();
		$csvList    = $this->getCSVFilesAsList('BE', $args['uid']);
		$filePath   = realpath(SITE_ROOT . trim($args['filePath'], "/"));
		$arrCsvData = new Csv($filePath);
		$strOut     = "";

		if($arrCsvData){
			$strOut = $this->renderObjectInTableViewBE($arrCsvData, $tableConfig, $args['uid'], $filePath);
		}

		$html   = self::prependRawScriptsToHTML($strOut);
		$this->view->assign('payload', ['html'=>$html,'activeLink'=>'preview','title'=>$args['fileName'], 'csvList'=>$csvList, 'back'=>self::getPrevPage()]);
	}


	/**
	 * @param $repository
	 * @param string $formClass
	 * @param string $className
	 * @param string $sendText
	 * @param null $id
	 *
	 * @return array
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
	 * @throws \ReflectionException
	 */
	protected function prepareFormBasedAction($repository, $formClass = 'CodePool\Pz\\FormObjects\\PzCSVFileForm', $className = 'POiz\\PoizCsvLister\\Domain\\Model\\PzCSVFile', $sendText = 'poiz_csv_lister.miscellaneous.save', $id = NULL) {
		/**@var PzCSVFileRepository $repository */
		/**@var PzCSVFileForm $pzFormObj */
		/**@var PzCSVFile $objPP */
		$validator      = new Validator();
		$errors         = NULL;
		$formIsDirty    = FALSE;
		$pzFormObj      = new $formClass();
		$allFine        = "erstellt";
		$objPP          = new $className();
		$csvID          = null;
		
		$csrfToken      = !isset($_POST['submit']) ? self::pzProcessCSRFToken(1) : null;

		#FORM-BAKER OBJECT GENERATION
		if ($id) {
			$objPP      = $repository->findByUid($id);;
			$pzFormObj->autoSetClassProps(Octopus::customEntityToArray($objPP));
			if ($formClass === 'CodePool\Pz\\FormObjects\\PzCSVFileForm') {
				$pzFormObj->setPhotoLibrary($pzFormObj);
			}
			$csvID      = $id;
		}

		$FormObjFB      = new FormBaker($formClass);
		$arrFormOpts    = array(
			'method'   => 'POST',
			'class'    => 'form-horizontal',
			'name'     => 'pcf_form',
			'action'   => '',
			'enctype'  => 'multipart/form-data',
			'btnLabel' => $sendText,
			'btnClass' => 'pz-submit pz_form_submit',
			'btnID'    => 'pz_form_submit'
		);
		$htmlForm       = $FormObjFB->buildFormFromDoctrineClass($arrFormOpts, TRUE, $pzFormObj);
		
		if ($_POST && $_POST['submit']) {
			$key        = strtolower(array_pop(explode('\\', $formClass)));
			$postVarsFO = $_POST[$key];
			$csrfToken  = $_POST['csrfToken'];
			
			$pzFormObj->autoSetClassProps(Octopus::arrayToObjectRecursive($postVarsFO));
			if (($csrfToken = self::pzProcessCSRFToken(2, $csrfToken)) !== TRUE) {
				$htmlForm    = $FormObjFB->buildFormFromDoctrineClass($arrFormOpts, TRUE, $pzFormObj);
				$errors      = '<h1>Your Session has now Expired.... Please recommence using this pre-populated Form...</h1>';
				$formIsDirty = TRUE;
				$allFine     = FALSE;
			}else {
				$csrfToken          = $_POST['csrfToken'];
				$validationStatusRV = $FormObjFB->validate($pzFormObj);
				#GET ERRORS ARRAY
				$errorsRV       = $validationStatusRV['errors'];
				$IDFreeErrorsRV = is_array($errorsRV) ? self::screenOffErrorsWithIDWithinKeys($errorsRV) : array();
				
				if (empty($IDFreeErrorsRV)) {
					//SAVE DATA TO DATABASE
					$objPP              = Octopus::autoSetClassProps($objPP, $postVarsFO);
					if ($id) {
						$formIsDirty    = FALSE;
						$allFine        = "aktualisiert";
						$repository->update($objPP);
						$this->persistenceManager->persistAll();
					}else {
						$repository->add($objPP);
						$this->persistenceManager->persistAll();
						$uid    = $objPP->getUid();
						$pzFormObj->setUid($uid);
						$formIsDirty = FALSE;
						$allFine     = "erstellt";
					}
					self::storeUploadedCSV($this->csvFileRepository, $this->persistenceManager, $pzFormObj);
					$htmlForm    = $FormObjFB->buildFormFromDoctrineClass($arrFormOpts, TRUE, $pzFormObj);
					
				}else {
					$FormObjFB->setErrorFields(array_keys($errorsRV));
					$htmlForm    = $FormObjFB->buildFormFromDoctrineClass($arrFormOpts, TRUE, $pzFormObj);
					$errors      = $validator->render_errors($IDFreeErrorsRV, TRUE);
					$formIsDirty = TRUE;
					$allFine     = "erstellt";
				}
			}
		}
		
		PzCSVFileForm::setInstance($pzFormObj);
		$arrPayload = array(
			'id'          => $csvID,
			'form'        => $htmlForm,
			'errors'      => $errors,
			'payload'     => $pzFormObj,
			'formIsDirty' => $formIsDirty,
			'csrfToken'   => $csrfToken,
			'status'      => $allFine
		);
		$form       = self::getBlockFormUIDisplay($arrPayload)['form'];
		
		return array('form' => $form, 'payload' => $arrPayload);
	}

	/**
	 * @param $csvRepo
	 * @param $persistenceManager
	 * @param $pzFormObject
	 *
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
	 * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
	 */
	protected static function storeUploadedCSV($csvRepo, $persistenceManager, $pzFormObject){
		/**@var PzCSVFileForm $pzFormObject*/
		/**@var PzCSVFile $objCSVFile*/
		/**@var PzCSVFileRepository $csvRepo*/
		/**@var PersistenceManager $persistenceManager*/

		// GET ALL STORED CSV FILES AND REBUILD THEM FOR SAVE....
		// WITH EACH ITERATION, SAVE ALSO THE RELATIONS IN THE RELATIONS TABLE.
		$tmpDocPath             = PLG_TEMP_STORE . "TMP/TMP.txt";
		$tmpDocStr              = (file_exists($tmpDocPath) && $str = file_get_contents($tmpDocPath) ) ? unserialize($str) : null;
		
		if($tmpDocPath && $tmpDocStr) {
			foreach ($tmpDocStr as $iKey => $arrVal) {
				if ($arrVal['hash']) {
					$fileLocation   = $arrVal['fileLocation'];
					$csvPath        =  PLG_TEMP_STORE . "files";
					$cleanPath      = str_replace("temp/", "{$csvPath}/", $fileLocation);
					
					if(file_exists($fileLocation)){
						if(!file_exists(dirname($cleanPath)) || !is_dir(dirname($cleanPath))){
							mkdir( dirname($cleanPath), 0777, true );
						}
						if( copy($fileLocation, $cleanPath)){
							unlink($fileLocation);
						}
					}
					$objCSVFile     = $csvRepo->findByUid($pzFormObject->getUid());
					$objCSVFile->setFilePath(str_replace(realpath(SITE_ROOT), "", $cleanPath));
					$csvRepo->update($objCSVFile);
					$persistenceManager->persistAll();
				}
			}
			Octopus::writeFile($tmpDocPath, "");
		}
	}

}
