#!/bin/sh

# Upgrade The Base Packages
apt-get update
apt-get upgrade -y

# Add A Few PPAs To Stay Current
apt-get install -y software-properties-common
apt-add-repository ppa:nginx/stable -y
apt-add-repository ppa:rwky/redis -y
apt-add-repository ppa:chris-lea/node.js -y
apt-add-repository ppa:ondrej/php5-5.6 -y

# Update Package Lists
apt-get update

# Base Packages
apt-get install -y --force-yes build-essential curl fail2ban gcc git libmcrypt4 libpcre3-dev \
make python-pip supervisor ufw unattended-upgrades unzip whois zsh

# Install Python Httpie
pip install httpie

# Disable Password Authentication Over SSH
sed -i "/PasswordAuthentication yes/d" /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "PasswordAuthentication no" | sudo tee -a /etc/ssh/sshd_config

# Restart SSH
ssh-keygen -A
service ssh restart

# Set The Hostname
echo "vc-price-comparison" > /etc/hostname
sed -i 's/127\.0\.0\.1.*localhost/127.0.0.1	vc-price-comparison localhost/' /etc/hosts
hostname vc-price-comparison

# Set The Timezone
ln -sf /usr/share/zoneinfo/Europe/London /etc/localtime

# Create The Root SSH Directory If Necessary
if [ ! -d /root/.ssh ]
then
	mkdir -p /root/.ssh
	touch /root/.ssh/authorized_keys
fi

# Setup User
useradd pricecomparison
mkdir -p /home/pricecomparison/.ssh
mkdir -p /home/pricecomparison/.pricecomparison
adduser pricecomparison sudo

# Setup Bash For Forge User

chsh -s /bin/bash pricecomparison
cp /root/.profile /home/pricecomparison/.profile
cp /root/.bashrc /home/pricecomparison/.bashrc

# Build Formatted Keys
cat > /root/.ssh/authorized_keys << EOF
# pricecomparison
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDHbXANxbAIM/hMVhwxM/MkE6SOt0eaH81Ggck5PHDJb7Xd61/iBX+qT2pr0f4Xg4IYA24pYlnnaawYrsZXYx329axvxhyA8hHUefLJRTBQt2ZaVCOm8W5LXHLP1d78UW0SjKeV0ypUTfbSDsZs72GFXJ0dTn4DcdcBb7bq6YiMHQ== phpseclib-generated-key
EOF

cp /root/.ssh/authorized_keys /home/pricecomparison/.ssh/authorized_keys

# Create The Server SSH Key
ssh-keygen -f /home/pricecomparison/.ssh/id_rsa -t rsa -N ''

# Copy Github And Bitbucket Public Keys Into Known Hosts File
ssh-keyscan -H github.com >> /home/pricecomparison/.ssh/known_hosts
ssh-keyscan -H bitbucket.org >> /home/pricecomparison/.ssh/known_hosts

# Configure Git Settings
git config --global user.name "Mark Hague"
git config --global user.email "mhague@rmn.com"

# Add The Reconnect Script Into Forge Directory
cat > /home/pricecomparison/.pricecomparison/reconnect << EOF
#!/usr/bin/env bash

echo "# pricecomparison" | tee -a /home/pricecomparison/.ssh/authorized_keys > /dev/null
echo \$1 | tee -a /home/pricecomparison/.ssh/authorized_keys > /dev/null

echo "# pricecomparison" | tee -a /root/.ssh/authorized_keys > /dev/null
echo \$1 | tee -a /root/.ssh/authorized_keys > /dev/null

echo "Keys Added!"
EOF

# Setup Site Directory Permissions
chown -R pricecomparison:pricecomparison /home/pricecomparison
chmod -R 755 /home/pricecomparison
chmod 700 /home/pricecomparison/.ssh/id_rsa

