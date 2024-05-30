<?php

declare(strict_types=1);

defined('TYPO3') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Credits',
    'Images',
    'LLL:EXT:credits/Resources/Private/Language/locallang_db.xlf:element.label',
    'tx-credits-images',
    'special',
    'LLL:EXT:credits/Resources/Private/Language/locallang_db.xlf:element.description',
);
