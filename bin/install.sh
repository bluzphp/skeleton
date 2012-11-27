#!/bin/sh
#############################################################################
# Installation
#
# Small installation script
#
#############################################################################

ROOT_DIR="$(realpath "$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"/../)"

# Server data
echo "Set permissions for ./data/"

chmod a+w $ROOT_DIR/data/cache/     && echo " ./cache/"
chmod a+w $ROOT_DIR/data/logs/      && echo " ./logs/"
chmod a+w $ROOT_DIR/data/sessions/  && echo " ./sessions/"
chmod a+w $ROOT_DIR/data/uploads/   && echo " ./uploads/"

echo "Copy configuration"
cp $ROOT_DIR/application/configs/app.dev.sample.php $ROOT_DIR/application/configs/app.dev.php && echo " ./configs/app.dev.php"
cp $ROOT_DIR/public/.htaccess.dev.sample $ROOT_DIR/public/.htaccess                           && echo " ./public/.htaccess"