#!/bin/bash
#-----------------------------------------------------------
#
# Purpose:	Just run this script to install the	required
#			ubuntu/pear packages for php development. 
#
# Tested on Ubuntu 10.04,Ubuntu 10.10
#-----------------------------------------------------------

    echo "Installing basic packages for development."
    echo "Notes:"
    echo "- if asked for a password, provide your system user password"
    echo "- if asked for a mysql server password, enter 'root'"
    echo -n "Press ENTER to continue, CTRL-c to cancel:"
    read DUMMY

    sudo echo

# check repositories add / activate "partner" repository

    echo "Checking / enabling 'partner' Ubuntu repository"

    grep -q -r '^deb .* partner' /etc/apt/sources.list         ||
    ( echo "ERROR: Ubuntu 'partner' repository not enabled."
      echo "Adding 'deb http://archive.canonical.com/ lucid partner' ... "
      sudo sed -i -e "s|^# deb .* partner$|deb http://archive.canonical.com/ lucid partner|" /etc/apt/sources.list
      sudo apt-get update
    )

# basic PHP (to be extended!):

	sudo apt-get --assume-yes install	\
	 	libapache2-mod-php5				\
	 	php-pear						\
	 	php5-mysql						\
	 	php5-sqlite						\
	 	php5-xdebug 					\
	 	php5-xcache						\
		php5-suhosin                    \
		php5-gd                         \
		php5-mcrypt                     \
		php5-xsl                        \
		php5-curl                       \
		php5-memcache

# tools

    sudo apt-get --assume-yes install   \
        flip

# update php memory limit

    echo "Raising memory limit in php.ini files"
    sudo sed -i -r 's/^ *memory_limit *= *.*/memory_limit = 512M/' /etc/php5/apache2/php.ini
    sudo sed -i -r 's/^ *memory_limit *= *.*/memory_limit = 512M/' /etc/php5/cli/php.ini

# typical 3rd party systems (db,cache ..):

    echo "Installing MySQL server, Memcache & Beanstalk daemons"

	sudo apt-get --assume-yes install	\
		mysql-server					\
		beanstalkd                      \
		memcached

    # activate beanstalkd:
    sudo echo "START=yes" >> /etc/default/beanstalkd
    sudo /etc/init.d/beanstalkd restart

# needed for pecl installs:

    echo "Prepare build system for pecl installs"

	sudo apt-get --assume-yes install	\
		build-essential					\
		autoconf						\
		php5-dev

# development environment:

    echo "Setup developer tools"

	sudo apt-get --assume-yes install   \
		sun-java6-jdk				    \
		subversion					    \
		git-core                        \
		vim							    \
		gettext                         \
		ssh                             \
		rsync                           \
		beanstalkd                      \
		imagemagick                     \
		kcachegrind                     \
		lftp                            &&
	sudo update-java-alternatives -s java-6-sun

    sudo echo "START=yes" >> /etc/default/beanstalkd
    sudo /etc/init.d/beanstalkd start

# pear stuff:

    echo "Install PEAR extensions for development"

    # not stable, yet:
    # sudo pear install --alldeps phpunit/PHP_CodeBrowser
    # maybe add later: sudo pear install phpunit/phpdcd-beta

    sudo pear channel-update pear.php.net
    sudo pear upgrade
    sudo pear channel-discover pear.phing.info
    sudo pear install phing/phing
    sudo pear install pear/PhpDocumentor
    sudo pear channel-discover pear.pdepend.org
    sudo pear install pdepend/PHP_Depend-beta
    sudo pear install PHP_CodeSniffer
    sudo pear channel-discover pear.phpunit.de
    sudo pear channel-discover pear.symfony-project.com
    sudo pear install phpunit/PHPUnit
    sudo pear channel-discover pear.phpmd.org
    sudo pear install --alldeps phpmd/PHP_PMD-alpha
    sudo pear channel-discover components.ez.no
    sudo pear install phpunit/phpcpd
    sudo pear install --alldeps PHP_CompatInfo
    sudo pear channel-discover pear.phpunit.de
    sudo pear channel-discover components.ez.no
    sudo pear install phpunit/phpdcd-beta


    # update local filesystem search cache (see below: locate)
    sudo updatedb

# docbook:

    echo "Install DocBook tools"

	sudo apt-get --assume-yes install	\
		xsltproc						\
		docbook-xsl						\
		fop								\
		docbook-xsl-doc-pdf				\
		pandoc

# install php_codesniffer meetidaaa standard
# /usr/share/php/PHP/CodeSniffer/Standards/

    #echo "Install PECL Phar extension"
    #sudo pear install pecl/phar                         &&
    #sudo echo "extension=phar.so" > /etc/php5/conf.d/phar.ini

# Selenium RC:
# * Download "Selenium RC" from http://seleniumhq.org/download/
# * Extract contents to /opt/selenium
# * run via "java -jar /opt/selenium/selenium-server-1.0.3/selenium-server.jar"

XDEBUG=`locate xdebug.so|grep php|tail -1`

echo "Enabling xdebug PHP debugger"
echo "zend_extension=$XDEBUG"            > /tmp/xdebug.ini
echo "[XDebug]"                         >> /tmp/xdebug.ini
echo "xdebug.remote_enable=1"           >> /tmp/xdebug.ini
echo "xdebug.remote_host=localhost"     >> /tmp/xdebug.ini
echo "xdebug.remote_port=9000"          >> /tmp/xdebug.ini
echo 'xdebug.remote_handler="dbgp"'     >> /tmp/xdebug.ini
echo "xdebug.profiler_enable_trigger=1" >> /tmp/xdebug.ini
echo "xdebug.profiler_output_dir=/tmp/" >> /tmp/xdebug.ini
sudo mv /tmp/xdebug.ini /etc/php5/conf.d/xdebug.ini 

# enable Apache extensions:

    echo "Enabling Apache mod_rewrite & restarting Apache"

    sudo a2enmod rewrite
    sudo apache2ctl graceful

# fix for broken phing package (until 2.4.5 arrives):

   # find correct logwriter class file:
   echo "Workarround for LogWriter and PDOSQL"

   LOGWRITER=`locate LogWriter|grep phing|tail -1`

   # find file to path:
   PDOSQL=`locate PDOSQLExecFormatterElement.php|tail -1`

   # fix db task include:
   sudo sed -i -e "s#<?php.*#<?php require_once '$LOGWRITER';#" $PDOSQL

# enable xdebug profiling trigger:

    XDEBUG_INI=/etc/php5/apache2/conf.d/xdebug.ini
    XDEBUG=`locate xdebug.so|tail -1`
    echo  >$XDEBUG_INI "zend_extension=$XDEBUG"
    echo >>$XDEBUG_INI "[XDebug]"
    echo >>$XDEBUG_INI "xdebug.remote_enable=1"
    echo >>$XDEBUG_INI "xdebug.remote_host=localhost"
    echo >>$XDEBUG_INI "xdebug.remote_port=9000"
    echo >>$XDEBUG_INI 'xdebug.remote_handler="dbgp"'
    echo >>$XDEBUG_INI ""
    echo >>$XDEBUG_INI "xdebug.profiler_enable_trigger=1" # XDEBUG_PROFILE
    echo >>$XDEBUG_INI ";xdebug.profiler_output_dir=/tmp/"

# check for innodb file as table ..
    grep -q -r '^innodb_file_per_table' /etc/mysql/my.cnf         ||
    echo "HINT: Add innodb_file_per_table in [mysqld] section of /etc/mysql/my.cnf!"

echo "SUCCESS: SETUP FINISHED."

# eof
