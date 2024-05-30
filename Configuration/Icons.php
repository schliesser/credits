<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'tx-credits-images' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:credits/Resources/Public/Icons/Extension.svg',
    ],
];
