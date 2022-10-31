<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Form Relay - Salesforce Plugin',
    'description' => 'Send form data to SFDC via Web-To-Lead API',
    'category' => 'be',
    'author' => '',
    'author_email' => '',
    'author_company' => 'Mediatis AG',
    'state' => 'alpha',
    'version' => '5.0.2',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.5.99',
            'formrelay' => '>=5.0.0',
        ],
        'conflicts' => [
        ],
        'suggests' => [
            'news' => '*',
        ],
    ],
];
