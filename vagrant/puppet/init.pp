import "nodes.pp"

Exec {
    path => "/usr/bin:/usr/sbin:/bin"
}
     
group { 'puppet':
    ensure => present,
}
