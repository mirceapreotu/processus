<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tufan
 * Date: 01.03.11
 * Time: 16:10
 * To change this template use File | Settings | File Templates.
 */

class App_Manager_Asset extends App_Manager_AbstractManager
{

	const FRONTEND_USER_ID = 383;
	const ASSET_FORMAT_RELATION_FORTABLE = 'system_asset_relation_asset_formatv';

	const FORMAT_SOURCE_ID=195;
	const FORMAT_THUMBNAIL_ID = 349;
	const FORMAT_PREVIEW_IMAGE_ID = 348;
	const FORMAT_WEBSITE_DELIVERY_ID = 686;

	const SHA1_SALT_PREFIX = 'c*:O';

	const SIZE_ORIGINAL_KEY = "orig";

	const SIZE_THUMBNAIL_KEY = "thumb";
	const SIZE_THUMBNAIL_WIDTH = 50;
	const SIZE_THUMBNAIL_HEIGHT = 50;

	const SIZE_SMALL_KEY = "small";
	const SIZE_SMALL_WIDTH = 110;
	const SIZE_SMALL_HEIGHT = 110;

	const SIZE_MEDIUM_KEY = "medium";
	const SIZE_MEDIUM_WIDTH = 223;
	const SIZE_MEDIUM_HEIGHT = 223;

	const SIZE_LARGE_KEY = "large";
	const SIZE_LARGE_WIDTH = 640;
	const SIZE_LARGE_HEIGHT = 480;

	/**
	 * @param  int $userId
	 * @param  string $encodedFileData
	 * @return int an asset Id
	 */
	public function processFlashUpload($userId, $promotionId, $encodedFileData)
	{
		$tmpLocation = ROOT_PATH . '/var/tmp/upload_' . uniqid() . '_' . getmypid() . '.jpg';
		$fileData = base64_decode($encodedFileData);

		file_put_contents($tmpLocation, $fileData);

		return $this->save($promotionId,$userId, $tmpLocation);
		//return $this->processFileUpload($userId, $tmpLocation);
	}


	public function save($promotionId, $userId, $pathname)
	{
		$dbClient = $this->getDbClient();

		$history = $this->_makeNewHistory($userId);

		$mimeType = Lib_Utils_File_MimeType::getByPathname($pathname);
		$type = Lib_Utils_File_MimeType::getType($mimeType);
		switch ($type) {
			case 'Video':
				$baseStorageFolder = 'big_storage';
				$assetFolderId=117;
				break;
			case 'Image':
			default:
				$baseStorageFolder = 'asset';
				$assetFolderId = 116;
			break;
		}

		$row = array(
			'cms_created' => date('Y-m-d H:i:s'),
			'cms_modified' => date('Y-m-d H:i:s'),
			'cms_state' => App_Manager_CmsState::STATE_ACTIVE,
			'cms_language_id' => 1,
			'headline' => 'Contribution of User: ' . $userId,
			'subscriber_id' => $userId,
			'promotion_id' => $promotionId,
			'cms_creator_id' => self::FRONTEND_USER_ID,
			'cms_modifier_id' => self::FRONTEND_USER_ID,
			'cms_owner_id' => self::FRONTEND_USER_ID,
			'base_storage_folder' => $baseStorageFolder,
			'asset_folder_id' => $assetFolderId,
			'votes_positive' => 0,
			'votes_negative' => 0,
			'cms_history' => json_encode($history)
		);

		$assetId = $dbClient->insert('system_asset', $row, true);

		$this->formatFileAdd($pathname, $assetId,null);
		return $assetId;
	}


