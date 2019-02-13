Bluz, a lightweight PHP Framework
=================================
Easy to setup, easy to use. Skeleton application

## Achievements

[![PHP >= 7.1+](https://img.shields.io/packagist/php-v/bluzphp/skeleton.svg?style=flat)](https://php.net/)

[![Latest Stable Version](https://img.shields.io/packagist/v/bluzphp/skeleton.svg?label=version&style=flat)](https://packagist.org/packages/bluzphp/skeleton)

[![Build Status](https://img.shields.io/travis/bluzphp/skeleton/master.svg?style=flat)](https://travis-ci.org/bluzphp/skeleton)

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/bluzphp/skeleton.svg?style=flat)](https://scrutinizer-ci.com/g/bluzphp/skeleton/)

[![Coverage Status](https://img.shields.io/coveralls/bluzphp/skeleton/master.svg?style=flat)](https://coveralls.io/r/bluzphp/skeleton?branch=master)

[![Total Downloads](https://img.shields.io/packagist/dt/bluzphp/skeleton.svg?style=flat)](https://packagist.org/packages/bluzphp/skeleton)

[![License](https://img.shields.io/packagist/l/bluzphp/skeleton.svg?style=flat)](https://packagist.org/packages/bluzphp/skeleton)

## Installation
Bluz works with PHP 7.0 or later and MySQL 5.4 or later (please check [requirements](https://github.com/bluzphp/skeleton/wiki/Requirements))

### I.a. From composer
Download `composer.phar`, it's easy:
```bash
curl -s https://getcomposer.org/installer | php
```

Run `create-project` command (replace `%path%` ;):
```bash
php composer.phar create-project bluzphp/skeleton %path% --stability=dev
```

### I.b. From repository
Get Bluz skeleton source files from GitHub repository:
```bash
git clone git://github.com/bluzphp/skeleton.git %path%
```

Download `composer.phar` to the project folder:
```bash
cd %path%
curl -s https://getcomposer.org/installer | php
```

Install composer dependencies with the following command:
```bash
php composer.phar install
```

### I.c. With PhpStorm
For install you need any web-server (for Windows) and PhpStorm. dows) и PhpStorm.

Create project in PhpStorm:

1. File -> New project;
2. Set the project name and location;
3. In a Project type field choose Composer project;
4. Check that radiobutton is set opposite "Download composer.phar from getcomposer.org", type in a search field "bluzphp/skeleton", select this package in Available packages window and click OK.
5. After that file composer.phar and all dependencies will be loaded. 

### II. Configuration
Edit your configuration's files `/path/to/application/configs/dev/*.php` (configuration for development environment).
> I think you need to change only `db.php` for first run

### III. Setup database
To run the migrations, execute the command:
```bash
/path/to/vendor/bin/bluzman db:migrate
```

To fill database with data example, execute the command:
```bash
/path/to/vendor/bin/bluzman db:seed:run
```

### IV.a. Run built-in web-server
You can run internal PHP web-server with simple console tool:
```bash
/path/to/vendor/bin/bluzman server:start --host[="..."] --port[="..."]
```

### IV.b. Use Apache
Or create symlink to Apache document root (required FollowSymlinks option):

```bash
# for Linux
ln -s /path/to/public /path/to/web
```

```bash
# for Windows
mklink /D /path/to/web path/to/public
```

## Usage

You can create models, controllers and views with [Bluzman](https://github.com/bluzphp/bluzman) console tool, 
or following *old school style*:

### Model
Model consists from two classes `Table` and `Row`:
```php
<?php
namespace Application\Users;
class Table extends \Bluz\Db\Table
{
    protected $table = 'users';
    protected $primary = ['id'];
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

### Controller
Controller is file with anonymous function:
```php
<?php
namespace Application;

/**
 * @privilege ViewProfile
 * @cache     5 minutes
 * @param     integer $id
 * @return    array
 */
return function($id) {
    return [
        'user' => Users\Table::findRow($id)
    ];
};
```

### View
View is native:
```php
<h2><?=$user->login?></h2>
```

## Documentation
* [Framework wiki](https://github.com/bluzphp/framework/wiki)
* [Skeleton wiki](https://github.com/bluzphp/skeleton/wiki)
* [Bluzman docs](https://github.com/bluzphp/bluzman)

## Demo
* [Bluz Demo](http://bluz.demo.php.nixdev.co)

## License
The project is developed by [NIX Solutions](http://nixsolutions.com) PHP team and distributed under [MIT LICENSE](https://raw.github.com/bluzphp/skeleton/master/LICENSE.md)

[NIX Solutions](http://nixsolutions.com) has OEM License of [Redactor](http://imperavi.com/redactor/).
Full text of Redactor License you can read at http://imperavi.com/redactor/license/

## Vendors
* [jQuery](https://github.com/jquery/jquery/)
* [RequireJS](http://requirejs.org/)
* [Twitter Bootstrap](http://getbootstrap.com/)
