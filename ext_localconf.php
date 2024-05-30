<?php

declare(strict_types=1);

defined('TYPO3') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Credits',
    'Images',
    [
        \Schliesser\Credits\Controller\CopyrightController::class => 'images',
    ],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
