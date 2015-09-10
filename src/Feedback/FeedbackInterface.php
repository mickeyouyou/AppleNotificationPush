<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, FeedbackInterface.php, 2015-09-09 17:18 created (updated)$
 */

namespace AppleNotificationPush\Feedback;


use AppleNotificationPush\Connection\Connection;

interface FeedbackInterface
{
    /**
     * @param Connection $connection
     * @return mixed
     */
    public function setConnection(Connection $connection);


    /**
     * @return mixed
     */
    public function getConnection();

    /**
     * @return mixed
     */
    public function getInvalidDevices();


}