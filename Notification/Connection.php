<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Connection.php, 2015-09-08 21:25 created (updated)$
 */

namespace AppleNotificationPush\Notification;

use \AppleNotificationPush\Connection\Connection as BaseConnection;
class Connection extends BaseConnection
{

    public function create()
    {
        parent::create();

        return $this;
    }



}