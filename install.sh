#!/bin/sh
#############################################################################
# Installation
#
# Small installation script
#
#############################################################################

# Server data
echo "Set permissions for ./data/"

chmod a+w ./data/cache/     && echo " ./cache/"
chmod a+w ./data/logs/      && echo " ./logs/"
chmod a+w ./data/sessions/  && echo " ./sessions/"
chmod a+w ./data/uploads/   && echo " ./uploads/"

echo "Copy configuration"
cp ./application/configs/app.dev.sample.php ./application/configs/app.dev.php && echo " ./configs/app.dev.php"
cp ./public/.htaccess.dev.sample ./public/.htaccess                           && echo " ./public/.htaccess"
