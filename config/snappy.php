<?php

return array(


    'pdf' => array(
        'enabled' => true,
        // 'binary'  => '/usr/local/bin/wkhtmltopdf',
        'binary'  => '/home/vagrant/Code/migrations/wkhtmltox/bin/wkhtmltopdf',
        'timeout' => false,
        'options' => array(
            // 'page-size' => 'A4',
            'page-width' => 215.9,
            'page-height' => 330.2,
            'margin-top' => 34,
            'margin-right' => 20,
            'margin-left' => 20,
            'margin-bottom' => 15,
            'orientation' => 'Landscape',
            'footer-font-size' => 8,
            'footer-right' => '[page]',
        ),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => '/usr/local/bin/wkhtmltoimage',
        'timeout' => false,
        'options' => array(),
        'env'     => array(),
    ),


);
