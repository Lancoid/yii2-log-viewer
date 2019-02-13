Yii2 Log Viewer
===============
Yii2 log viewer

Installation
------------

Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
    'bootstrap' => ['log-viewer'],
    'modules' => [
        'log-viewer' => [
            'class' => 'lancoid\yii2LogViewer\Module',
            'aliases' => [
                'Api Errors' => '@api/runtime/logs/app.log',
                'Console Errors' => '@console/runtime/logs/app.log',
                'Backend Errors' => '@backend/runtime/logs/app.log',
                'Frontend Errors' => '@frontend/runtime/logs/app.log',
            ],
            'lang' => 'en',
            'accessRoles' => ['admin'],
            'moduleUrl' => 'admin/log-viewer',
        ],
    ],
];
```

You can then access Log Reader using the following URL:

```php
http://localhost/path/to/index.php?r=log-viewer
```

or if you have enabled pretty URLs, you may use the following URL:

```php
http://localhost/log-viewer
```
