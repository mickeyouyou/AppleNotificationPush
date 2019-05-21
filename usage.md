

### Useage

use AppleNotificationPush\Notification;
use AppleNotificationPush\Notification\Connection;

```php
// First argument - sandbox mode
$connection = new Connection(true, '/path/to/your/certificate.pem', 'your_passphrase');
$notification = new Notification($connection);
```


After create notification service your can send push message to apple devices.

Base example:

```php
$notification->sendMessage('device_token', 'Hello world');
```


Multifle Example:

```php 
foreach($devices as $device)
{
    $notification->sendMesage($device, $message);
}
```
