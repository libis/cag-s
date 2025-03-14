<?php
return [
    'block_layouts' => [
        'invokables' => [
            'CagLedenbladenBlock' => CagLedenbladenBlock\Site\BlockLayout\CagLedenbladenBlock::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ]
];