# Setup Unattended Security Upgrades
cat > /etc/apt/apt.conf.d/50unattended-upgrades << EOF
Unattended-Upgrade::Allowed-Origins {
	"Ubuntu trusty-security";
};
Unattended-Upgrade::Package-Blacklist {
	//
};
EOF

cat > /etc/apt/apt.conf.d/10periodic << EOF
APT::Periodic::Update-Package-Lists "1";
APT::Periodic::Download-Upgradeable-Packages "1";
APT::Periodic::AutocleanInterval "7";
APT::Periodic::Unattended-Upgrade "1";
EOF

# Setup UFW Firewall
ufw allow 22
ufw allow 80
ufw allow 443
ufw --force enable

# Allow FPM Restart
echo "pricecomparison ALL=NOPASSWD: /usr/sbin/service php5-fpm reload" > /etc/sudoers.d/php5-fpm

# Install Base PHP Packages
apt-get install -y --force-yes php5-cli php5-dev php-pear \
php5-mysqlnd php5-pgsql php5-sqlite \
php5-apcu php5-json php5-curl php5-dev php5-gd \
php5-gmp php5-imap php5-mcrypt php5-memcached php5-xdebug

# Make The MCrypt Extension Available
ln -s /etc/php5/conf.d/mcrypt.ini /etc/php5/mods-available
sudo php5enmod mcrypt
sudo service nginx restart

# Install Composer Package Manager
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

# Misc. PHP CLI Configuration
sudo sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php5/cli/php.ini
sudo sed -i "s/display_errors = .*/display_errors = On/" /etc/php5/cli/php.ini
sudo sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php5/cli/php.ini
sudo sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php5/cli/php.ini

# Install Nginx & PHP-FPM
apt-get install -y --force-yes nginx php5-fpm

# Disable The Default Nginx Site
rm /etc/nginx/sites-enabled/default
rm /etc/nginx/sites-available/default
service nginx restart

# Tweak Some PHP-FPM Settings
sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php5/fpm/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php5/fpm/php.ini
sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php5/fpm/php.ini
sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php5/fpm/php.ini
sed -i "s/;date.timezone.*/date.timezone = UTC/" /etc/php5/fpm/php.ini
sed -i "s/\;session.save_path = .*/session.save_path = \"\/var\/lib\/php5\/sessions\"/" /etc/php5/fpm/php.ini

# Configure Nginx & PHP-FPM To Run As pricecomparison
sed -i "s/user www-data;/user pricecomparison;/" /etc/nginx/nginx.conf
sed -i "s/# server_names_hash_bucket_size.*/server_names_hash_bucket_size 64;/" /etc/nginx/nginx.conf

sed -i "s/^user = www-data/user = pricecomparison/" /etc/php5/fpm/pool.d/www.conf
sed -i "s/^group = www-data/group = pricecomparison/" /etc/php5/fpm/pool.d/www.conf

sed -i "s/;listen\.owner.*/listen.owner = pricecomparison/" /etc/php5/fpm/pool.d/www.conf
sed -i "s/;listen\.group.*/listen.group = pricecomparison/" /etc/php5/fpm/pool.d/www.conf
sed -i "s/;listen\.mode.*/listen.mode = 0666/" /etc/php5/fpm/pool.d/www.conf

# Configure A Few More Server Things
sed -i "s/;request_terminate_timeout.*/request_terminate_timeout = 60/" /etc/php5/fpm/pool.d/www.conf
sed -i "s/worker_processes.*/worker_processes auto;/" /etc/nginx/nginx.conf
sed -i "s/# multi_accept.*/multi_accept on;/" /etc/nginx/nginx.conf

# Install A Catch All Server
cat > /etc/nginx/sites-available/catch-all << EOF
server {
	return 404;
}
EOF

ln -s /etc/nginx/sites-available/catch-all /etc/nginx/sites-enabled/catch-all

# Restart Nginx & PHP-FPM Services
service php5-fpm restart
service nginx restart

