<?php
/**
 * DTN app controller
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package dtn
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

class DtnAppController extends AppController {
    public function beforeFilter() {
        $this->autoRender = false;
        $this->response->type('json');
        header('Content-type: application/json');
    }
}
