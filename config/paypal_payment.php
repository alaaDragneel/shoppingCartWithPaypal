<?php

return [

    /*
    |--------------------------------------------------------------------------
    | User Paypal Account
    |--------------------------------------------------------------------------
    |
    | All values Come From Paypal.
    | ClientId is The User id in Paypal.
    | ClientSecret is The Secret key To connect With The Paypal Account.
    |
    */

    'Account' => [
        'ClientId' => 'AYU_K7-lc_KwRak40q3CTZo5IrWSEsIeRS2sDH4opydzar1GuweGFD80kKVCCX9okzm-fhN1cq62lPhe',
        'ClientSecret' => 'EFnTeVI6nd6szVAf4MO7eT1jJGZvf2Ga1nzZPQSgWsdFYdD90ptSeAQmFjdp2k3eewhIa1l_dOtbTrE6'
    ],

    /*
    |--------------------------------------------------------------------------
    | User Paypal Account Connection && Log Setting
    |--------------------------------------------------------------------------
    |
    | All Keys Used By The Paypal SDK.
    | Mode See If Your App In Production OR Development[Sandbox].
    | Http.ConnectionTimeOut Set The Time Out By Passing The Number Of Seconds.
    | log.LogEnable Enable The Log.
    | logFileName Use File TO Log In It By Default [ public_path('/logs/paypal.log') ].
    | log.LogLevel Choose the Log Level By Default [ FINE ].
    |
    */

    'Setting' => [
        'mode' => 'sandbox',
        'http.ConnectionTimeOut' => '30',
        'log.LogEnable' => 'true',
        'logFileName' => public_path('/logs/paypal.log'),
        'log.LogLevel' => 'FINE'
    ],

];
