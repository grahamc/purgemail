#!/usr/bin/php
<?php

/**
 * @see http://www.php.net/imap_open for construction tips
 */
define('SERVICE_GMAIL',         '{imap.gmail.com:993/imap/ssl}INBOX');
define('SERVICE_RACKSPACE',     '{secure.emailsrvr.com:993/imap/ssl}INBOX');


/**
 * @see http://php.net/imap_search for search patterns
 */
define('FILTER_LINKED_IN',      'SEEN FROM "@linkedin.com"');
define('FILTER_MINT',           'SEEN FROM "team@mint.com"');
define('FILTER_FACEBOOK',       'SEEN FROM "facebookmail.com"');
define('FILTER_LIVINGSOCIAL',   'SEEN FROM "deals@livingsocial.com"');
define('FILTER_GITHUB',         'SEEN FROM "@reply.github.com"');
define('FILTER_LOOTLOOK',       'SEEN FROM "looker@lootlook.com"');
define('FILTER_VENMO',          'SEEN FROM "venmo@venmo.com"');
define('FILTER_TWITTER',        'SEEN FROM "postmaster.twitter.com"');
define('FILTER_ASSEMBLA',       'SEEN FROM "@alerts.assembla.com"');
define('FILTER_PINGDOM',        'SEEN FROM "alert@pingdom.com"');

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

foreach ($accounts as $account) {
    echo "\n\n" . $account['username'] . "\n";
    $imap = imap_open($account['dsn'], $account['username'], $account['password']);

    foreach ($account['criteria'] as $c) {
        searchAndPurge($c, $imap);
    }

    imap_close($imap);
}

/**
 * Search for emails based on a pattern
 * 
 * @param string $search imap_search compatable pattern
 * @param resource $imap 
 * @see http://php.net/imap_search
 */
function searchAndPurge($search, $imap)
{
    $x = imap_search($imap, $search);

    if ($x) {
        $r = imap_fetch_overview($imap, implode($x, ','));
        foreach ($r as $email) {
            echo $email->subject . "\n";
            imap_delete($imap, $email->uid, FT_UID);
        }
    }

    imap_expunge($imap);
}
