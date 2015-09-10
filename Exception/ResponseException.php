<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, ResponseException.php, 2015-09-09 16:14 created (updated)$
 */

namespace AppleNotificationPush\Exception;


use AppleNotificationPush\Message\Message;

class ResponseException extends ApnsPushException
{
    protected static $errorResponseMessages = array(
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
        255 => 'Unknown Error',
        256 => 'Unpack response error'
    );

    protected $statusCode;

    protected $command;

    protected $identifer;

    protected $messageObject;

    public function __construct($command = null, $statusCode = null, $identifier = null, Message $message)
    {
        if (isset(self::$errorResponseMessages[$statusCode])) {
            $messageStr = self::$errorResponseMessages[$statusCode];
        } else {
            $messageStr = 'Undefined error with status: "' . $statusCode. '".';
        }

        parent::__construct($messageStr, $statusCode);

        $this->statusCode = $statusCode;
        $this->command    = $command;
        $this->identifer  = $identifier;
        $this->messageObject = $message;
    }


    /**
     * parse the Error Response Packet
     * @param $errorMessage
     * @return array
     */
    static public function parseErrorMessage($errorMessage, Message $message)
    {
        $unpackError = false;

        // Register custom error handler for control unpack error
        set_error_handler(function () use (&$unpackError) {
            $unpackError = true;
        });

        $errorMessageArray = unpack('Ccommand/CstatusCode/Nidentifier', $errorMessage);

        // Restore custom error handler
        restore_error_handler();

        if ($unpackError) {
            return new static(256, 0, 0, $message);
        }

        return new static(
            $errorMessageArray['command'],
            $errorMessageArray['statusCode'],
            $errorMessageArray['identifier'],
            $message
        );
    }

    public function getResponseMessage()
    {

    }


    /**
     * @return bool
     */
    public function readErrorMessage()
    {
        $responseMessage = socket_read($this->socket, 6);

        if(empty($responseMessage)) {
            $this->log('info', 'yeah it is empty error resopnse');
            return false;
        }

        $this->log('info', '非空的错误响应报在此：' . var_export($responseMessage, true));

        if($responseMessage === false || strlen($responseMessage) != 6) {
            return false;
        }

        $errorMessage = $this->_parseErrorMessage($responseMessage);
        $this->log('info', var_export($errorMessage, true));
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {

        return $this->command;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifer;
    }


}