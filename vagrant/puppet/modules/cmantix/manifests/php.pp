class cmantix::ondreppa {
	# add ondrej/php5 ppa repo for php 5.4
	cmantix::addppa { 
		"ondrej/php5":
			apt_key => "E5267A6C",
			dist => 'precise'
	}
}

class cmantix::php {
	$phpPackages = [
		"php5-cli",
		"php5-common",
		"php5-intl",
		"php5-dev",
		"php5-curl",
		"php5-gd",
		"php5-xcache",
		"php5-mcrypt",
		"php5-xmlrpc",
		"php5-xsl",
		"php-pear"
	]
	
	package {
		$phpPackages :
			ensure => latest,
			notify => Service["php5-fpm"],
	}
	
	package {
		"php5-fpm" :
			ensure => latest,
			notify => Service['nginx'],
	}
	
	# set the proper fpm configuration
	file {
		'fpm-www-conf' :
			path => '/etc/php5/fpm/pool.d/www.conf',
			ensure => file,
			require => Package['php5-fpm'],
			source => 'puppet:///modules/cmantix/www.conf',
			notify => Service["php5-fpm"],
	}
	
	service {
		"php5-fpm" :
			ensure => running,
	}
	
}