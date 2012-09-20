# c-mantix web node
stage { [preinstall, pre, post]: }
Stage[preinstall] -> Stage[pre] -> Stage[main] -> Stage[post]


class { apthupdate: stage => pre }
class { cmantix::nginx: stage => post }
class { cmantix::removeApache: stage => post }

class apthupdate{
	exec {"apt-update":
		command     => "/usr/bin/apt-get update",
		logoutput=>true,
	}
}

# c-mantix web node
node 'cmantix.dev.local' {
	#stage { [preinstall, pre, post]: }
	#Stage[preinstall] -> Stage[pre] -> Stage[main] -> Stage[post]
	
	# set MOTD To identify machine!.
	file {
		'/etc/motd' :
			content => "Welcome to your C-Mantix ECM Development Machine.\n"
	}
	# add php 5.4 repo
	#include cmantix::ondreppa
	#include apthupdate
	
	# install bases
	include cmantix::bases
	
	# install nginx
	include cmantix::nginx
	
	# install base php
	include cmantix::php
	
	# remove latent apache
	include cmantix::removeApache
}
