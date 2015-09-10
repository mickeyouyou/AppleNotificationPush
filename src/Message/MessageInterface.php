<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, MessageInterface.php, 2015-09-09 00:16 created (updated)$
 */

namespace AppleNotificationPush\Message;


interface MessageInterface
{


    /**
     * @param $identifier
     * @return mixed
     */
    public function setIdentifier($identifier);


    /**
     * @return mixed
     */
    public function getIdentifier();

    /**
     * @param $deviceToken
     * @return mixed
     */
    public function setDeviceToken($deviceToken);


    /**
     * @return mixed
     */
    public function getDeviceToken();



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
     * @param $expires
     * @return mixed
     */
    public function setExpires($expires);

    public function getExpires();

    /**
     * @param $priority
     * @return mixed
     */
    public function setPriority($priority);

    /**
     * @return mixed
     */
    public function getPriority();


    /**
     * @param $data
     * @return mixed
     */
    public function setCustomerData(Array $data);

    /**
     * @return mixed
     */
    public function getCustomerData();

}