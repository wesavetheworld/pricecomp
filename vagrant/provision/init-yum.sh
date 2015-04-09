#!/bin/bash

cp -f /vagrant/vagrant/provision/files/epel.repo /etc/yum.repos.d/epel-base.repo
cp -f /vagrant/vagrant/provision/files/nginx.repo /etc/yum.repos.d/nginx.repo
cp -f /vagrant/vagrant/provision/files/mysql.repo /etc/yum.repos.d/mysql.repo

wget -q http://dl.iuscommunity.org/pub/ius/stable/CentOS/6/x86_64/epel-release-6-5.noarch.rpm
wget -q http://dl.iuscommunity.org/pub/ius/stable/CentOS/6/x86_64/ius-release-1.0-13.ius.centos6.noarch.rpm
rpm -Uvh epel-release*.rpm ius-release*.rpm --replacepkgs
rm epel-release-6-5.noarch.rpm
rm ius-release-1.0-13.ius.centos6.noarch.rpm