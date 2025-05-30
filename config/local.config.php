<?php
return [
    'logger' => [
        'log' => true,
        'writers' => [
          'stream' => false,
          'job' => true,
          'syslog' => false,
        ],
        'priority' => \Laminas\Log\Logger::NOTICE,
    ],
    'http_client' => [
        'sslcapath' => null,
        'sslcafile' => null,
    ],
    'cli' => [
        'phpcli_path' => null,
    ],
    'thumbnails' => [
        'types' => [
            'large' => ['constraint' => 1000],
            'medium' => ['constraint' => 400],
            'square' => ['constraint' => 400],
        ],
        'thumbnailer_options' => [
            'imagemagick_dir' => null,
        ],
    ],
    'translator' => [
        'locale' => 'en_US',
    ],
    'service_manager' => [
        'aliases' => [
            'Omeka\File\Store' => 'Omeka\File\Store\Local',
            'Omeka\File\Thumbnailer' => 'Omeka\File\Thumbnailer\ImageMagick',
        ],
    ],
    'csrf' => [
        'options' => [
            'csrf_options' => [
                'timeout' => 1800
            ]
        ]
    ]
];
