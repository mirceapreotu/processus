#!/bin/bash
#-----------------------------------------------------------
#
# Purpose:	Converts a bas_template project to a specific
#			"new" initial project  
#
#-----------------------------------------------------------

echo "Setup project"
echo "========================================="
echo -n "Project key (bas_template): "
read PROJECT_KEY
echo

PROJECT_DOMAINKEY=`echo $PROJECT_KEY|sed -r 's/_/-/g'`

echo "Configuring default settings:"
echo "  DBNAME: $PROJECT_KEY"
echo "  DBUSER: $PROJECT_KEY"
echo "  DBPASS: $PROJECT_KEY"
echo "  DOMAIN: $PROJECT_DOMAINKEY.local.meetidaaa.com"
echo 

sed --in-place -r -e "s/@@PROJECT_KEY@@/$PROJECT_KEY/g" build.xml
sed --in-place -r -e "s/@@PROJECT_KEY@@/$PROJECT_KEY/g" ../etc/samples/build.properties
sed --in-place -r -e "s/@@PROJECT_DOMAINKEY@@/$PROJECT_DOMAINKEY/g" ../etc/samples/build.properties

echo "Next steps:"
echo "- cd ../.. && mv bas_template $PROJECT_KEY && cd $PROJECT_KEY/build"
echo "- phing setup"
echo "- sudo cp ../etc/vhost.conf /etc/apache2/sites-available/$PROJECT_DOMAINKEY"
echo "- sudo a2ensite $PROJECT_DOMAINKEY && sudo apache2ctl restart"
echo "- open in browser: http://$PROJECT_DOMAINKEY.local.meetidaaa.com"
echo
# eof
