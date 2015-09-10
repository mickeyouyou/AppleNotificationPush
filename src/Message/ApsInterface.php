<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, ApsInterface.php, 2015-09-09 13:08 created (updated)$
 */

namespace AppleNotificationPush\Message;


/**
 * Interface ApsInterface
 * @package AppleNotificationPush\Message
 */
interface ApsInterface
{

    /**
     * @param $alert
     * @return mixed
     */
    public function setAlert($alert);

    /**
     * @return mixed
     */
    public function getAlert();

    /**
     * @param $badge
     * @return mixed
     */
    public function setBadge($badge);


    /**
     * @return mixed
     */
    public function getBadge();


    /**
     * @param $sound
     * @return mixed
     */
    public function setSound($sound);

    /**
     * @return mixed
     */
    public function getSound();

    /**
     * @param $category
     * @return mixed
     */
    public function setCategory($category);

    /**
     * @return mixed
     */
    public function getCategory();

    /**
     * @return mixed
     */
    public function getapsData();

}