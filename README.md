myMap
=========

A minimal REST web application<sup>[wiki](https://github.com/auburtin/myMap/wiki)</sup> for **publishing** content on a geographic Map, and interactively **subscribing** to particular locations or keywords


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
