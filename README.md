# Email Storage stream for Logging 

Send log by email using one of configured `EmailConfig` in `app/Config/email.php`

## Usage

Add to app bootstrap:

``` php
<?php
CakePlugin::load('EmailLog');
App::uses('CakeLog', 'Log');
CakeLog::config('email', array(
	'engine' => 'EmailLog.EmailLog', 
	'emailConfig' => 'default', // optional
	'subjectFormat' => ':type  :date  @ :host', // optional
	'logTypes' => array('info', 'notice', 'warrning', 'debug', 'error')  // optional
));
```

Cake will autoconfigure file log, but only if there's no already configured handlers. So append file config too:

``` php
CakeLog::config('file', array('engine' => 'FileLog'));
```

Test it now in controller:

``` php
CakeLog::write('error', 'Missing key: '. print_r($this->request, true));
```

## Requirements

    PHP version: PHP 5.2+
    CakePHP version: 2.x
