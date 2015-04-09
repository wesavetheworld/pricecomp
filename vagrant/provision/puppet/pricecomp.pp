node default {

	###################################
	# Disable SELINUX.
	###################################
	
	exec { "echo 0 > /selinux/enforce":
		path	=> '/bin',
		onlyif	=> '/usr/bin/test -f /selinux/enforce',
	}
	

	###################################
	# Apache changes.
	###################################
		
	exec { "sed -i 's/^Listen 80/#Listen 80/' /etc/httpd/conf/httpd.conf":
		path	=> '/bin',
		notify	=>	Service['httpd'],
		require	=> [
			Package['httpd'],
		],
	}
	
	exec { "sed -i 's/^#EnableSendfile off/EnableSendfile off/' /etc/httpd/conf/httpd.conf":
		path	=> '/bin',
		notify	=>	Service['httpd'],
	}
	
	exec { "sed -i 's/^User apache/User rmnuk-vagrant/' /etc/httpd/conf/httpd.conf":
		path	=> '/bin',
		notify	=>	Service['httpd'],
	}

	###################################
	# Nginx changes.
	###################################
	exec { "sed -i 's/ sendfile/#sendfile/' /etc/nginx/nginx.conf":
		path	=> '/bin',
		notify	=>	Service['nginx'],
	}
	
	
	###################################
	# Ensure packages installed.	 
	###################################

	package { 'ntp':
		ensure	=> installed,
	}
	
	package { 'mysql-community-server':
		ensure	=> installed,
	}
	
	package { 'memcached':
		ensure	=> installed,
	}
	
	package { 'nginx':
		ensure	=> 'installed',
	}
	
	package { 'httpd':
		ensure	=> installed,
	}
	
	package { 'php55u':
		ensure	=> installed,
	}
	
	package { 'php55u-mysqlnd':
		ensure	=> installed,
		require	=> [
			Package['php55u'],
			Package['mysql-community-server'],
		],
	}
	
	package { 'php55u-pear':
		ensure	=> installed,
		require	=> Package['php55u'],
	}
	
	package { 'php55u-pecl-memcache':
		ensure	=> installed,
		require	=> Package['php55u'],
	}
	
	package { 'php55u-mbstring':
		ensure	=> installed,
		require	=> Package['php55u'],
	}
	
	package { 'php55u-mcrypt':
		ensure	=> installed,
		require	=> Package['php55u'],
	}	
	
	package { 'php55u-pecl-xdebug':
		ensure	=> installed,
		require	=> Package['php55u'],
	}

  package { 'php55u-devel':
    ensure	=> installed,
    require	=> Package['php55u'],
  }
	
	package { 'librabbitmq':
		ensure	=> installed,
	}

	package { 'php-pecl-amqp':
		ensure => installed,
		source => "http://rpms.famillecollet.com/enterprise/6/php55/x86_64/php-pecl-amqp-1.4.0-1.el6.remi.5.5.x86_64.rpm",
		provider => rpm,
		require	=> [
			Package['php55u'],
			Package['librabbitmq'],
		]
	}

	package { 'php55u-opcache':
		ensure	=> installed,
		require	=> Package['php55u'],
	}

	package { 'php55u-pecl-jsonc':
		ensure	=> installed,
		require	=> Package['php55u'],
	}

  exec { 'printf "no\n" | sudo pecl install -f mongo; echo $;':
    path	=> '/usr/bin',
    notify =>	Service['httpd']
  }
	
	
	###################################
	# Ensure services running (or not).	 
	###################################		
	
	service { 'ntpd':
		ensure	=> running,
		enable	=> true,
		require	=> Package['ntp'],
	}

	service { 'iptables':
		ensure	=> stopped,
		enable	=> false,
	}
	
	service { 'mysqld':
		ensure	=> running,
		enable	=> true,
		require	=> Package['mysql-community-server'],
	}
	
	service { 'memcached':
		ensure	=> stopped,
		enable	=> false,
		require	=> Package['memcached'],
	}
	
	service { 'httpd':
		ensure	=> running,
		enable	=> true,
		require	=> Package['httpd'],
	}
	
	service { 'nginx':
		ensure	=> running,
		enable	=> true,
		require	=> Package['nginx'],
	}

  ###################################
  # PHP changes.
  ###################################
  exec { 'grep -q "extension=mongo.so" /etc/php.ini || echo "extension=mongo.so" >> /etc/php.ini':
    path	=> '/bin',
    notify	=>	Service['httpd'],
  }
	
	###################################
	# Add config and other files.
	###################################
	
	file { '/var/www/html/phpinfo.php':
		replace => true,
		content	=> '<?php phpinfo(); ?>',
		mode	=> 0755,
		require	=> Package['php55u'],
	}
	
	file { '/etc/selinux/config':
		replace	=> true,
		content	=> "SELINUX=disabled\nSELINUXTYPE=targeted",
	}
	
	file { '/etc/httpd/conf.d/vhosts.conf':
		replace => true,
		source  => 'file:///vagrant/vagrant/provision/files/pricecomp/httpd.vhost',
		notify	=> Service['httpd'],
		require	=> Package['httpd'],
	}
	
	file { '/etc/nginx/conf.d/vhosts.conf':
		replace => true,
		source  => 'file:///vagrant/vagrant/provision/files/pricecomp/nginx.vhost',
		notify	=>	Service['nginx'],
	}
	
	file { '/etc/php.d/xdebug.ini':
		replace => true,
		source  => 'file:///vagrant/vagrant/provision/files/pricecomp/xdebug.ini',
		notify	=>	Service['httpd'],
	}
	

	###################################
	# User / Groups.
	###################################	
	
	user { 'rmnuk-vagrant':
		name	=> 'rmnuk-vagrant',
		groups	=> ['vboxsf','admin'],
	}		
	
}