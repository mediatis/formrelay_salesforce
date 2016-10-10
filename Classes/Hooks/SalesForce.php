<?php
namespace Mediatis\FormrelaySalesforce\Hooks;
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Michael Vöhringer (mediatis AG) <voehringer@mediatis.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Plugin Send form data to SourceFoce.com
 *
 * @author	Michael Vöhringer (mediatis AG) <voehringer@mediatis.de>
 * @package	TYPO3
 * @subpackage	leica_sfsend
 */
class SalesForce extends \Mediatis\Formrelay\AbstractFormrelayHook implements \Mediatis\Formrelay\DataProcessorInterface
{


	public function processData($data){

		GeneralUtility::devLog('SalesForce::processData', __CLASS__, 0);

		// Do nothing, if plugin.tx_formrelay_salesforce.enabled is not set to true
		if (!$this->conf['enabled']) {
			return $data;
		}

		if (!$this->validateForm($data)) {
			return $data;
		}

		// create salesforce data
		$result = $this->processAllFields($data);

		return $this->sendToSalesforce($result);
	}

	private function sendToSalesforce($data){

		$params = array();
		foreach ($data as $key => $value) {
			$params[] = rawurlencode($key) . '=' . rawurlencode($value);
		}
		$queryString = implode('&', $params);

		$curlOptions = array(
			CURLOPT_URL => $this->conf['salesForceUrl'],
			CURLOPT_POST => TRUE,
			CURLOPT_POSTFIELDS => $queryString,

			CURLOPT_REFERER => $_SERVER['HTTP_REFERER'],
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_MAXREDIRS => 10,
		);

		$handle = curl_init();

		curl_setopt_array($handle, $curlOptions);

		curl_exec($handle);
		curl_close($handle);
	}
}

?>