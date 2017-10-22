<?php

//constants
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT_PATH', dirname(dirname(__DIR__)));

define('SRC_PATH', ROOT_PATH . DS . 'src');
define('SPEC_PATH', ROOT_PATH . DS . 'spec');

define('TESTS_CONFIG_PATH', SPEC_PATH . DS . 'config');
define('TESTS_SUITE_PATH', SPEC_PATH . DS . 'suite');

//CLI override
$commandLine = $this->commandLine();
$commandLine->option('cc', 'default', true);
$commandLine->option('reporter', 'default', 'verbose');

