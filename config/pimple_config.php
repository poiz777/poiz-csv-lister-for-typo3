<?php

require_once __DIR__ . "/bootstrap.php";
require_once __DIR__ . "/../CodePool/vendor/autoload.php";

use Pimple\Container;

$container = new Container();

$services = [
	'CodePool\Pz\Base\Poiz\Forms\FormBaker'             => ['alias'=>'FB',                  'params'=>[]],
	'CodePool\Pz\Base\Poiz\Forms\Validator'             => ['alias'=>'Validator',           'params'=>[]],
	'CodePool\Pz\Base\Poiz\Forms\ErrorLogger'           => ['alias'=>'ErrorLogger',         'params'=>[]],
	'CodePool\Pz\DataObjects\PzCsvFileEntity'           => ['alias'=>'PzCsvFileEntity',     'params'=>[]],
	'CodePool\Pz\DataObjects\Repo\PzCsvFileEntityRepo'  => ['alias'=>'PzCsvFileEntityRepo', 'params'=>[]],
	'CodePool\Pz\FormObjects\PZCSVFileForm'             => ['alias'=>'PZCSVFileForm',       'params'=>[]],
	'CodePool\Pz\FormObjects\PZCSVRenderForm'           => ['alias'=>'PZCSVRenderForm',     'params'=>[]],
	'CodePool\Pz\Poiz\Bridges\PoizPluginHelper'         => ['alias'=>'PoizPluginHelper',    'params'=>[]],
	'CodePool\Pz\Poiz\Bridges\Octopus'                  => ['alias'=>'Octopus',             'params'=>[]],
];

foreach($services as $fullClassName=>$arrServiceData){
	$container[$arrServiceData['alias']] = function ($c) use($fullClassName){
		return new $fullClassName();
	};
}

$entityConfig   = array(
	'fileName'      => CODE_POOL . '/DataObjects/Sampler.php',
	'tableName'     =>'sample_tbl',
	'className'     =>'Sampler',
	'nameSpace'     =>'CodePool\Pz\DataObjects',
	'usableClasses' =>array(
		'Doctrine\ORM\Mapping as ORM',
		'Doctrine\ORM\Mapping\Id',
		'Doctrine\ORM\Mapping\Table',
		'Doctrine\ORM\Mapping\Column',
		'Doctrine\ORM\Mapping\Entity',
		'Doctrine\ORM\Mapping\OneToOne',
		'Doctrine\ORM\Mapping\JoinColumn',
		'Doctrine\ORM\Mapping\GeneratedValue',
		'Doctrine\ORM\Mapping\JoinColumns',
		'Doctrine\ORM\Mapping\OneToMany',
		'Doctrine\ORM\Mapping\ManyToOne',
		'Doctrine\ORM\Mapping\ManyToMany',
	),
);

$container['entityConfig'] = function ($c) use($entityConfig) {
	return $entityConfig;
};

$container['entityConfig'] = function ($c) use($entityConfig) {
	return $entityConfig;
};

$container['Doctrine.ORM.EntityManager'] = function ($c) {
	return E_MAN::getEntityManager();
};

$container['E_MAN'] = function ($c) {
	return $GLOBALS['E_MAN'];
};
$GLOBALS['container'] = $container;
