#!/bin/sh
#############################################################################
# Installation
#
# Small installation script
#
#############################################################################

# Server data
echo "Permissions for ./data/"

chmod a+w ./data/cache/     && echo " ./cache/"
chmod a+w ./data/logs/      && echo " ./logs/"
chmod a+w ./data/sessions/  && echo " ./sessions/"
chmod a+w ./data/uploads/   && echo " ./uploads/"
