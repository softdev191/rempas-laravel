<?php

return [

    /** set your paypal credential **/
    'client_id' =>'Afwikv13sUlG9vFCd5LJ2hSFrPCegHiKr4bglUTOke8i0W4D9ztXfQBGLZtbQcgAC3FNgQSPvqS-N5C9',
    'secret' => 'EBvHkHCSEIuX4hsNHtIU7u5mk90f75HN6H-REiZ0SOtWJeS4kRagVi7XbPIhzAGR82RfJO5zorQdVI38',
    /**
    * SDK configuration 
    */
    'settings' => [
        /**
        * Available option 'sandbox' or 'live'
        */
        'mode' => 'live',
        /**
        * Specify the max request time in seconds
        */
        'http.ConnectionTimeOut' => 20000,
        /**
        * Whether want to log to a file
        */
        'log.LogEnabled' => true,
        /**
        * Specify the file that want to write on
        */
        'log.FileName' => storage_path() . '/logs/paypal.log',
        /**
        * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
        *
        * Logging is most verbose in the 'FINE' level and decreases as you
        * proceed towards ERROR
        */
        'log.LogLevel' => 'FINE'
    ],


];
