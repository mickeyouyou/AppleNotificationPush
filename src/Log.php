<?php
/**
 * Apn Logger
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Log.php, 2015-09-02 16:13 created (updated)$
 */

namespace Push;

use \AppleNotificationPush\Log\Log as BaseLog;

class Log
{

    private $logger;

    public function __construct($dir = '/data/logs/apns/')
    {
        $this->logger = new BaseLog($dir);
    }


    /**
     * write context to the log file
     * @param string $level
     * @param $context
     */
    public function log($level = 'info', $context)
    {
        $this->logger->log($level, $context);
    }

}