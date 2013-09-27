#!/bin/sh
#############################################################################
# Installation
#
# Small installation script for "post-install-cmd" event
#############################################################################

# Root directory
ROOT_DIR=$( cd "$( dirname $0 )"/../ && pwd )

# Server data
echo "Set permissions"

chmod a+w $ROOT_DIR/data/cache/     && echo " ./data/cache/"
chmod a+w $ROOT_DIR/data/logs/      && echo " ./data/logs/"
chmod a+w $ROOT_DIR/data/sessions/  && echo " ./data/sessions/"
chmod a+w $ROOT_DIR/data/uploads/   && echo " ./data/uploads/"
chmod a+w $ROOT_DIR/public/uploads/ && echo " ./public/uploads/"

echo "Copy configuration"
cp $ROOT_DIR/application/configs/app.dev.sample.php $ROOT_DIR/application/configs/app.dev.php && echo " ./configs/app.dev.php"
cp $ROOT_DIR/application/configs/app.testing.sample.php $ROOT_DIR/application/configs/app.testing.php && echo " ./configs/app.testing.php"
cp $ROOT_DIR/public/.htaccess.dev.sample $ROOT_DIR/public/.htaccess                           && echo " ./public/.htaccess"
