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
define('FILTER_LINKEDIN',      'SEEN FROM "@linkedin.com"');
define('FILTER_MINT',           'SEEN FROM "team@mint.com"');
define('FILTER_FACEBOOK',       'SEEN FROM "facebookmail.com"');
define('FILTER_LIVINGSOCIAL',   'SEEN FROM "deals@livingsocial.com"');
define('FILTER_GITHUB',         'SEEN FROM "@reply.github.com"');
define('FILTER_LOOTLOOK',       'SEEN FROM "looker@lootlook.com"');
define('FILTER_VENMO',          'SEEN FROM "venmo@venmo.com"');
define('FILTER_TWITTER',        'SEEN FROM "postmaster.twitter.com"');
define('FILTER_ASSEMBLA',       'SEEN FROM "@alerts.assembla.com"');
define('FILTER_PINGDOM',        'SEEN FROM "alert@pingdom.com"');
define('FILTER_PHPARCH', 	'SEEN FROM "marcotabini@phparch.com"');
define('FILTER_THINKGEEK', 	'SEEN FROM "overlords@email.thinkgeek.com"');

$accounts = array();

if (!file_exists('config.php')) {
    echo "You must create a config.php. See: config.dist.php\n";
    exit(1);
}

require_once 'config.php';

$message = '';
$total = 0;
foreach ($accounts as $account) {
    $deletions = purgeAccount($account['dsn'], $account['username'], $account['password'], $account['criteria']);
    $total += $count = count($deletions);
    
    $message .= '[' . $count . '] ' . $account['username'] . "\n";
    foreach ($deletions as $subject) {
        $message .= ' - ' . $subject . "\n";
    }
    
    $message .= "\n";
}
$message = 'TOTAL: ' . $total . "\n\n" . $message;

echo $message;

function purgeAccount($dsn, $user, $password, $criteria) {
    $imap = imap_open($dsn, $user, $password);
    
    $deleted = array();
    foreach ($criteria as $c) {
        $deleted += searchAndPurge($c, $imap);
    }
    
    return $deleted;
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

    $deleted = array();
    if ($x) {
        $r = imap_fetch_overview($imap, implode($x, ','));
        foreach ($r as $email) {
            $deleted[] = $email->subject;
            imap_delete($imap, $email->uid, FT_UID);
        }
    }

    imap_expunge($imap);
    
    return $deleted;
}
