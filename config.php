<?php

return [
    'db' => [
        'username' => '%db.user%',
        'password' => '%db.password%',
        'dbname' => '%db.database%',
        'host' => 'localhost',
        'port' => '3306',
    ],
    'default_language' => 'de',
    'blacklisted_subdomains' => [
        '',
        'www',
    ],
    'menu' => [
        '/',
    ],
    'template' => [
        'site_name' => 'Papertowel',
        'logoURL' => '',
        'navbar' => 'left',
        'resources' => [
            'css' => [
                '/web/css/vendor/bootstrap.css',
                '/web/css/vendor/bootstrap-theme.css',
                '/web/css/vendor/font-awesome.min.css',
                '/web/css/vendor/jquery.fancybox.min.css',
                '/web/css/global.css',
            ],
            'js' => [
                '/web/js/vendor/jquery-3.2.1.min.js',
                '/web/js/vendor/jquery.fancybox.min.js',
                '/web/js/vendor/bootstrap.min.js',
                '/web/js/vendor/bootstrap-hover-dropdown.min.js',
                'https://www.google.com/recaptcha/api.js',
                '/web/js/contact-form.js',
                '/web/js/global.js',
            ]
        ],
    ]
];