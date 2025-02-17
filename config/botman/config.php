<?php

return [
    'conversation_cache_time' => 40,
    'user_cache_time' => 30,
    
    /*
    |--------------------------------------------------------------------------
    | Conversation Cache Store
    |--------------------------------------------------------------------------
    |
    | BotMan will use this store to save conversation state and information.
    |
    */
    'conversation_cache_store' => 'file',

    /*
    |--------------------------------------------------------------------------
    | Bot Storage
    |--------------------------------------------------------------------------
    |
    | BotMan will use this storage to save user & conversation states.
    |
    */
    'storage' => [
        'driver' => 'file',
    ],
];