	/**
	 * Fügt ein FormatFile hinzu.
	 * 'source' ist auch ein Format.
	 *
	 * @param BMediaAssetFormat $format
	 * @param string $pathname
	 * @param BArray $attributes
	 * @param bool $flagMoveFile=false
	 * @return void
	 */
	public function formatFileAdd($pathname, $assetId, $attributes)
	{
		// wenn das file nicht lesbar ist
		if (!is_readable($pathname)
		    || !is_file($pathname)
		) {
			throw new Exception('Temp file not readable! [' . $pathname . ']');
		}

		// wenn keine Attribute übergeben wurden
		if ($attributes === null) {
			$attributes = array();
			$attributes['cms_state'] = 2;
		}

		// file attribute pruefen
		$attributes['mime_type'] = $mimeType = Lib_Utils_File_MimeType::getByPathname($pathname);
		$attributes['asset_type_selector'] = $type = Lib_Utils_File_MimeType::getType($mimeType);
		switch ($type) {
			case 'Image':

				$imageInfo = getimagesize($pathname);

				// check imageinfo
				if (is_array($imageInfo)
				    && array_key_exists(0, $imageInfo) && $imageInfo[0] > 0 // width
				    && array_key_exists(1, $imageInfo) && $imageInfo[1] > 0 // heigth
				) {
					$attributes['width'] = $imageInfo[0];
					$attributes['height'] = $imageInfo[1];
				}
				$baseStorageFolder = 'asset';
				$this->ensureFormat(self::FORMAT_THUMBNAIL_ID, $assetId);
				break;

			case 'Video':
				$fileInfo = Lib_Utils_Convert_FFMPEG::getFileInformation($pathname);
				foreach($fileInfo as $key => $info) {
					$attributes[$key]=$info;
				}
				$baseStorageFolder = 'big_storage';
				$this->ensureFormat(self::FORMAT_PREVIEW_IMAGE_ID, $assetId);
				$this->ensureFormat(self::FORMAT_THUMBNAIL_ID, $assetId);
				break;

			default:
				$baseStorageFolder = 'asset';
				break;
		}

		// sicherstellen, dass das format existiert
		$this->ensureFormat(self::FORMAT_SOURCE_ID, $assetId, $attributes);
		$this->_ensureFormatDirname($assetId,self::FORMAT_SOURCE_ID, $baseStorageFolder);

		// verschieben
//		if ($flagMoveFile) {
//			rename($pathname, $this->formatGetPathname($format));
//			@`chmod 644 $pathname`;
//
//			// kopieren
//		} else {
			copy($pathname, $this->formatGetPathname($assetId,self::FORMAT_SOURCE_ID, $baseStorageFolder));
			chmod($pathname, 0644);
//		}
	}

	/**
	 * fügt ein neues Format hinzu
	 * @throws Exception
	 * @param  $assetId
	 * @param  $formatId
	 * @return void
	 */
	public function ensureFormat($formatId, $assetId, array $attributes=null)
	{
		$db=$this->getDbClient();
		if ((int)$assetId<=0) {
			throw new Exception('Format kann nicht an nicht existierendes Asset gehängt werden!' .
			                    '[format:' . $formatId. ']');
		}

		$formatRelationInsertRow = array();

		$formatRelationInsertRow['asset_id'] = $assetId;
		$formatRelationInsertRow['asset_formatv_id'] = $formatId;

		// meta infos
		$formatRelationInsertRow['cms_created'] =
		$formatRelationInsertRow['cms_modified'] = date('Y-m-d H:i:s');
		$formatRelationInsertRow['cms_owner_id'] =
		$formatRelationInsertRow['cms_creator_id'] =
		$formatRelationInsertRow['cms_modifier_id'] = self::FRONTEND_USER_ID;

		// history
		$formatRelationInsertRow['cms_history'] = json_encode($this->_makeNewHistory(self::FRONTEND_USER_ID));
		// attribute übernehmen, wenn übergeben
		if (is_array($attributes) && !empty($attributes)) {
			$formatRelationInsertRow['file_name'] = Lib_Utils_Array::getProperty($attributes, 'file_name');
			$formatRelationInsertRow['file_size'] = Lib_Utils_Array::getProperty($attributes, 'file_size');
			$formatRelationInsertRow['mime_type'] = Lib_Utils_Array::getProperty($attributes, 'mime_type');
			$formatRelationInsertRow['width'] = Lib_Utils_Array::getProperty($attributes, 'width');
			$formatRelationInsertRow['height'] = Lib_Utils_Array::getProperty($attributes, 'height');
			$formatRelationInsertRow['duration'] = Lib_Utils_Array::getProperty($attributes, 'duration');
			$formatRelationInsertRow['video_codec'] = Lib_Utils_Array::getProperty($attributes, 'video_codec');
			$formatRelationInsertRow['video_bitrate'] = Lib_Utils_Array::getProperty($attributes, 'video_bitrate');
			$formatRelationInsertRow['fps'] = Lib_Utils_Array::getProperty($attributes, 'fps');
			$formatRelationInsertRow['audio_codec'] = Lib_Utils_Array::getProperty($attributes, 'audio_codec');
			$formatRelationInsertRow['audio_frequenz'] = Lib_Utils_Array::getProperty($attributes, 'audio_frequenz');
			$formatRelationInsertRow['audio_bitrate'] = Lib_Utils_Array::getProperty($attributes, 'audio_bitrate');
			$formatRelationInsertRow['audio_channels'] = Lib_Utils_Array::getProperty($attributes, 'audio_channels');
			$formatRelationInsertRow['is_manual_upload'] = Lib_Utils_Array::getProperty($attributes,
			                                                                            'is_manual_upload');
			$formatRelationInsertRow['cms_state'] = Lib_Utils_Array::getProperty($attributes, 'cms_state') OR 2;
			$formatRelationInsertRow['asset_type_selector'] = Lib_Utils_Array::getProperty($attributes,
			                                                                               'asset_type_selector');
			$formatRelationInsertRow['source_format_id'] = Lib_Utils_Array::getProperty($attributes, 'source_format_id');
		}

		// position ermitteln
		$currentPosition = 0;
		$formatList = $this->getFormatList($assetId);
		foreach ($formatList as $formatItem) {
			if ($formatItem['position'] < $currentPosition) {
				$currentPosition = $formatItem['position'];
			}
		}
		$formatRelationInsertRow['position'] = $currentPosition - 1;
		$db->insert(self::ASSET_FORMAT_RELATION_FORTABLE, $formatRelationInsertRow);
	}

