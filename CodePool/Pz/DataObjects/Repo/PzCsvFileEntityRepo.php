<?php 

	namespace  CodePool\Pz\DataObjects\Repo;

	use Doctrine\ORM\EntityRepository;
	use Doctrine\ORM\EntityManager;
	use Doctrine\ORM\Mapping\ClassMetadata;

	/**
	 * PzCsvFileEntityRepo
	 * Repository Class automatically generated by Poiz Doctrine Mediator.
	 * You may add additional Methods to this Repository as well...
	 **/
	class PzCsvFileEntityRepo extends EntityRepository {

		/**
		 * @var EntityManager
		 */
		protected $eMan;


		public function __construct(EntityManager $em, ClassMetadata $class){
			parent::__construct($em, $class);
			$this->eMan			= $em;
		}

	}