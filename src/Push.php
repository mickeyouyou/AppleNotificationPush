<?php
/**
 * 苹果客户端推送类
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Push.php, 2015-08-28 16:14 created (updated)$
 */

namespace Push;


class Push
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
     *
     *
     * @var $environment int
     */
    private $environment;

    /**
     *
     *
     * @var array Service URLs environments.
     */
    protected $serviceURLs = array(
        'tls://gateway.push.apple.com:2195', // Production environment
        'tls://gateway.sandbox.push.apple.com:2195' // Sandbox environment
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
    private $url;


    /**
     * Instantiate socket object
     *
     * @var $socket object
     */
    protected $socket;


    /**
     *
     *
     * @var array Error-response messages.
     */
    protected $errorResponseMessages = array(
        0   => 'No errors encountered',
        1   => 'Processing error',
        2   => 'Missing device token',
        3   => 'Missing topic',
        4   => 'Missing payload',
        5   => 'Invalid token size',
        6   => 'Invalid topic size',
        7   => 'Invalid payload size',
        8   => 'Invalid token',
        10  => 'ShutDown',
        255 => 'Unknown Error'
    );

    /**
     * default log handler
     *
     * @var object log
     */
    public $logger;


    public function __construct($environment, $certificate, $passphrase)
    {
        // check empty
        if (empty($certificate)) {
            throw new ApnException('certificate file is empty');
        }

        // file exist and readable trial
        if(!is_readable($certificate)) {
            throw new ApnException(
                "Unable to read certificate file {$certificate}"
            );
        }

        $this->certificate = $certificate;
        $this->passphrase  = $passphrase;
        $this->environment = $environment;
        $this->url = $this->serviceURLs[$this->environment];
    }

    /**
     * Connects to Apple Push Notification service server.
     *
     * @return bool True if successful connected.
     * @throws ApnException if is unable to connect.
     */
    public function connect()
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certificate);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->passphrase);

        $this->socket = @stream_socket_client($this->url, $errno, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$this->socket) {
            $this->log('error', var_export("unable to connect to apple push gateway (Unknown error)", true));
            throw new ApnException("ERROR: '{$errno}' - '{$errstr}'");
        }

        stream_set_blocking($this->socket, 0);
        stream_set_write_buffer($this->socket, 0);

        $this->log('info', "Connected to APNS '{$this->url}'");

        return true;
    }


    /**
     *
     * @param $deviceToken
     * @param $message
     */
    public function send($deviceToken, $message)
    {
        $payload = json_encode($message);
        $msg = chr(0) .
            pack('n', 32) .
            pack('s', $deviceToken) .
            pack('n', strlen($payload)) .
            $payload;

        /**
         * fwrite() returns the number of bytes written, or FALSE on error.
         */
        $result = fwrite($this->socket, $msg, strlen($msg));

        $this->_readErrorMessage();

        if(strlen($msg) !== (int) $result) {
            $this->log('info', 'come in');
            $this->_readErrorMessage();
        }
        if (!$result){
            $this->log('info', 'Message not Delivered : ' . var_export($this->_parseErrorMessage($result), true));
        } else {
            $this->log('info', 'Message successfully delivered : ' . var_export($payload, true));
        }
    }

    /**
     * Disconnects from Apple Push Notifications service server.
     *
     * @return bool
     */
    public function disconnect()
    {
        if(is_resource($this->socket)) {
            return fclose($this->socket);
        }

        return false;
    }

    private function _readErrorMessage()
    {
        $responseMessage = fread($this->socket, 6);

        if(empty($responseMessage)) {
            $this->log('info', 'yeah it is empty error resopnse');
        }

        $this->log('info',  var_export($responseMessage, true));

        if($responseMessage === false || strlen($responseMessage) != 6) {
            return false;
        }

        $errorMessage = $this->_parseErrorMessage($responseMessage);
        $this->log('info', var_export($errorMessage, true));
    }


    private function _parseErrorMessage($errorMessage)
    {
        return unpack('Ccommand/CstatusCode/Nidentifier', $errorMessage);
    }


    public function log($level = 'info', $context)
    {
        $logger = new Log('/data/logs/apns/');
        $logger->log($level, $context);

    }

}