<?php 

	namespace CodePool\Pz\DataObjects;

	use Doctrine\ORM\Mapping\Id;
	use Doctrine\ORM\Mapping\Table;
	use Doctrine\ORM\Mapping\Column;
	use Doctrine\ORM\Mapping\Entity;
	use Doctrine\ORM\Mapping\GeneratedValue;
	use Doctrine\ORM\EntityManager;
	use Pimple\Container;

	require __DIR__ . "/../../../config/pimple_config.php";


	/**
	 * PzCsvFileEntity
	 * @Table(name="tx_poizcsvlister_domain_model_pzcsvfile")
	 * @Entity(repositoryClass="CodePool\Pz\DataObjects\Repo\PzCsvFileEntityRepo")
	 **/
	class PzCsvFileEntity { 

		/**
		 * @var array
		 */
		protected $entityBank	= array();
		/**
		 * @var Container
		 */
		protected $container;
		/**
		 * @var EntityManager
		 */
		protected $eMan;


		/**
		 * @var int
		 * @Id PzCsvFileEntity
		 * @Column(type="integer")
		 * @GeneratedValue(strategy="AUTO")
		 */
		protected $uid; 

		/**
		 * @var string
		 * @Column(name="file_name", type="string", length=255, nullable=false)
		 */
		protected $file_name; 

		/**
		 * @var string
		 * @Column(name="file_path", type="string", length=255, nullable=false) 
		 *
		 */
		protected $file_path; 

		/**
		 * @var int
		 * @Column(name="pid", type="integer", length=11, nullable=false) 
		 *
		 */
		protected $pid; 

		/**
		 * @var int
		 * @Column(name="list_render", type="integer", length=10, nullable=true)
		 *
		 */
		protected $list_render; 

		public function __construct(){
			$this->eMan			= $GLOBALS['container']['E_MAN'];
			$this->initializeEntityBank(); 
		}


		public function getUid() {
			return $this->uid;
		}

		public function getFileName() {
			return $this->file_name;
		}

		public function getFilePath() {
			return $this->file_path;
		}

		public function getPid() {
			return $this->pid;
		}

		public function getListRender() {
			return $this->list_render;
		}


		public function setUid($uid) {
			$this->uid = $uid;
			return $this;
		}

		public function setFileName($file_name) {
			$this->file_name = $file_name;
			return $this;
		}

		public function setFilePath($file_path) {
			$this->file_path = $file_path;
			return $this;
		}

		public function setPid($pid) {
			$this->pid = $pid;
			return $this;
		}

		public function setListRender($list_render) {
			$this->list_render = $list_render;
			return $this;
		}


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

		public function generateRandomHash($length = 6) {
			$characters     = '0123456789ABCDEF';
			$randomString   = '';

			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, strlen($characters) - 1)];
			}

			return $randomString;
		}

		public function getEntityBank() {
			return $this->entityBank;
		}

	} 
