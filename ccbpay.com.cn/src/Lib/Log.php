<?php

namespace jzweb\sat\ccbpay\Lib;

use Monolog\Formatter\LineFormatter;
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
        $dateFormat = "Y-m-d H:i:s";
        $output = "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
        $formatter = new LineFormatter($output, $dateFormat);
        $stream = new StreamHandler($config['log_path'] . $this->fileName . '-' . date("Y-m-d") . '.log');
        $stream->setFormatter($formatter);
        $this->log = new Logger(basename(__FILE__));
        $this->log->pushHandler($stream);
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
