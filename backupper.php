<?php
require_once('include/Ping.php'); //Put in Ping class to ping the endpoint or anything else.

/*
The following must be configured before running the script.
*/
$backuppath = ''; //Physical path to the backup folder. Without trailing slash. REQUIRED.

$pingSg1 = new Ping('s3-ap-southeast-1.amazonaws.com');
$latencySg1 = $pingSg1->ping();
$pingSg2 = new Ping('s3-ap-southeast-2.amazonaws.com');
$latencySg2 = $pingSg2->ping();
if ($latencySg1 !== false) { //If Singapore 1 is working...
    define('awsEndpoint', 's3-ap-southeast-1.amazonaws.com'); //Set to Singapore 1
    unset($pingSg1);
    unset($pingSg2);
} elseif {
    define('awsEndpoint', 's3-ap-southeast-2.amazonaws.com'); //Set to Singapore 2
    unset($pingSg1);
    unset($pingSg2);
} else {
    unset($pingSg1);
    unset($pingSg2);
    die("No endpoints are working!!!");
}
define('awsAccessKey', ''); // required
define('awsSecretKey', ''); // required
define('awsBucket', ''); // required

// Will this script run "weekly", "daily", or "hourly"?
define('schedule','weekly'); // required

require_once('include/backup.inc.php');

// You may place any number of .php files in the backups folder. They will be executed here.
foreach ($backuppath . "/*.php") as $filename)
{
    include $filename;
}

/*

backupDBs - hostname, username, password, prefix, [post backup query]

  hostname = hostname of your MySQL server
  username = username to access your MySQL server (make sure the user has SELECT privliges)
  password = your password
  prefix = backup filenames will contain this prefix, this prevents overwriting other backups when you have more than one server backing up at once.
  post backup query = Optional: Any SQL statement you want to execute after the backups are completed. For example: PURGE BINARY LOGS BEFORE NOW() - INTERVAL 14 DAY;

*/
backupDBs('localhost','username','password','my-database-backup','');

/*

backupFiles - array of paths, [prefix]
  
  array of paths = An array of one or more file paths that you want backed up
  prefix = Optional: backup filenames will contain this prefix, this prevents overwriting other backups when you have more than one server backing up at once.

*/
//backupFiles(array('/home/myuser', '/etc'),'me');
//backupFiles(array('/var/www'),'web files');
?>
