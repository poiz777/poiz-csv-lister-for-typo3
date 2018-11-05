<?php
namespace POiz\PoizCsvLister\Controller;

use CodePool\Pz\DataObjects\PzCsvFileEntity;
use CodePool\Pz\Traits\ControllerHelper;
use Doctrine\ORM\EntityManager;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use ParseCsv\Csv;
use Pimple\Container;

require_once  __DIR__ . "/../../config/pimple_config.php";

/**
 * PzCSVListerController
 */
class PzCSVListerController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	use ControllerHelper;
	
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
	 * photosRepository
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
	 * @var PzCSVListerController
	 */
	protected static $instance;

	/**
	 * @var array
	 */
	public static $formSettings;
	
	/**
	 * objectManager
	 *
	 * @var RenderingContext
	 */
	protected static $renderingContext = NULL;

	/**
	 * PzCSVListerController constructor.
	 */
	public function __construct() {
		parent::__construct();
		if(isset($GLOBALS['container'])){
			$this->container    = $GLOBALS['container'];
			$this->eMan         = $this->container['E_MAN'];
			$GLOBALS['eMan']    = $this->eMan;
			self::$em           = $this->eMan;
		}
		self::$formSettings     = $this->settings['forms'];
		self::$instance         = $this;
		$this->objectManager    = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
	}

	public function renderAction () {
		/**@var PzCsvFileEntity $csvFileObj */
		$listNLink      = $this->settings['forms']['list_and_link'];
		$csvFileID      = (int)$this->settings['forms']['csv_id'];
		$csvTitle       = $this->settings['forms']['csv_title'];
		$csvList        = ($listNLink == "1") ? $this->getCSVFilesAsList('FE',$csvFileID) : "";
		$title          = '';

		if($csvFileID){
			$csvFileObj = $this->eMan->getRepository('CodePool\Pz\DataObjects\PzCsvFileEntity')->find($csvFileID);
			$title      = $csvFileObj->getFileName();
			$filePath   = realpath(SITE_ROOT . trim($csvFileObj->getFilePath(), "/"));
			$arrCsvData = new Csv($filePath);
			$strOut     = "";

			if($arrCsvData){
				$tableConfig    = $this->getHTMLTableConfig();
				$strOut         = $this->renderObjectInTableView($arrCsvData, $tableConfig);
			}
			$html       = $csvList . $strOut;
			$html       = self::prependRawScriptsToHTML($html);

		}else{
			$html       = self::prependRawScriptsToHTML($csvList);
		}
		$this->view->assign('payload', ['html'=> $html, 'csvList'=>$csvList, 'title'=>$title, 'csvTitle'=>$csvTitle, 'back'=>self::getPrevPage()]);
	}

	public function viewListAction(){
		$tableConfig    = $this->getHTMLTableConfig();
		$args           = $this->request->getArguments();
		$listNLink      = $this->settings['forms']['list_and_link'];
		$csvTitle       = $this->settings['forms']['csv_title'];
		$csvList        = ($listNLink == "1") ? $this->getCSVFilesAsList(null, $args['uid']) : "";
		$filePath       = realpath(SITE_ROOT . trim($args['filePath'], "/"));
		$arrCsvData     = new Csv($filePath);
		$strOut         = "";

		if($arrCsvData){
			$strOut = $this->renderObjectInTableView($arrCsvData, $tableConfig);
		}

		$html   = self::prependRawScriptsToHTML($strOut);
		$this->view->assign('payload', ['html'=> $html, 'title'=>$args['fileName'], 'csvList'=>$csvList, 'csvTitle'=>$csvTitle, 'back'=>self::getPrevPage()]);
	}
}
