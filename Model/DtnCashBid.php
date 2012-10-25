<?php
/**
 * Future Quote
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package xignite
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('DtnAppModel', 'Dtn.Model');

class DtnCashBid extends DtnAppModel {

/**
 * Subscription schema
 *
 * @var array
 */
	public $_schema = array(
        'id' => array('type' => 'integer'),
        'city' => array('type' => 'string'),
        'state' => array('type' => 'string'),
        'county' => array('type' => 'string'),
        'name' => array('type' => 'string'),
        'commodityname' => array('type' => 'string'),
        'displayname' => array('type' => 'string'),
        'bid' => array('type' => 'float'),
        'date' => array('type' => 'date'),
        'querystatus' => array('type' => 'string')
	);

}
