<?php
App::uses('CakeLogInterface', 'Log');
App::uses('String', 'Utility');
App::uses('CakeEmail', 'Network/Email');

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
	 * Default options for logger
	 * 
	 * - emailConfig - One of configured EmailConfig. See app/Config/email.php.
	 * - subjectFormat - Email subject format using available params.
	 * - logTypes - List of log types to send by email (all by default) or array of log types like 'error', 'warrning'. 'info'.
	 * @var array
	 */
	protected $_options = array(
		'emailConfig' => 'default',
		'subjectFormat' => ':type  :date @ :host',
		'logTypes' => array()
	);

	/**
	 * 
	 * Merge options
	 * 
	 * @param array $options Options for logger
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

		$subject = String::insert($this->_options['subjectFormat'], $params);
		
		try {
			CakeEmail::deliver(null, $subject, $message, $this->_options['emailConfig']);
		} catch (SocketException $e) {
			return false;
		}
	}

}