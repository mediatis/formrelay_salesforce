<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Form Relay - Salesforce Plugin',
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
    'version' => '4.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.4.99',
            'formrelay' => '>=5.0.0'
        ],
        'conflicts' => [
        ],
        'suggests' => [
            'news' => '*'
        ],
    ],
    'suggests' => [
    ],
];
