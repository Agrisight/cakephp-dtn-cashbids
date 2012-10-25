<?php
/**
 * DTN app model
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package dtn
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppModel', 'Model');

class DtnAppModel extends AppModel {

/**
 * The datasource
 *
 * @var string
 */
	public $useDbConfig = 'dtn';

/**
 * No table here
 *
 * @var mixed
 */
	public $useTable = false;

/**
 * Returns the last error from DTN
 *
 * @return string Error
 */
	public function getDtnError() {
		$ds = ConnectionManager::getDataSource($this->useDbConfig);
		return $ds->lastError;
	}

}
