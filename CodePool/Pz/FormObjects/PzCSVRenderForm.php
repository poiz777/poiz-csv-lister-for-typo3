<?php
	
	namespace CodePool\Pz\FormObjects;
	use CodePool\Pz\Traits\FormHelper;
	
	class PZCSVRenderForm {
		use FormHelper;
		/**
		 * id
		 *
		 * @var int
		 */
		protected $id               = 0;
		
		/**
		 * name
		 *
		 * @var string
		 *
		 * ##FormLabel Project Owner's/Employer's Name
		 * ##FormFieldHint <span class='pz-hint pz-field-hint'>This is the Unique Name of the Project.<br/><b style="color:#FAFAFA;">Alphanumeric Characters are permissible!</b></span>
		 * ##FormInputType text
		 * ##FormInputRequired 1
		 * ##FormInputState 0
		 * ##FormValidationStrategy VALIDATION_STRATEGY_RAW
		 */
		protected $name             = '';
		
		/**
		 * employerProjects
		 *
		 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<>
		 * @cascade remove
		 * @lazy
		 */
		protected $employerProjects = NULL;
		
		/**
		 * @return int
		 */
		public function getId() {
			return $this->id;
		}
		
		/**
		 * @return string
		 */
		public function getName() {
			return $this->name;
		}
		
		/**
		 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
		 */
		public function getEmployerProjects() {
			return $this->employerProjects;
		}
		
		/**
		 * @param int $id
		 * @return PZCSVRenderForm
		 */
		public function setId($id) {
			$this->id = $id;
			
			return $this;
		}
		
		/**
		 * @param string $name
		 * @return PZCSVRenderForm
		 */
		public function setName($name) {
			$this->name = $name;
			
			return $this;
		}
		
		/**
		 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $employerProjects
		 * @return PZCSVRenderForm
		 */
		public function setEmployerProjects($employerProjects) {
			$this->employerProjects = $employerProjects;
			
			return $this;
		}

	}
