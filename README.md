# kelda
Lakeland Terriers web page.

The html files etc for the web page.

Using Spress for the Twig template engine to add the menu, header & footer etc for each page https://spress.yosymfony.com/docs/how-it-works/

Guess I could of just used make to cat all the files together but if you ever want to move to Symphony this will be a lot of the way there. :)

## Add a rewrite rule to the apache setup:
```
RewriteEngine on

#   first try to find it in dir1/...
#   ...and if found stop and be happy:
RewriteCond         "%{DOCUMENT_ROOT}/dir1/%{REQUEST_URI}"  -f
RewriteRule "^(.+)" "%{DOCUMENT_ROOT}/dir1/$1"  [L]
```
