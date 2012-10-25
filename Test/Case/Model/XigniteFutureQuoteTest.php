<?php
/* Activity Test cases generated on: 2012-06-04 23:18:13 : 1338866293*/
App::uses('XigniteFutureQuote', 'Xignite.Model');

/**
 * Activity Test Case
 *
 */
class XigniteFutureQuoteTestCase extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Model = new XigniteFutureQuote();
		$this->Model->setDataSource('xignite_test');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Model);

		parent::tearDown();
	}

/**
 * testRead method
 * 
 * @return void
 */
    public function testRead() {
//        $this->Model->find('all', array(
//            'conditions' => array(
//                'Symbol' => 'ZCADFS',
//                'StartDate' => '9/1/2012',
//                'EndDate' => '9/30/2012'
//            )
//        ));

        $this->Model->find('all', array(
            'conditions' => array(
                'Symbol' => 'ZC',
                'StartDate' => '9/1/2012',
                'EndDate' => '9/30/2012'
            )
        ));

    }

}
