CakePHP DTN Cash Bids Plugin
============================

This plugin enables access to the DTN Cash Bids API via a CakePHP plugin.

Setup
-----

bootstrap.php:

    CakePlugin::load('Dtn');

database.php:

    public $dtn = array(
        'datasource' => 'Dtn.DtnSource',
        'username' => 'username1234',
        'password' => 'yourPassWoRD',
        'service' => 'CASHBIDS'
    );

Usage
-----

Load the model wherever it is needed:

    public $uses = array('Dtn.DtnCashBid');

You can then retrieve a bid with the following code:

    $bids = $this->DtnCashBid->find('all', array(
        'conditions' => array(
            'zip' => 48735,
            'commodity' => 'corn'
        )
    ));

Zip **(required)** - The place to orient the local search around.  
Commodity (optional) - Limit the search to this commodity name.

