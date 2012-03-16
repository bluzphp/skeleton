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
     $view->user = $app->getDb()->fetchObject("SELECT * FROM users WHERE id = ?", array($id), 'User');
};
```


View:

```php
<h2><?=$user->login?></h2>
```



## Installation
Bluz works with PHP 5.3 or later.

Requried FollowSymlinks option:

```
ln -s /path/to/public /var/www/htdocs
```

Chnage permissions for all data directories

```
chmod a+w /path/to/data/*
```

Rename ```/public/.htaccess.sample``` to ```/public/.htaccess```

## License

Read LICENSE

## Vendors

* jQuery
* jQuery UI
* Boostrap - http://twitter.github.com/bootstrap/javascript.html
