ROOT_DIR=$( cd "$( dirname $0 )"/../ && pwd )
cd $ROOT_DIR/public/
echo "BLUZ_ENV set to "$1" in server.sh"
export BLUZ_ENV=$1
php -S localhost:8000 -t ./ routing.php