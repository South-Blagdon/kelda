# kelda

Lakeland Terriers web page.

The html files etc for the web page.

This uses twig templates so php and composer will need to be installed to build the web pages

I not sure if 'vendor/' should be added to the .gitignore if it will be used in a github action?

```
composer require "twig/twig:^3.0"
```

## Add a rewrite rule to the apache setup

``` apache
RewriteEngine on

#   first try to find it in dir1/...
#   ...and if found stop and be happy:
RewriteCond         "%{DOCUMENT_ROOT}/dir1/%{REQUEST_URI}"  -f
RewriteRule "^(.+)" "%{DOCUMENT_ROOT}/dir1/$1"  [L]
```

## TODO

* It is kind of a pain to copy the web site to the server, needs

Dir layout:
/build/  # dir to build the website in
/php/  # php functions used in build.php Split up so we don't have 1 big file.
/src/  # web page/html src. Maybe some unused stuff from previous build framework.
/src/content # fav icon, 404 custom page, assets, etc. some stuff is unused from previous build framework
