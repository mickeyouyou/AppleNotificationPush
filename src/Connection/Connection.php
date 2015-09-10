<?php
/**
 * Apns Push Connection
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Connection.php, 2015-09-08 19:29 created (updated)$
 */

namespace AppleNotificationPush\Connection;


use AppleNotificationPush\Exception\ConnectionException;
use AppleNotificationPush\Exception\SocketErrorException;
use AppleNotificationPush\Log;

abstract class Connection implements  ConnectionInterface
{

    /**
     * @var $environment int
     */
    protected $environment;

    /**
     *
     *
     * @var array Service URLs environments.
     */
    protected $serviceURLs = array(
        'tls://gateway.push.apple.com:2195', // Production environment
        'tls://gateway.sandbox.push.apple.com:2195' // Sandbox environment
    );


    protected $feedbackServiceURLs = array(
        'tls://feedback.push.apple.com:2196', // Production environment
        'tls://feedback.sandbox.push.apple.com:2196' // Sandbox environment
    );


    /**
     * certificate file path
     *
     * @var $certificate string
     */
    private $certificate;


    /**
     * passphrase for type of pem certificate
     *
     * @var $passphrase string
     */
    private $passphrase;

    /**
     * choosed
     *
     * @var string
     */
    protected $url;


    /**
     * Instantiate socket object
     *
     * @var $socket object
     */
    protected $socket;


    /**
     * Create Socket to
     * @param $environment
     * @param $certificate
     * @param $passphrase
     * @throws ConnectionException
     */
    public function __construct($environment, $certificate, $passphrase)
    {
        // check empty
        if (empty($certificate)) {
            throw new ConnectionException('certificate file is empty');
        }

        // file exist and readable trial
        if(!is_readable($certificate)) {
            throw new ConnectionException(
                "Unable to read certificate file {$certificate}"
            );
        }

        $this->certificate = $certificate;
        $this->passphrase  = $passphrase;
        $this->environment = $environment;

    }

    /**
     * destruct close the connection
     */
    public function __destruct()
    {
        $this->close();
    }

    public function create()
    {
        // Connection already created
        if($this->socket) {
            return $this;
        }

        $context = stream_context_create();
        stream_context_set_option($context, 'ssl', 'local_cert', $this->certificate);
        stream_context_set_option($context, 'ssl', 'passphrase', $this->passphrase);

        $this->socket = stream_socket_client(
            $this->getUrl(),
            $errno,
            $errstr,
            ini_get('default_socket_timeout'),
            STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT,
            $context
        );

        if (!$this->socket) {
            $this->log('error', var_export("unable to connect to apple push gateway (Unknown error)", true));
            throw new ConnectionException("ERROR: '{$errno}' - '{$errstr}'");
        }

        stream_set_blocking($this->socket, 0);
        stream_set_write_buffer($this->socket, 0);

        $this->log('info', "Connected to APNS '{$this->url}'");

        return true;
    }


    /**
     * write the data
     * @param $data
     * @param $length
     * @return int the number of bytes written, or FALSE on error.
     * @throws SocketErrorException
     */
    public function write($data, $length)
    {
        if(!$this->socket) {
            throw new SocketErrorException('Can\'t write to socket. Socket not created.');
        }

        return fwrite($this->socket, $data, $length);

    }

    /**
     *
     * @param $length
     * @return string read from socket connection
     * @throws SocketErrorException
     */
    public function read($length)
    {
        if(!$this->socket) {
            throw new SocketErrorException('Can\'t read from socket. Socket not created.');
        }

        return stream_get_contents($this->socket, $length, -1);
        // other method
        // fread
        // socket_read

    }

    /**
     * @return bool
     */
    public function is()
    {
        return (bool) $this->socket;
    }


    /**
     * check stream is ready for read
     * @return bool
     * @throws SocketErrorException
     */
    public function isReadyRead()
    {
        if(!$this->socket) {
            throw new SocketErrorException('Can\'t check ready socket. Socket not created.');
        }

        $selectRead = array($this->socket);

        $null = null;

        list($seconds, $uSeconds) = array(1, 0);

        /**
         * This is the magic function - explained below
         */
        return (bool) stream_select($selectRead, $null, $null, $seconds, $uSeconds);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if($this->environment = self::ENVIRONMENT_SANDBOX) {
            $this->url = $this->serviceURLs[self::ENVIRONMENT_SANDBOX];
        } else {
            $this->url = $this->serviceURLs[self::ENVIRONMENT_PRODUCTION];
        }

        return $this->url;
    }


    /**
     * close connection
     * @return bool
     */
    public function close()
    {
        if(is_resource($this->socket)) {
            return fclose($this->socket);
        }

        return false;
    }

    /**
     * @param $level
     * @param $context
     */
    public function log($level, $context)
    {
        $log = new Log();
        $log->log($level, $context);
    }

}