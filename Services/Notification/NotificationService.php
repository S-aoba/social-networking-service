<?php

namespace Services\Notification;

use Database\DataAccess\Interfaces\ProfileDAO;
use Services\Image\ImagePathResolver;

class NotificationService
{
  public static function enrichNotificationsWithProfileImage(
    array $notifications, 
    ProfileDAO $profileDAO, 
    ImagePathResolver $imagePathResolver
    ): void
  {
    foreach ($notifications as $notification) {
        $data = $notification->getData();
        if(isset($data['userId']) === false) {
          throw new \Exception('User ID is missing in notification data.');
        }
        $profile = $profileDAO->getByUserId($data['userId']);
        if($profile === null) {
          throw new \Exception('Profile not found for user ID: ' . $data['userId']);
        }
        
        $imagePathResolver->resolveProfile($profile);
        $data['imagePath'] = $profile->getImagePath();
        $notification->setData($data);
    }
  }
}