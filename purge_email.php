#!/usr/bin/php
<?php

$accounts = array(
    array(
        'dsn'      => '{imap.gmail.com:993/imap/ssl}INBOX',
        'username' => '',
        'password' => '',
        'criteria' => array(
            'SEEN FROM "ci@nationalfield.org"',
            'SEEN FROM "notifications@nationalfield.org" SUBJECT "[NationalField] Ticket"',
            'SEEN FROM "@reply.github.com"',
            'SEEN FROM "notifications@nationalfieldmail.com"',
            'SEEN FROM "alert@pingdom.com"',
	    'SEEN FROM "EmailTest@nationalfield.org"',
            ),
        ),
    array(
        'dsn'      => '{imap.gmail.com:993/imap/ssl}INBOX',
        'username' => '',
        'password' => '',
        'criteria' => array(
            'SEEN FROM "otto@delectabledata.com"',
            ),
        ),
    array(
        'dsn'      => '{secure.emailsrvr.com:993/imap/ssl}INBOX',
        'username' => '',
        'password' => '',
        'criteria' => array(
            'SEEN FROM "@linkedin.com"',
            'SEEN FROM "postmaster.twitter.com"',
            'SEEN FROM "@alerts.assembla.com"',
            ),
        ),
    array(
        'dsn'      => '{secure.emailsrvr.com:993/imap/ssl}INBOX',
        'username' => '',
        'password' => '',
        'criteria' => array(
            'SEEN FROM "team@mint.com"',
            'SEEN FROM "facebookmail.com"',
            'SEEN FROM "deals@livingsocial.com"',
            'SEEN FROM "venmo@venmo.com"',
            'SEEN FROM "@reply.github.com"',
	        'SEEN FROM "looker@lootlook.com"',
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
