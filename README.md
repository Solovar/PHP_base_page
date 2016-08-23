# PHP_base_page
The base I use for making OOP PHP based web pages.

_1.0_

easy up set of database connection.
easy up set of login and permissions.
easy upset of admin page.
have navigation class.

**known issues:**

Advanced search class is broken.
HumanConfirm class works but is a little bulky and can be performance heavy on very small systems.
TempDir class isn't useful.
JS needs tidying up.

**For use of prettyTo Navigate, add the fallowing to .htaccess file:**

```
Options +FollowSymLinks
RewriteEngine On
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule ^.*$ ./index.php
```