import 'apt.pp', 'php.pp', 'nginx.pp', 'devtools.pp'

# Class: cmantix
#
# This module manages cmantixNginx
#
# Parameters:
#
# Actions:
#
# Requires:
#
# Sample Usage:
#
# [Remember: No empty lines between comments and class definition]
class cmantix {
}

class cmantix::bases {
	exec {
		'apt-get update' :
			command => 'apt-get update',
	}
	
	$sysPackages = [
		"build-essential", 
		"curl",
		"screen"
	]
	
	package {
		$sysPackages :
			ensure => "installed",
			require => Exec['apt-get update'],
	}
	
}
