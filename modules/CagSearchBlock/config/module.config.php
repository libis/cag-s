<?php
return [
    'block_layouts' => [
        'invokables' => [
            'CagSearchBlock' => CagSearchBlock\Site\BlockLayout\CagSearchBlock::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ]
];
