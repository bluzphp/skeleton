Bluz, a lightweight PHP Framework
=================================
Easy to setup, easy to use. Example application

## Achievements

[![Build Status](https://secure.travis-ci.org/bluzphp/skeleton.png?branch=master)](https://travis-ci.org/bluzphp/skeleton)
[![Dependency Status](https://www.versioneye.com/php/bluzphp:skeleton/badge.png)](https://www.versioneye.com/php/bluzphp:skeleton)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bluzphp/skeleton/badges/quality-score.png?s=5751f605c8db43a14bef3626d2c33749614d188a)](https://scrutinizer-ci.com/g/bluzphp/skeleton/)
[![Coverage Status](https://coveralls.io/repos/bluzphp/skeleton/badge.png)](https://coveralls.io/r/bluzphp/skeleton)

[![Latest Stable Version](https://poser.pugx.org/bluzphp/skeleton/v/stable.png)](https://packagist.org/packages/bluzphp/skeleton)
[![Total Downloads](https://poser.pugx.org/bluzphp/skeleton/downloads.png)](https://packagist.org/packages/bluzphp/skeleton)

[![License](https://poser.pugx.org/bluzphp/skeleton/license.svg)](https://packagist.org/packages/bluzphp/skeleton)

## Installation

Bluz works with PHP 5.4 or later and MySQL 5.4 or later (please check [requirements](https://github.com/bluzphp/skeleton/wiki/Requirements))

### From composer

Download `composer.phar`, it's easy:
```
curl -s https://getcomposer.org/installer | php
```

Run `create-project` command (replace `%path%` ;):
```
php composer.phar create-project bluzphp/skeleton %path% --stability=dev
```

### From repository

Get Bluz skeleton source files from GitHub repository:
```
git clone git://github.com/bluzphp/skeleton.git %path%
```

Download `composer.phar` to the project folder:
```
cd %path%
curl -s https://getcomposer.org/installer | php
```

Install composer dependencies with the following command:
```
php composer.phar install
```

### With PhpStorm

For install you need any web-server (for Windows) and PhpStorm. dows) и PhpStorm.

Create project in PhpStorm:

1. File -> New project;
2. Set the project name and location;
3. In a Project type field choose Composer project;
4. Check that radiobutton is set opposite "Download composer.phar from getcomposer.org", type in a search field "bluzphp/skeleton", select this package in Available packages window and click OK.
5. After that file composer.phar and all dependencies will be loaded. 

### Last step

Restore database structure from `structure.ddl` file (use InnoDB as the Default MySQL Storage Engine for avoid "Error Code: 1071"!).
Restore default database data from `dump.sql`

Edit your own configuration file `/path/to/application/configs/app.dev.php` (configuration for development environment)

Run internal PHP web-server with simple console tool (for Linux):

```
/path/to/bin/server.sh -e dev
```

Or create symlink to Apache document root (required FollowSymlinks option):
* Linux
```
ln -s /path/to/public /path/to/web
```
* Win
```
mklink /D /path/to/web path/to/public
```


## Usage

Controller:

```php
<?php
return
/**
 * @privilege View-User-Profile
 * @cache 5 minutes
 * @param integer $id
 * @return \closure
 */
function($id) use ($view) {
    /**
     * @var Application $this
     * @var View $view
     */
     $view->user = Users\Table::findRow($id);
};
```

View:

```php
<h2><?=$user->login?></h2>
```

Model:

```php
<?php
namespace Application\Users;
class Table extends \Bluz\Db\Table
{
    protected $table = 'users';
    protected $primary = array('id');
}
```

```php
<?php
namespace Application\Users;
/**
 * @property integer $id
 * @property string $login
 */
class Row extends \Bluz\Db\Row {

}
```

## Documentation

* [Framework wiki](https://github.com/bluzphp/framework/wiki)
* [Skeleton wiki](https://github.com/bluzphp/skeleton/wiki)

## License

Read [MIT LICENSE](https://raw.github.com/bluzphp/skeleton/master/LICENSE.md) file

## Vendors

* [Bluz](https://github.com/bluzphp/framework/)
* [jQuery](https://github.com/jquery/jquery/)
* [RequireJS](http://requirejs.org/)
* [Twitter Bootstrap](http://getbootstrap.com/)
* [Font Awesome](http://fontawesome.io/)
