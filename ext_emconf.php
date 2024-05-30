<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Credits: Image copyright listing',
    'description' => 'Give credits: Automatically list all used images with their copyright. Multisite support.',
    'category' => 'fe',
    'author' => 'André Buchmann',
    'author_email' => 'andy.schliesser@gmail.com',
    'state' => 'stable',
    'clearCacheOnLoad' => false,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
        ],
    ],
];
