<?php
/**
 * Created by PhpStorm.
 * User: Raphael
 * Date: 14/12/14
 * Time: 08:14
 */

	require_once "bootstrap.php";
	return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);