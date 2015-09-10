<?php
/**
 * Apns Logger
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Log.php, 2015-09-02 16:13 created (updated)$
 */

namespace AppleNotificationPush\Log;

class Log implements LogInterface
{

    /**
     * log dir to tail
     *
     * @var $dir log dir
     */
    private $dir;


    /**
     * log file extension default log
     *
     * @var string $logExtension
     */
    private $logExtension = ".log";



    public function __construct($dir)
    {
        self::setDir($dir);
    }

    public function setDir($dir)
    {
        // check exist or create it
        if(empty($dir)) {
            throw new ApnException("'${dir}' is empty");
        }

        if(!is_dir($dir)) {
            mkdir($dir);
        }
        // check dir writable
        if(!is_writable($dir)) {
            throw new ApnException("'${dir}' you set is can't write");
        }

        // chown
        //chown($dir, null);

        $this->dir = $dir;
    }


    /**
     * write context to the log file
     * @param string $level
     * @param $context
     */
    public function log($level = 'info', $context)
    {
        if(substr($this->dir, -1) == DIRECTORY_SEPARATOR) {
            $logFile = $this->dir . $level . $this->logExtension;
        } else {
            $logFile = $this->dir . DIRECTORY_SEPARATOR . $level. $this->logExtension;
        }

        $fd = @fopen($logFile, 'a');

        fwrite($fd, $context . PHP_EOL);
        fclose($fd);
    }

}