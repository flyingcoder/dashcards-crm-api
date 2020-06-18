<?php

return [
    'channels' => [
        'event' => [
            'log' => 'event.log',
            'daily' => false,
            'level' => 'debug'
        ],
        'stripe' => [
            'log' => 'stripe.log',
            'daily' => false,
            'level' => 'debug'
        ],
        'payment' => [
            'log' => 'payment.log',
            'daily' => false,
            'level' => 'info'
        ],
    ]
];
