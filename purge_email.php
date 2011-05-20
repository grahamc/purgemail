#!/usr/bin/php
<?php

define('SERVICE_GMAIL', '{imap.gmail.com:993/imap/ssl}INBOX');
define('SERVICE_RACKSPACE', '{secure.emailsrvr.com:993/imap/ssl}INBOX');


define('FILTER_LINKED_IN', 'SEEN FROM "@linkedin.com"');
define('FILTER_MINT', 'SEEN FROM "team@mint.com"');
define('FILTER_FACEBOOK', 'SEEN FROM "facebookmail.com"');
define('FILTER_LIVINGSOCIAL', 'SEEN FROM "deals@livingsocial.com"');
define('FILTER_GITHUB', 'SEEN FROM "@reply.github.com"');
define('FILTER_LOOTLOOK', 'SEEN FROM "looker@lootlook.com"');
define('FILTER_VENMO', 'SEEN FROM "venmo@venmo.com"');
define('FILTER_TWITTER', 'SEEN FROM "postmaster.twitter.com"');
define('FILTER_ASSEMBLA', 'SEEN FROM "@alerts.assembla.com"');
define('FILTER_PINGDOM', 'SEEN FROM "alert@pingdom.com"');

$accounts = array(
    array(
        'dsn'      => SERVICE_RACKSPACE,
        'username' => 'rackspace@example.com',
        'password' => 'password',
        'criteria' => array(
            FILTER_GITHUB,
            FILTER_PINGDOM,
            ),
        ),
    array(
        'dsn'      => SERVICE_GMAIL,
        'username' => 'gmail@example.com',
        'password' => 'password',
        'criteria' => array(
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


function searchAndPurge($search, $imap) {
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
