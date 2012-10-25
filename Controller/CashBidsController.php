<?php
/**
 * Dtn app controller
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package xignite
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('DtnAppController', 'Dtn.Controller');
App::uses('DtnCashBid', 'Dtn.Model');

class CashBidsController extends DtnAppController {
    
    public $uses = array('Dtn.DtnCashBid');

    public function index() {
        if (empty($this->request->query['zip'])) {
            throw new BadRequestException('Please specify a zip code.');
        }

        $results = $this->DtnCashBid->find('all', array(
            'conditions' => array(
                'zip' => $this->request->query['zip']
            )
        ));

        if (! $results) {
            throw new InternalErrorException('Could not retrieve results.');
        }

        echo json_encode(Set::extract($results, '{n}.DtnCashBid'));
    }

}
