class cmantix::nginx {
	package {
		'nginx' :
			ensure => present,
	}
	
	service {
		'nginx':
			ensure => running,
			enable => true,
			hasrestart => true,
			hasstatus   => true,
			require => Package["nginx"],
			#require => File['/etc/nginx/nginx.conf'],
			#restart => '/etc/init.d/nginx restart'
	}
	
	# set the proper configuration
	file {
		'cmantix-nginx' :
			path => '/etc/nginx/sites-available/cmantix',
			ensure => file,
			require => Package['nginx'],
			notify => Service['nginx'],
			source => 'puppet:///modules/cmantix/cmantix.nginx'
	}
	
	#disable default configuration
	file {
		'default-nginx-disable' :
			path => '/etc/nginx/sites-enabled/default',
			ensure => absent,
			notify => Service['nginx'],
			require => Package['nginx']
	}
	
	# set project conf
	file {
		'cmantix-nginx-enable' :
			path => '/etc/nginx/sites-enabled/cmantix',
			target => '/etc/nginx/sites-available/cmantix',
			ensure => link,
			notify => Service['nginx'],
			require => [File['cmantix-nginx'], File['default-nginx-disable']]
	}
}

class cmantix::removeApache {
	$apachePackages = [
		"apache2-mpm-prefork",
		"apache2-utils",
		"apache2.2-bin",
		"apache2.2-common",
		"libapache2-mod-php5filter",
	]
	
	package {
		$apachePackages :
			ensure => absent,
	}
}