<?php

return [

    // Debug mode will echo connection status alerts to
    // the screen throughout the email sending process.
    // Very helpful when testing your credentials.

    'debug_mode' => false,

    // Define the different connections that can be used.
    // You can set which connection to use when you create
    // the SMTP object: ``$mail = new SMTP('my_connection')``.

    'default' => 'primary',
    'connections' => [
        'primary' => [
            'host' => 'mail.peivandi.ca',
            'port' => '26',
            'secure' => null, // null, 'ssl', or 'tls'
            'auth' => true, // true if authorization required
            'user' => 'rewards@peivandi.ca',
            'pass' => 'rewards189',
        ],
    ],

    // NERD ONLY VARIABLE: You may want to change the origin
    // of the HELO request, as having the default value of
    // "localhost" may cause the email to be considered spam.
    // http://stackoverflow.com/questions/5294478/significance-of-localhost-in-helo-localhost

    'localhost' => 'peivandi.ca', // rename to the URL you want as origin of email

];