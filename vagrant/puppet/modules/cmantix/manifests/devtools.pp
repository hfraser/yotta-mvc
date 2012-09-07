class cmantix::devtools {
	$phpPackages = [
		"php5-xdebug",
		"php5-xhprof",
	]
	
	package {
		$phpPackages :
			ensure => "installed",
			notify => Service["php5-fpm"],
	}

	# install xdebug
	# add xdebug config
	# install xhprof
	# install xcache interface
	# add pear phing and necessary build tools
	# get and install cmantix check style
}