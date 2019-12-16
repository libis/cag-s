<?php
return [
    'block_layouts' => [
        'invokables' => [
            'GalleryBlock' => GalleryBlock\Site\BlockLayout\GalleryBlock::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ]
];
