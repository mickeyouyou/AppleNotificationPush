<?php
/**
 * APns Push Item
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Message.php, 2015-09-09 00:16 created (updated)$
 */

namespace AppleNotificationPush\Message;


use AppleNotificationPush\Exception\CustomerDataException;

class Message implements MessageInterface
{

    /**
     *
     *
     * @var
     */
    protected $deviceToken;

    /**
     *
     *
     * @var
     */
    protected $identifier;

    /**
     *
     *
     * @var
     */
    protected $expires;

    protected $priority;

    protected $aps;


    /**
     *
     * @param null $deviceToken
     * @param null $alert
     */
    public function __construct($deviceToken = null, $alert = null)
    {
        $this->aps = new Aps();
        $this->customerData = array();
        /**
         * Beijing Timezone
         */
        $this->expires = new \DateTime('+8hours', new \DateTimeZone('UTC'));

        if(null !== $deviceToken) {
            $this->setDeviceToken($deviceToken);
        }

        if(null !== $alert) {
            $this->setAlert($alert);
        }


    }


    /**
     * @param $identifier
     * @return mixed
     */
    public function setIdentifier($identifier)
    {

        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {

        return $this->identifier;
    }

    /**
     * @param $deviceToken
     * @return mixed
     */
    public function setDeviceToken($deviceToken)
    {
        $this->deviceToken = $deviceToken;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }

    /**
     * @param $badge
     * @return $this
     */
    public function setBadge($badge)
    {
        $this->aps->setBadge($$badge);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBadge()
    {

        return $this->aps->getBadge();
    }

    /**
     * @param $sound
     * @return $this
     */
    public function setSound($sound)
    {
        $this->aps->setSound($sound);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSound()
    {
        return $this->aps->getSound();
    }

    /**
     * @param $expires
     * @return mixed
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     *
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param $priority
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param $alert
     * @return mixed
     */
    public function setAlert($alert)
    {

        $this->aps->setAlert($alert);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlert()
    {
        return $this->aps->getAlert();
    }

    /**
     *
     * @param array $data
     * @return mixed
     * @throws CustomerDataException
     */
    public function setCustomerData(Array $data)
    {
        if(empty($data)) {
            return ;
        }

        if(!is_array($data)) {
            throw new CustomerDataException('customer data type need be array');
        }

        foreach($data as $key => $datum) {
            $this->customerData[$key] = $datum;
        }

        return $this;
    }

    /**
     * return data without index aps
     * @return mixed
     */
    public function getCustomerData()
    {
        return $this->customerData;
    }

    public function getPayloadData()
    {
        return array(
            'aps' => $this->aps->getApsData()
        ) + $this->customerData;
    }
}