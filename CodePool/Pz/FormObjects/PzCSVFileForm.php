<?php
	
	namespace CodePool\Pz\FormObjects;
	use CodePool\Pz\Traits\FormHelper;

	/****
	 * Class PzCSVFileForm
	 * @package CodePool\Pz\FormObjects
	 */
	class PzCSVFileForm {
		use FormHelper;

		/**
		 * @var PzCSVFileForm
		 */
		protected static $instance;
		/**
		 * id
		 *
		 * @var int
		 */
		protected $uid               = 0;
		
		/**
		 * name
		 *
		 * @var string
		 *
		 * ##FormLabel poiz_csv_lister.miscellaneous.unique_lister_name
		 * ##FormFieldHint <span class='pz-hint pz-field-hint'>poiz_csv_lister.miscellaneous.unique_lister_name_desc<br/><b style="color:#FAFAFA;">poiz_csv_lister.miscellaneous.al_num_ok</b></span>
		 * ##FormInputType text
		 * ##FormInputRequired 1
		 * ##FormInputState 0
		 * ##FormValidationStrategy VALIDATION_STRATEGY_RAW
		 */
		protected $fileName             = '';
		
		
		/**
		 * filePath
		 *
		 * @var string
		 * @validate NotEmpty
		 */
		protected $filePath = '';
		
		/**
		 * listRender
		 *
		 * @var \POiz\PoizCsvLister\Domain\Model\PzCSVRender
		 */
		protected $listRender = null;
		
		
		
		/**
		 *
		 * @var string
		 *
		 * ##FormLabel poiz_csv_lister.miscellaneous.csv_files_list
		 * ##FormFieldHint <span class='pz-hint pz-field-hint'>poiz_csv_lister.miscellaneous.uploaded_csv_file</span>
		 * ##FormInputType div
		 * ##FormRawContent CodePool\Pz\FormObjects\PzCSVFileForm::buildCSVLibrary
		 * ##FormInputRequired 0
		 */
		protected $photoLibrary     = "";
		
		/**
		 * @var string
		 *
		 * ##FormLabel poiz_csv_lister.miscellaneous.upload_csv_file
		 * ##FormFieldHint <span class='pz-hint pz-field-hint'><span class='pz-optional'>poiz_csv_lister.miscellaneous.optional</span><br />poiz_csv_lister.miscellaneous.uploader_hint</span>
		 * ##FormInputType drop_zone
		 * ##FormDZoneAllow .csv,.xls
		 * ##FormInputRequired 0
		 * ##FormInputDzURL /typo3conf/ext/poiz_csv_lister/CodePool/Pz/Ajax/Ajax.php?action=uploadCSV
		 */
		protected $column_images;
		
		/**
		 * PzCSVFileForm constructor.
		 */
		public function __construct() {
			self::$instance = $this;
		}
		
		
		
		/**
		 * @return int
		 */
		public function getUid() {
			return $this->uid;
		}
		
		/**
		 * @return string
		 */
		public function getFileName() {
			return $this->fileName;
		}
		
		/**
		 * @return string
		 */
		public function getFilePath() {
			return $this->filePath;
		}
		
		/**
		 * @return \POiz\PoizCsvLister\Domain\Model\PzCSVRender
		 */
		public function getListRender() {
			return $this->listRender;
		}
		
		/**
		 * @return string
		 */
		public function getPhotoLibrary() {
			return $this->photoLibrary;
		}
		
		/**
		 * @return string
		 */
		public function getColumnImages() {
			return $this->column_images;
		}
		
		/**
		 * @param int $uid
		 * @return PzCSVFileForm
		 */
		public function setUid($uid) {
			$this->uid = $uid;
			
			return $this;
		}
		
		/**
		 * @param string $fileName
		 * @return PzCSVFileForm
		 */
		public function setFileName($fileName) {
			$this->fileName = $fileName;
			
			return $this;
		}
		
		/**
		 * @param string $filePath
		 * @return PzCSVFileForm
		 */
		public function setFilePath($filePath) {
			$this->filePath = $filePath;
			
			return $this;
		}
		
		/**
		 * @param \POiz\PoizCsvLister\Domain\Model\PzCSVRender $listRender
		 * @return PzCSVFileForm
		 */
		public function setListRender($listRender) {
			$this->listRender = $listRender;
			
			return $this;
		}
		
		/**
		 * @param string $photoLibrary
		 * @return PzCSVFileForm
		 */
		public function setPhotoLibrary($photoLibrary) {
			$this->photoLibrary = $photoLibrary;
			
			return $this;
		}
		
		/**
		 * @param string $column_images
		 * @return PzCSVFileForm
		 */
		public function setColumnImages($column_images) {
			$this->column_images = $column_images;
			
			return $this;
		}
		
		/**
		 * @return PzCSVFileForm
		 */
		public static function getInstance() {
			return self::$instance;
		}
		
		/**
		 * @param PzCSVFileForm $instance
		 */
		public static function setInstance($instance) {
			self::$instance = $instance;
		}
		
	}
