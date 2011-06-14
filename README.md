# Email Storage stream for Logging 

Send log by email using one of configured EmailConfig (app/Config/email.php)

## Usage

Add to app bootstrap:

<pre><code>
CakePlugin::load('EmailLog');
App::uses('CakeLog', 'Log');
CakeLog::config('email', array(
	'engine' => 'EmailLog.EmailLog', 
	'emailConfig' => 'default', // optional
	'subjectFormat' => ':type  :date  @ :host', // optional
	'logTypes' => array('info', 'notice', 'warrning', 'debug', 'error')  // optional
));
</code></pre>

Cake will autoconfigure file log, but only if there's no already configured handlers. So append file config too:

<pre><code>
CakeLog::config('file', array('engine' => 'FileLog'));
</code></pre>

Test it now in controller:

<pre><code>
CakeLog::write('error', 'Missing key: '. print_r($this->request, true));
</code></pre>

## Requirements

    PHP version: PHP 5.2+
    CakePHP version: Cakephp 2.x
