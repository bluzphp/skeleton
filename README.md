Bluz, a lightweight PHP Framework
=================================
Easy to setup, easy to use.

Controller:

```php
<?php
return
/**
 * @acl View User Profile
 * @cache 5 minutes
 * @param integer $id
 * @return closure
 */
function($id) use ($app, $view) {
    /**
     * @var Application $app
     * @var View $view
     */
     $view->user = $app->getDb()->fetchObject("SELECT * FROM users WHERE id = ?", array($id), 'Users\Row');
};
```


View:

```php
<h2><?=$user->login?></h2>
```

Model:

```php
namespace Application\Users;
class Row extends \Bluz\Db\Row {

}
```


## Installation
Bluz works with PHP 5.3 or later.

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

## Vendors

* jQuery
* jQuery UI
* Boostrap - http://twitter.github.com/bootstrap/javascript.html

[1]: https://github.com/AntonShevchuk
[2]: https://github.com/Baziak
[3]: https://github.com/MaksSlesarenko