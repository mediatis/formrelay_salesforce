<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Form Relay - SalesForce Plugin',
    'description' => 'Send form data to SFDC via Web-To-Lead API',
    'category' => 'be',
    'author' => '',
    'author_email' => '',
    'author_company' => 'Mediatis AG',
    'state' => 'alpha',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '8.5.0-9.5.99',
            'formrelay' => '>=2.0.0',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
    'suggests' => [
    ],
];
