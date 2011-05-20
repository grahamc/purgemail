<?php

/**
 * An array of your accounts and filter types.
 */
$accounts = array(
    array(
        // DSN must be compatable with imap_open: http://www.php.net/imap_open
        'dsn' => SERVICE_RACKSPACE,
        'username' => 'rackspace@example.com',
        'password' => 'password',
        
        // For the criteria, you may use any of the pre-defined filters,
        'criteria' => array(
            FILTER_GITHUB,
            FILTER_PINGDOM,
            
            // Or you may create a custom one. Note: If you screw up these
            // filters, you may delete all your email... careful.
            'SEEN FROM "custom@example.com"',
        ),
    ),
);