# Add pricecomparison User To www-data Group
usermod -a -G www-data pricecomparison
id pricecomparison
groups pricecomparison


# Set The Automated Root Password
debconf-set-selections <<< "mysql-server mysql-server/root_password password Pr1CeC0mPar1S0n"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password Pr1CeC0mPar1S0n"

# Install MySQL
apt-get install -y --force-yes mysql-server

# Configure Access Permissions For Root & Forge Users
sed -i '/^bind-address/s/bind-address.*=.*/bind-address = */' /etc/mysql/my.cnf
mysql --user="root" --password="Pr1CeC0mPar1S0n" -e "GRANT ALL ON *.* TO root@'127.0.0.1' IDENTIFIED BY 'Pr1CeC0mPar1S0n';"
mysql --user="root" --password="Pr1CeC0mPar1S0n" -e "GRANT ALL ON *.* TO root@'%' IDENTIFIED BY 'Pr1CeC0mPar1S0n';"
service mysql restart

mysql --user="root" --password="Pr1CeC0mPar1S0n" -e "CREATE USER 'pricecomparison'@'127.0.0.1' IDENTIFIED BY 'Pr1CeC0mPar1S0n';"
mysql --user="root" --password="Pr1CeC0mPar1S0n" -e "CREATE USER 'pricecomparison'@'localhost' IDENTIFIED BY 'Pr1CeC0mPar1S0n';"
mysql --user="root" --password="Pr1CeC0mPar1S0n" -e "GRANT ALL ON *.* TO 'pricecomparison'@'127.0.0.1' IDENTIFIED BY 'Pr1CeC0mPar1S0n' WITH GRANT OPTION;"
mysql --user="root" --password="Pr1CeC0mPar1S0n" -e "GRANT ALL ON *.* TO 'pricecomparison'@'localhost' IDENTIFIED BY 'Pr1CeC0mPar1S0n' WITH GRANT OPTION;"
mysql --user="root" --password="Pr1CeC0mPar1S0n" -e "FLUSH PRIVILEGES;"

# Create The Initial Database If Specified
mysql --user="root" --password="Pr1CeC0mPar1S0n" -e "CREATE DATABASE price_comparison;"

# Install & Configure Redis Server
apt-get install -y redis-server
sed -i 's/bind 127.0.0.1/bind 0.0.0.0/' /etc/redis/redis.conf
service redis-server restart

# Install & Configure Memcached
apt-get install -y memcached
sed -i 's/-l 127.0.0.1/-l 0.0.0.0/' /etc/memcached.conf
service memcached restart

# Install & Configure Beanstalk
apt-get install -y beanstalkd
sed -i "s/BEANSTALKD_LISTEN_ADDR.*/BEANSTALKD_LISTEN_ADDR=0.0.0.0/" /etc/default/beanstalkd
sed -i "s/#START=yes/START=yes/" /etc/default/beanstalkd
/etc/init.d/beanstalkd start

cat > /etc/nginx/sites-available/default << EOF
server {
    listen 80 default_server;
    server_name default;
    root /home/pricecomparison/pricecomparison/public;

    client_max_body_size 4G;
    client_body_buffer_size 1024k;

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log /var/log/nginx/pricecomparison-access.log;
    error_log  /var/log/nginx/pricecomparison-error.log error;

    error_page 404 /index.php;

    location ~ \.php\$ {
        try_files $uri /index.php =404;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOF

ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

cat > /home/pricecomparison/pricecomparison/.env.php << EOF
<?php
return array(
    'DB_HOST'                       => '127.0.0.1',
    'DB_DATABASE'                   => 'price_comparison',
    'DB_USER'                       => 'pricecomparison',
    'DB_PASS'                       => 'Pr1CeC0mPar1S0n',
    'APP_DEBUG'                     => true,
    'APP_URL'                       => 'http://pricecomp.wsm.local',
    'REDIS_HOST'                    => '127.0.0.1'
);
EOF

service nginx restart
service php5-fpm restart