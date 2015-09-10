<?php
/**
 *
 *
 * @author fengzbao@qq.com
 * @copyright Copyright (c) fzb.me
 * @version $Id:1.0.0, Aps.php, 2015-09-09 13:10 created (updated)$
 */

namespace AppleNotificationPush\Message;


class Aps implements ApsInterface
{

    private $alert;

    private $sound;

    private $badge;

    private $category;


    /**
     * @param $alert
     * @return mixed
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * @param $badge
     * @return mixed
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getBadge()
    {

        return $this->badge;
    }

    /**
     * @param $sound
     * @return mixed
     */
    public function setSound($sound)
    {

        $this->sound = $sound;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSound()
    {

        return $this->sound;
    }

    /**
     * @param $category
     * @return mixed
     */
    public function setCategory($category)
    {

        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {

        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getApsData()
    {
        $apsData = array(
            'alert' => $this->alert
        );

        if (null !== $this->sound) {
            $apsData['sound'] = $this->sound;
        }

        if (null !== $this->badge) {
            $apsData['badge'] = $this->badge;
        }

        if (null !== $this->category) {
            $apsData['category'] = $this->category;
        }

        return $apsData;
    }
}