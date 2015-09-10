<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Notification.php, 2015-09-08 21:19 created (updated)$
 */

namespace AppleNotificationPush\Notification;


use AppleNotificationPush\Connection\ConnectionInterface;
use AppleNotificationPush\Exception\ConnectionException;
use AppleNotificationPush\Exception\ResponseException;
use AppleNotificationPush\Log;
use AppleNotificationPush\Message\Message;

class Notification
{
    /**
     *
     *
     * @var string
     */
    private $connection;

    /**
     * @param null $connection
     */
    public function __construct($connection = null)
    {
        if(null !== $connection) {
            if($connection instanceof ConnectionInterface) {
                $this->connection = $connection;
            } elseif(is_string($connection)) {
                // todo
                $this->connection = new Connection($connection);
            }
        }

    }

    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }


    /**
     * @param Message $message
     * @throws ConnectionException
     * @throws ResponseException
     * @throws \AppleNotificationPush\Exception\SocketErrorException
     */
    public function sendMessage(Message $message)
    {
        if(!$this->connection) {
            throw new ConnectionException('connection is undefined');
        }

        if(!$this->connection->is()) {
            $this->log('info', 'Create connection...');

            $this->connection->create();
        }

        $payload = $this->createPayload($message);

        $boolean = $this->writePayload($payload);

        // catch the exception or errorResponse Packet
        if($this->connection->isReadyRead()) {

            // read the error response packet
            $errorResponse = $this->connection->read(6);

            $exception = ResponseException::parseErrorMessage($errorResponse, $message);

            $this->log('info', 'Received Error Data : ' . var_export($exception->getMessage(), true));

            /** @var ResponseException $exception */
            throw $exception;
        }

        $this->log('info', 'notification sent successfully ?');

        // todo 事件监控
    }

    /**
     * create payload data
     * @param Message $message
     * @return string
     */
    private function createPayload(Message $message)
    {
//        $payload = json_encode($message);
//        $msg = chr(0) .
//            pack('n', 32) .
//            pack('H*', $message['device_token']) .
//            pack('n', strlen($payload)) .
//            $payload;

        $payload = pack(
            'CNNnH*',
            1,
            $message->getIdentifier(),
            $message->getExpires()->format('U'),
            32,
            $message->getDeviceToken()
        );

        $jsonData = json_encode($message->getPayloadData());

        $payload .= pack('n', strlen($jsonData));
        $payload .= $jsonData;

        return $payload;
    }

    /**
     * @param $payload
     * @return bool
     * @throws \AppleNotificationPush\Exception\SocketErrorException
     */
    private function writePayload($payload)
    {
        return strlen($payload) === $this->connection->write($payload, strlen($payload));
    }


    /**
     * logger handler
     * @param $level
     * @param $context
     */
    private function log($level, $context)
    {
        $logger = new Log();
        $logger->log($level, $context);
    }


}