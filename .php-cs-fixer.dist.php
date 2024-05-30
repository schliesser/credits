<?php

$config = \TYPO3\CodingStandards\CsFixerConfig::create();

$config->getFinder()
    ->exclude('.Build')
    ->exclude('.github')
    ->exclude('var')
    ->in(__DIR__);
$config->addRules([
    'function_declaration' => [
        'closure_function_spacing' => 'one',
    ],
]);
return $config;
