<?php

namespace jzweb\sat\ccbll\Lib;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Monolog
 */
class Log
{

    private $log;
    private $fileName = 'jzweb-sat-ccbpay';

    /**
     * 构造
     */
    public function __construct($config)
    {
        // create a log channel
        $this->log = new Logger(basename(__FILE__));
        $this->log->pushHandler(new StreamHandler($config['log_path'] . $this->fileName . '-' . date("Y-m-d") . '.log'));

    }

    /**
     * 写日志
     * @param   [type] $message                 [description]
     * @param   string $level [description]
     * @return  [type]                          [description]
     */
    public function log($message, $level = 'info')
    {
        return $this->log->log($level, $message);
    }
}

?>