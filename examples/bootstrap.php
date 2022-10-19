<?php

require_once(__DIR__
    . DIRECTORY_SEPARATOR
    . '..'
    . DIRECTORY_SEPARATOR
    . 'vendor'
    . DIRECTORY_SEPARATOR
    . 'autoload.php');

Tracy\Debugger::enable(false, realpath('../logs'));
Tracy\Debugger::$strictMode = false;
Tracy\Debugger::$maxDepth = 15;
Tracy\Debugger::$maxLength = 500;
Tracy\Debugger::$showLocation = true;
