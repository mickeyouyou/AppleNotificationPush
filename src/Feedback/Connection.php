<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Connection.php, 2015-09-09 17:25 created (updated)$
 */

namespace AppleNotificationPush\Feedback;

use AppleNotificationPush\Connection\Connection as BaseConnection;

class Connection extends BaseConnection
{


    /**
     * @return $this
     * @throws \AppleNotificationPush\Exception\ConnectionException
     */
    public function create()
    {
        parent::create();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        if($this->environment = self::ENVIRONMENT_SANDBOX) {
            $this->url = $this->feedbackServiceURLs[self::ENVIRONMENT_SANDBOX];
        } else {
            $this->url = $this->feedbackServiceURLs[self::ENVIRONMENT_PRODUCTION];
        }

        return $this->url;
    }


}