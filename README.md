Bluz, a lightweight PHP Framework
=================================
Easy to setup, easy to use.

Controller:

```php
<?php
return
/**
 * @privilege View-User-Profile
 * @cache 5 minutes
 * @param integer $id
 * @return closure
 */
function($id) use ($view) {
    /**
     * @var Application $this
     * @var View $view
     */
     $view->user = $this->getDb()->fetchObject("SELECT * FROM users WHERE id = ?", array($id), 'Users\Row');
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
    static $instance;
    protected $table = 'users';
    protected $rowClass = '\Application\Users\Row';
    protected $primary = array('id');
}
```

```php
<?php
namespace Application\Users;
class Row extends \Bluz\Db\Row {
    public $id;
    public $login;
}
```

## Installation
Bluz works with PHP 5.4 or later.

Requried FollowSymlinks option. Create symlink to public directory:

```
ln -s /path/to/public /var/www/htdocs
```

Change permissions for all data directories:

```
chmod a+w /path/to/data/*
```

Rename ```/public/.htaccess.sample``` to ```/public/.htaccess```.
Create your own configuration file ```/path/to/application/configs/app.development.php```

## License

Read LICENSE file

## Contributors

* [Anton Shevchuk][1] 
* [Eugene Zabolotniy][2] 
* [Maks Slesarenko][3] 
* [EagleMoor][4]

## Vendors

* jQuery - https://github.com/jquery/jquery
* jQuery UI - https://github.com/jquery/jquery-ui
* Boostrap - http://twitter.github.com/bootstrap/javascript.html
* jQuery UI Bootstrap - http://addyosmani.github.com/jquery-ui-bootstrap

[1]: https://github.com/AntonShevchuk
[2]: https://github.com/Baziak
[3]: https://github.com/MaksSlesarenko
[4]: https://github.com/EagleMoor