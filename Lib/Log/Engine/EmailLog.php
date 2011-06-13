<?php

App::uses('CakeLogInterface', 'Log');

/**
 *CakePHP Email Storage stream for Logging
 * 
 * Send log by email using one of configured EmailConfig (app/Config/email.php)
 * 
 *Usage:
 * 
 * {{{
 * CakeLog::config('email', array(
 * 		'engine' => 'EmailLog.EmailLog', 
 * 		'emailConfig' => 'error', 
 * 		'subjectFormat' => ':type  :date  @ :host',
 * 		'logTypes' => array('warrning', 'error'), 
 * ));
 * }}}
 *
 */
class EmailLog implements CakeLogInterface {

	/**
	 * Options for email log writer
	 * 
	 * emailConfig - one of configured EmailConfig. See app/Config/email.php
	 * subjectFormat - email subject format using available params
	 * logTypes - list of log types to send by email (all by default) or array of log types like 'error', 'warrning'. 'info'
	 * @var array
	 */
	protected $_options = array(
		'emailConfig' => 'default',
		'subjectFormat' => ':type  :date @ :host',
		'logTypes' => array()
	);

	/**
	 * Constructs a new File Logger.
	 * 
	 * Options
	 *
	 * - `path` the path to save logs on.
	 *
	 * @param array $options Options for the FileLog, see above.
	 * @return void
	 */
	function __construct($options = array()) {
		$this->_options = array_merge($this->_options, $options);
	}

	/**
	 * Send email log
	 *
	 * @param string $type The type of log you are making.
	 * @param string $message The message you want to log.
	 * @return boolean success of write.
	 */
	public function write($type, $message) {
		if (!empty($this->_options['logTypes']) && !in_array($type, $this->_options['logTypes'])) {
			return false;
		}

		$params = array(
			'type' => ucfirst($type),
			'date' => date('Y-m-d H:i:s'),
			'host' => env('SERVER_NAME'),
		);

		App::uses('String', 'Utility');
		$subject = String::insert($this->_options['subjectFormat'], $params);

		try {
			App::uses('CakeEmail', 'Network/Email');
			return CakeEmail::deliver(null, $subject, $message, $this->_options['emailConfig']);
		} catch (SocketException $e) {
			return false;
		}
	}

}