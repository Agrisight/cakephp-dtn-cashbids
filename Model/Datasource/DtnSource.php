<?php
/**
 * Dtn datasource
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package dtn
 * @subpackage dtn.models.datasources
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('HttpSocket', 'Network/Http');
App::uses('Xml', 'Utility');
App::uses('CakeLog', 'Log');

class DtnSource extends DataSource {

/**
 * HttpSocket
 *
 * @var HttpSocket
 */
	public $Http = null;

/**
 * Start quote
 * 
 * @var string 
 */
	public $startQuote = '';

/**
 * End quote
 * 
 * @var string 
 */
	public $endQuote = '';
    
/**
 * Records
 * 
 * Used to aid in filtering out duplicates.
 * @var type 
 */
    protected $records = array();

/**
 * Constructor. Sets API key and throws an error if it's not defined in the
 * db config
 *
 * @param array $config
 */
	public function __construct($config = array()) {
		parent::__construct($config);

		if (empty($config['username']) || empty($config['password']) || empty($config['service'])) {
			throw new CakeException('DtnSource: Missing api credentials');
		}

		$this->Http = new HttpSocket();
	}

/**
 * Reads a Dtn record
 *
 * @param Model $model The calling model
 * @param array $query conditions, limit, etc
 * @return mixed `false` on failure, data on success
 */
	public function read(Model $model, $query = array()) {
        $this->records = array();
        $request = array();

		// If calculate() wants to know if the record exists. Say yes.
		if ($query['fields'] == 'COUNT') {
			return array(array(array('count' => 1)));
		}

        if (!empty($query['conditions']['zip'])) {
            $request['body']['zip'] = $query['conditions']['zip'];
        }

		$response = $this->request($request);

		if ($response === false) {
			return false;
		}

        $result = array();
        foreach (Set::extract($response, 'CASHBIDREQUEST_ROW.{n}') as $record) {
            if ($this->_matches($record, $query['conditions'])) {
                if (! $this->_is_duplicate($record)) {
                    $result[] = array($model->alias => array_change_key_case($record, CASE_LOWER));
                }
            }
        }

		return $result;
	}
    
    protected function _matches($record, $conditions) {
        if (! empty($conditions['commodity'])) {
            if (strpos($record['COMMODITYNAME'], strtoupper($conditions['commodity'])) !== 0) {
                return false;
            }
        }

        if (! empty($conditions['bid_type'])) {
            if (! in_array(strtoupper($conditions['bid_type']), explode('/', $record['COMMODITYNAME']))) {
                return false;
            }
        }

        return true;
    }
    
    protected function _is_duplicate($record) {
        $key = $record['COMMODITYNAME'] . $record['CITY'] . $record['STATE'] . $record['NAME'] . $record['BID'];

        if (isset($this->records[$key])) {
            return true;
        }

        $this->records[$key] = true;

        return false;
    }

/**
 * Submits a request to Dtn. Requests are merged with default values, such as
 * the api host. If an error occurs, it is stored in `$lastError` and `false` is
 * returned.
 *
 * @param array $request Request details
 * @return mixed `false` on failure, data on success
 */
	public function request($request = array()) {
		$this->lastError = null;
		$this->request = array(
			'uri' => array(
				'host' => 'agquote.dtn.com',
				'scheme' => 'http',
				'path' => '/cashbids/processcashbidtag.cfm'
			),
			'method' => 'POST',
            'body' => array(
                'username' => $this->config['username'],
                'password' => $this->config['password'],
                'service' => $this->config['service'],
                'type' => '1',  
                'request' => 'byzip',
                'xml' => 'XML'
            )
		);
		$this->request = Set::merge($this->request, $request);

		try {
			$http_response = $this->Http->request($this->request);

			if ($this->Http->response['status']['code'] != '200') {
                $this->lastError = 'Unexpected error.';
                CakeLog::write('dtn', $this->lastError);
                return false;
			}

            $response = Xml::toArray(Xml::build(trim($http_response->body)));

            if (
                isset($response['CASHBIDREQUEST']['CASHBIDREQUEST_ROW']['querystatus']) &&
                strtolower($response['CASHBIDREQUEST']['CASHBIDREQUEST_ROW']['querystatus']) === 'failure'
            ) {
                $this->lastError = $response['CASHBIDREQUEST']['CASHBIDREQUEST_ROW']['message'];
                return false;
            }

            return $response['CASHBIDREQUEST'];
		} catch (CakeException $e) {
			$this->lastError = $e->getMessage();
			CakeLog::write('dtn', $e->getMessage());
		}
	}

/**
 * For checking if record exists. Return COUNT to have read() say yes.
 *
 * @param Model $Model
 * @param string $func
 * @return true
 */
	public function calculate(Model $Model, $func) {
		return 'COUNT';
	}

/**
 * Don't use internal caching
 *
 * @return null
 */
	public function listSources($data = null) {
		return null;
	}

/**
 * Descibe with schema. Check the model or use nothing.
 *
 * @param Model $Model
 * @return array
 */
	public function describe($model) {
		if (isset($Model->_schema)) {
			return $Model->_schema;
		} else {
			return null;
		}
	}

}