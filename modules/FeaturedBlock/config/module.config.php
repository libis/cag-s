<?php
return [
    'block_layouts' => [
        'invokables' => [
            'FeaturedBlock' => FeaturedBlock\Site\BlockLayout\FeaturedBlock::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ]
];
