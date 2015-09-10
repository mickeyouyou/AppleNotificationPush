## AppleNotificationPush
Send push notification to Apple Devices (iPhone, iPad) 




### Useage

```php
$message = new \AppleNotificationPush\Message\Message();

        $message->setAlert('SEARCH_ACTIVITY title ');
        $message->setSound('default');
        $message->setIdentifier(mt_rand(10, 1000));
        $message->setDeviceToken('f83f0f2fc1efc0a549601437128bf2d94fea83b4c31b0750d3d9f8f98d5e7a87');
        $message->setPriority(10);
//        $message->setCustomerData(array('id'=> 123));
//        $message->setCustomerData(array('url' => 'sdfd'));

        $message->setCustomerData(array(
            'id' => mt_rand(10, 2000),
            'content' => 'SEARCH_ACTIVITY content',
            'url' => 'MY_ORDER_ACTIVITY_UNRECEIVE',
            'extra' => '[]'
        ));


//        $message->setDeviceToken('ss')->setSound('s')->setAlert('alert');
//        var_dump($message->getPayloadData());

        $certificate = BASEPATH . "../shared/config/development/ck.pem";
        $connection = new Connection(1, $certificate, C('jpush.apns.password'));
        $notification = new Notification($connection);
        $notification->sendMessage($message);     
```



#### Feedback

```php

    public function feedback()
    {
        $certificate = BASEPATH . "../shared/config/development/ck.pem";
        $connection = new \AppleNotificationPush\Feedback\Connection(1, $certificate, C('jpush.apns.password'));


//        var_dump($connection);

        $feedback = new Feedback($connection);

        $feedback->getConnection()->create();

        var_dump($feedback->getInvalidDevices());
//        var_dump($feedback);
    } 
```


### Logger

将调试或者记录的权限完全交给开发者，自由定义。

Use like:

```php
$this->log('info', sprintf(
            '%d device tokens received from feedback service.',
            count($feedback)
        ));
```