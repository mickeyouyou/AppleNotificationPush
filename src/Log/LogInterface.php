<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, LogInterface.php, 2015-09-08 21:04 created (updated)$
 */

namespace AppleNotificationPush\Log;


interface LogInterface
{

    public function log($level, $context);

}