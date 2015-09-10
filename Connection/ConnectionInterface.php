<?php
/**
 * Apns Push Connection Interface
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, ConnectionInterface.php, 2015-09-08 20:21 created (updated)$
 */

namespace AppleNotificationPush\Connection;


interface ConnectionInterface
{
    /**
     *
     * < @type integer Production environment.
     */
    const ENVIRONMENT_PRODUCTION = 0;

    /**
     *
     * < @type integer Sandbox environment.
     */
    const ENVIRONMENT_SANDBOX = 1;

    /**
     * create connection
     * @return mixed
     */
    public function create();


    /**
     * close connection
     *
     * @return mixed
     */
    public function close();

    /**
     * @param $data
     * @param $length
     * @return mixed
     */
    public function write($data, $length);


    /**
     * @param $length
     * @return mixed
     */
    public function read($length);

    /**
     * @return mixed
     */
    public function isReadyRead();


    /**
     * @return mixed
     */
    public function getUrl();

}