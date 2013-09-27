Bluz, a lightweight PHP Framework
=================================
Easy to setup, easy to use. Example application


## Achievements

[![Build Status](https://secure.travis-ci.org/bluzphp/skeleton.png?branch=master)](https://travis-ci.org/bluzphp/skeleton)
[![Latest Stable Version](https://poser.pugx.org/bluzphp/skeleton/v/stable.png)](https://packagist.org/packages/bluzphp/skeleton)
[![Total Downloads](https://poser.pugx.org/bluzphp/skeleton/downloads.png)](https://packagist.org/packages/bluzphp/skeleton)

## Installation

Bluz works with PHP 5.4 or later and MySQL 5.4 or later (please check [requirements](https://github.com/bluzphp/skeleton/wiki/Requirements))

First you should clone Bluz skeleton project from github repository,

```
git clone git://github.com/bluzphp/skeleton.git
```
or download the latest version from [here](https://github.com/bluzphp/skeleton/downloads) and extract it.

Then you need to download composer.phar to the project folder:

```
cd skeleton
curl -s https://getcomposer.org/installer | php
```

and install dependencies with the following command:

```
php composer.phar install
```

Create symlink to public directory (required FollowSymlinks option):

```
ln -s /path/to/public /var/www/htdocs
```

Restore database structure from `structure.ddl` file (use InnoDB as the Default MySQL Storage Engine for avoid "Error Code: 1071"!).
Restore default database data from `dump.sql`


Edit your own configuration file ```/path/to/application/configs/app.dev.php```

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

Read [LICENSE](https://raw.github.com/bluzphp/skeleton/master/LICENSE) file

## Vendors

* [Bluz](https://github.com/bluzphp/framework/)
* [jQuery](https://github.com/jquery/jquery/)
* [RequireJS](http://requirejs.org/)
* [Twitter Bootstrap](http://twitter.github.com/bootstrap/)
