myMap
=========

A small REST web application<sup>[wiki](https://github.com/cyrilaub/myMap/wiki)</sup> with a geographic Map


installation
-------------

  - Make a link, to serve **www/myMap** folder: ln -s www/myMap /path/to/webserverhtdocs 
  - If your server is Apache, add these Rewrite rules, in www/myMap/.htaccess:

```
    RewriteEngine On
    RewriteRule ^/?(javascript|css|img) - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . index.php [L]
```

  - Browse [http://localhost/myMap](http://localhost/myMap)
