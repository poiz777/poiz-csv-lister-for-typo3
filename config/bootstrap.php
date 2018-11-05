<?php
	// bootstrap.php
	use Doctrine\ORM\Tools\Setup;
	use Doctrine\ORM\EntityManager;
	use Doctrine\Common\Cache\ArrayCache;
	use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
	use Doctrine\Common\Annotations\AnnotationReader;


	//CREATE A POINTER TO ENTITIES PATH - POINTER MUST BE AN ARRAY CONTAINING FULL PATH NAMES
	require __DIR__    . "/../CodePool/_DEFINITIONS_.php";
	$paths 		= array( realpath(ENTITY_ROOT) );


	//CREATE A SIMPLE "DEFAULT" DOCTRINE ORM CONFIGURATION FOR ANNOTATIONS
	$isDevMode 	= true;
	$DConfig 	= Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);


	// DATABASE CONFIGURATION PARAMETERS: MYSQL
	$mysqlConn  = array(
		'driver'        => 'pdo_mysql'  ,
		'user'          => USER         ,
		'password'      => PASS         ,
		'host'          => HOST         ,
		'dbname' 	    => DBASE        ,
		'charset'       => 'utf8',
		'driverOptions' => [1002 => 'SET NAMES utf8']
	);


	$cache      = new ArrayCache();
	$reader     = new AnnotationReader();
	$driver     = new AnnotationDriver($reader, $paths);
	$DConfig    = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);


	$DConfig->setMetadataCacheImpl( $cache );
	$DConfig->setQueryCacheImpl( $cache );
	$DConfig->setMetadataDriverImpl( $driver );



	if(!class_exists("E_MAN")){
		class E_MAN{
			/**
			 * @var Doctrine\ORM\EntityManager
			 */
			protected static $entityManager;

			/**
			 * @return mixed
			 */
			public static function getEntityManager(){
				return self::$entityManager;
			}

			/**
			 * @param Doctrine\ORM\EntityManager $entityManager
			 */
			public static function setEntityManager(Doctrine\ORM\EntityManager $entityManager){
				self::$entityManager = $entityManager;
			}

		}

	}

	// INSTANTIATE THE ENTITY MANAGER
	$entityManager = EntityManager::create($mysqlConn,  $DConfig);


	// - ADD SUPPORT FOR MYSQL ENUM-TYPES....
	$platform = $entityManager->getConnection()->getDatabasePlatform();
	$platform->registerDoctrineTypeMapping('enum', 'string');

	// STATICALLY SET THE ENTITY MANAGER SO IT CAN BE ACCESSED BY CLASSES NEEDING IT....
	E_MAN::setEntityManager($entityManager);
	$GLOBALS['E_MAN']   = $entityManager;
