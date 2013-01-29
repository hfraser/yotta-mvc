#!/usr/bin/php
<?php
// Used to clear caches

// validate that we are running from a shell command
if (!defined('STDIN')) {
	die("This script is made to be ran only from the shell");
}

require_once('bootstrap.php');

if (is_dir(CM_CACHE) && is_writable(CM_CACHE)) {
	echo("[INFO] Clearing cache \n");
	exec('rm -rf ' . CM_CACHE . '*');
} else {
	echo("[ERROR] Directory is not writable :: " . CM_CACHE . "\n");
}