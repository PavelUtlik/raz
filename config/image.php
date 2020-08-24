<?php


return [
//
//    'user_photo' => [
//        'limit'=>10,
//        'save_path' =>  storage_path('app/public/files/images/user_photos'),
//    ],
//    'meeting_photo' => [
//        'save_path' =>  storage_path('app/public/files/images/meeting_photos'),
//    ],

    'user_photo' => [
        'limit'=>10,
        'save_path' =>  public_path('files/images/user_photos'),
        'url' =>  config()->get('app.url') . 'files/images/user_photos/'
    ],
    'meeting_photo' => [
        'save_path' =>  public_path('files/images/meeting_photos'),
        'url' =>  config()->get('app.url') . 'files/images/meeting_photos/'
    ],

    'meeting_chat_photo' => [
        'save_path' =>  public_path('files/images/meeting_chat_photos'),
        'url' =>  config()->get('app.url') . 'files/images/meeting_chat_photos/'
    ],


];