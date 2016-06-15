#!/bin/sh
#############################################################################
# Server
#
# Run build-in PHP server
#############################################################################

# Root directory
ROOT_DIR=$( cd "$( dirname $0 )"/../ && pwd )

# Declare host env debug
host="localhost:8000"
env="production"

while [ $# -gt 0 ] ; do
    case "$1" in
    -d) debug=1 ; shift ;;
    -e) shift ; env="$1" ; shift ;;
    -h) shift ; host="$1" ; shift ;;
    --) shift ;;
    -*) echo "Invalid option '$1'" ; exit 1 ;;
    esac
done

# Apply environment
[ ! -z "$env" ] && echo "Environment is $env"
export BLUZ_ENV=${env}

# Apply debug mode
if [ ! -z "$debug" ]
then
    echo "Debug mode is ON"
    export BLUZ_DEBUG=1
fi

# Start server on port 8000 for public directory
cd ${ROOT_DIR}/public/

# Run
php -S ${host} -t ./ routing.php
