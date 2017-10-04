<?php

namespace Api;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'api/V1/concert' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/V1/concert[/:id]',
                    'constraints' => [
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\ConcertController::class
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ConcertController::class => Controller\Factory\ConcertControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