	/**
	 * Macht ein neues History objekt.
	 *
	 * @return stdClass
	 */
	protected function _makeNewHistory($modifierId)
	{
		$modifier = $modifierId OR self::FRONTEND_USER_ID;

		$history = new stdClass();
		$history->entrys = array();
		$newHistoryEntry = new stdClass();
		$newHistoryEntry->timestamp = time();
		$newHistoryEntry->modifier_id = $modifier;
		$newHistoryEntry->note = 'created';
		$history->entrys[] = $newHistoryEntry;
		return $history;
	}


	/**
	 * return list of formats for this asset
	 *
	 * @return array
	 */
	public function getFormatList($assetId)
	{
		$db = $this->getDbClient();
		$sql = 'SELECT asset_formatv_id, position FROM ' .
		       'system_asset_relation_asset_formatv' .
		       ' WHERE asset_id=:asset_id';

		$params = array(
			"asset_id" => $assetId
		);

		return $db->getRows($sql, $params);
	}

	public function formatGetPathname($assetId,$formatId, $baseStorageFolder){
		return Zend_Registry::get('CONFIG')->CMSGLOBALS .
		'/' . $baseStorageFolder .
		'/' . implode('/', str_split($assetId)) .
		'/content'.
		'/'.$formatId .
		'/file';
	}

	protected function _ensureFormatDirname($assetId,$formatId, $baseStorageFolder){
		$path = $this->formatGetPathname($assetId,$formatId, $baseStorageFolder);
		$path = Lib_Utils_String::removePostfix($path, '/file');
		// wenn das Directory bereits existiert
		if (is_dir($path)) {
			return;
		}

		try {
			mkdir($path, 0777, true);
		} catch (Exception $e) {
			throw new Exception('Cant make directory! [' . $path . '] [' . $e->getMessage() . ']');
		}
		return true;
		// good old way
		/*$subdir = substr($path, strlen(Zend_Registry::get('CONFIG')->CMSGLOBALS));
		$pathArray = explode('/', substr($subdir, 1));

		$currentDir = Zend_Registry::get('CONFIG')->CMSGLOBALS;
		foreach ($pathArray as $folder) {
			$currentDir .= '/' . $folder;
			clearstatcache();
			if (!is_dir($currentDir)) {
				try {
					mkdir($currentDir);
					`chmod 755 $currentDir`;
				} catch (Exception $e) {
					throw new Exception('Cant make directory! [' . $currentDir . '] ['. $e->getMessage().']');
				}
			}
		}*/
	}


	/**
	 * Updates the description of the user contribution
	 * @throws Exception
	 * @param  $assetId
	 * @param  $text
	 * @return void
	 */
	public function setDescriptionByAssetId($assetId, $text){
		if ((int) $assetId<=0){
			throw new Exception('Unknown AssetId'. $assetId);
		}

		$updateRow=array("text" => $text);
		$where = 'id='.(int)$assetId;

		$this->getDbClient()->update('system_asset', $updateRow, $where);
	}
}
