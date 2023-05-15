<?php

/**
 * ext_emconf.php
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Giphy',
    'description' => 'Giphy for the masses.',
    'category' => 'misc',
    'author' => 'Carsten Walther',
    'author_email' => 'walther.carsten@web.de',
    'author_company' => '',
    'state' => 'stable',
    'clearcacheonload' => true,
    'uploadfolder' => true,
    'version' => '12.4.1',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-12.4.99'
        ]
    ]
];
