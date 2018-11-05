<?php
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	header('Access-Control-Max-Age: 86400');
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

	require_once __DIR__    . "/../../_DEFINITIONS_.php";
	require_once CODE_BASE   . "/vendor/autoload.php";
	use CodePool\Pz\Poiz\Bridges\Octopus;
	
	class Ajax {

		/**
		 * Ajax constructor.
		 */
		public function __construct() {
		}

		/**
		 * @param string $path
		 *
		 * @return array|string
		 */
		public function uploadCSVAction($path){
			return Octopus::uploadCSVFiles($path, TRUE, FALSE);
		}

		/**
		 * @param string $filePath
		 *
		 * @return array|string
		 */
		public function deleteUploadedFileByPath($filePath){
			return Octopus::deleteTemporarilyUploadedCSVFile($filePath);
		}

		/**
		 * @param int $id
		 *
		 * @return array|string
		 */
		public function deleteUploadedFileByUID($id){
			return Octopus::deleteOldUploadedCSVFile($id);
		}

		/**
		 * @param int $id
		 * @param string $field
		 * @param string $fieldVal
		 * @param string $updatePackets
		 *
		 * @return array|string
		 */
		public function updateEntry($id, $field, $fieldVal, $updatePackets){
			return Octopus::handleEntryTrashingAndUpdating("update", $id, $field, $fieldVal, $updatePackets);
		}

		/**
		 * @param int $id
		 * @param string $field
		 * @param string $fieldVal
		 * @param string $updatePackets
		 *
		 * @return array|string
		 */
		public function deleteEntry($id, $field, $fieldVal, $updatePackets){
			return Octopus::handleEntryTrashingAndUpdating("delete", $id, $field, $fieldVal, $updatePackets);
		}
	}

	$do             = isset($_REQUEST['do'])            ? $_REQUEST['do']               : null;
	$uid            = isset($_REQUEST['uid'])           ? $_REQUEST['uid']              : null;
	$path           = isset($_REQUEST['path'])          ? $_REQUEST['path']             : "temp";
	$field          = isset($_REQUEST['field'])         ? $_REQUEST['field']            : "temp";
	$intent         = isset($_REQUEST['intent'])        ? $_REQUEST['intent']           : "file-upload";
	$action         = isset($_REQUEST['action'])        ? $_REQUEST['action']           : null;
	$filePath       = isset($_REQUEST['filePath'])      ? $_REQUEST['filePath']         : null;
	$fieldVal       = isset($_REQUEST['fieldVal'])      ? $_REQUEST['fieldVal']         : "";
	$updatePackets  = isset($_REQUEST['updatePackets']) ? $_REQUEST['updatePackets']    : null;
	
	$path       = empty($path)                  ? "temp"                : $path;
	$action     = ($do)                         ? $do                   : $action;
	$ajax       = new Ajax();

	switch($action){
		case "uploadCSV":
			$response   = $ajax->uploadCSVAction("files");
			break;
		case "deleteUploadedFileByPath":
			$response   = $ajax->deleteUploadedFileByPath($filePath);
			break;
		case "deleteUploadedFileByUID":
			$response   = $ajax->deleteUploadedFileByUID($uid);
			break;
		case "updateEntry":
			$response   = $ajax->updateEntry($uid, $field, $fieldVal, $updatePackets);
			break;
		case "trashEntry":
		case "deleteEntry":
			$response   = $ajax->deleteEntry($uid, $field, $fieldVal, $updatePackets);
			break;
		default:
			$response   = $ajax->uploadCSVAction("files");
	}

	$json = json_encode($response);
	die($json);
