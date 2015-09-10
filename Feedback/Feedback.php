<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Feedback.php, 2015-09-09 17:19 created (updated)$
 */

namespace AppleNotificationPush\Feedback;


use AppleNotificationPush\Connection\Connection;
use AppleNotificationPush\Log;

class Feedback implements FeedbackInterface
{

    private $connection;

    /**
     * @param null $connection
     */
    public function __construct($connection = null)
    {
        if(null !== $connection) {
            if ($connection instanceof Connection) {
//                $this->log('info', "yes it's a instance of Connection");
                $this->connection = $connection;
            } elseif (is_string($connection)) {
                // todo
                $this->connection = new \AppleNotificationPush\Feedback\Connection($connection);
            }
        }
    }

    /**
     * @param Connection $connection
     * @return mixed
     */
    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return mixed
     */
    public function getInvalidDevices()
    {
        if (!$this->connection->is()) {

            $this->log('info', 'Create feedback connection...');

            $this->connection->create();
        }

        $data = $this->connection->read(38);

        $this->connection->close();

        $feedback = array();

        if ($data) {
            foreach (str_split($data, 38) as $deviceData) {
                $feedback[] = new Device($deviceData);
            }
        }

        $this->log('info', sprintf(
            '%d device tokens received from feedback service.',
            count($feedback)
        ));

        return $feedback;


    }

    /**
     * @param $level
     * @param $context
     */
    public function log($level, $context)
    {
        $logger = new Log();
        $logger->log($level, $context);
    }
}