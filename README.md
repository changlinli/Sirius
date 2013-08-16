Sirius Platform
============

Yet another PHP web framework! 

Platform Basics
---------------

The Sirius Platform is an interface-driven (as opposed to inheritance-driven)
web framework built using PHP. Why interface driven? Sirius is built with the
knowledge that the most amount of time spent on an application is usually not
the initial construction but rather the maintenance and gradual modification
that follows the first "hacking" phase. Therefore a set of defaults has been put
in place that make building an application easy, but throwing away those defauls
is also something that is enthusiastically supported!

Requirements
------------

+ MySQL
+ PHP 5.3+
+ Composer (this is not, strictly speaking, _required_ but it highly recommended
  in order to install the optional add-ons that come with the platform (e.g.
  OAuth, Twig, etc)

### Optional Components

+ Apache (as these instructions are tailored for Apache), but any server that
  supports PHP should work. Keep in mind though that mod\_rewrite is enabled and
relies heavily on .htaccess files. If you switch to another piece of server
software you'll be on your own in terms of setting up rewrite rules.
+ XDebug (absolutely great set of development tools including a debugger,
  tracer, and profiler)
+ UNIX-like OS (although you really shouldn't have problems on other OSes, the
  Sirius platform is developed in a Linux and Mac OS X environment)

How to Install (Easy Version for PHP 5.4+)
------------------------------------------

This is how to install the platform in a matter of minutes without touching a
single line of code and will work for the majority of people. The following
sections will give additional detail if errors are encountered or if users are
looking for a more customized install.

+ Make the directory for your application and `cd` into it
+ `git clone` this repository and `cd` into it
+ Run `curl -sS http://getcomposer.org/install/ | php`
+ Run `php composer.phar install`
+ Run `php base/setup.php`
+ `cd` back up one level back into your application directory and run `php -S
  localhost:8000 index.php` (note that this requires PHP 5.4+)
+ Use a web browser to navigate to localhost:8000 and you're done!

How to Install (More Detailed Version)
--------------------------------------

### Getting the Files in Place

If this is a fresh install (i.e. there is no pre-existing application to drop
this library into), after cloning the git repository containing the platform,
run ``` php base/setup.php ```. This will set up your application with
a few basic files under the `app` folder. These files should be sufficient to
get some HTML and CSS to show on your page indicating that you have successfully
installed the Sirius platform. 

If a pre-existing application already exists which was using an older version of
the Sirius platform, git clone this repository in your application's top-level
folder. Then replace your `index.php` file with ```base/sample_app/index.php``` and
delete your original `base` folder.

If you would like to make use of some of the optional features of the platform
(such as OAuth support and Twig templating), please install Composer using 

    curl -sS https://getcomposer.org/installer | php

and then following up with 

    php composer.phar

The `composer.json` file should then take care of the rest of the features. Note
that due to the way Composer attempts to resolve dependencies, Composer may take
a surprisingly long time to run (ten minutes with the CPU pegged at 100% is not
out of the question).

### (PHP 5.4+) Run the Development Server

If you're using PHP 5.4 or later, you're in luck! Just navigate to the directory
that your `index.php` file is located and run 

    php -S localhost:8000 index.php

and you'll have a development server up and running. Simply enter
`localhost:8000` in a browser window of your choice and you'll see your first
Sirius-powered website!

For other server options, read on.


Configuring the Server
----------------------

If you have PHP 5.3, if you simply don't want to use the PHP built-in
development server (which is extremely slow compared to servers that are meant
to actually serve the website), or are deploying the website in an actual
production environment, the following is for you.

### Setting Up Apache

This next portion is going to assume you are setting up this website for
development purposes on a local machine. That means that we're going to be using
Virtual Hosts to allow ourselves to install several development websites if we
would like.

After all the files have been grabbed, you will need to make sure that Apache is
set up to recognize your site. This means going to wherever sites-enabled is
located (for example on Ubuntu, this is usually located at
`/etc/apache2/sites-available`) and creating a new file. The filename, by
convention, is usually the URL of the associated site. For example, if we are
creating the local site testsite.local, then the filename should be
`testsite.local`.

The `testsite.local` itself should have the following at minimum (once we are
assuming testsite.local as the URL of the site; substitute your own URL for
testsite.local as necessary)

    <VirtualHost *:80>
        RewriteEngine On
        <Directory "/path/to/application/testsite/">
            Options +FollowSymLinks +SymLinksIfOwnerMatch
            AllowOverride All
            DirectoryIndex index.php
        </Directory>
        DocumentRoot "/path/to/application/testsite/"
        ServerName testsite.local
    </VirtualHost>

The rest will be handled by a `.htaccess` file which should already have been
generated in your application's folder.

Now we need to get your computer to associate the URL with a certain IP address
using the `hosts` file. In this case, since we're developing locally, go to your
`hosts` file and add the following line

    127.0.0.1 testsite.local

Now, remember: _restart Apache_ (usually `apachectl restart` or `apache2ctl
restart`)!

### Set Up Database

Although the Sirius plaform should theoretically work with a wide variety of
databases, so far it has only been tested against MySQL. As such these
instructions will be MySQL-specific. 

Open up the custom_config.php file that should appear in the directory one level
above the platform's files. Change the database settings to whatever fits your
MySQL database (this might mean you have to make your MySQL database first).

One possible workflow is the following (if you have access to the MySQL
command-line client).

+ First, in a shell, type

    mysql -u <mysql username> -p<mysql password>

where <mysql username> and <mysql password> are replaced by your username and
password respectively. Note that there should be no space between the `-p` flag
and your password. You should see a prompt telling you have successfully
connected to your MySQL server. Then type the following SQL command.
      
    CREATE DATABASE <database name>

where <database name> is (of course) the name of your database.

+ <mysql username>, <mysql password>, and <database name> should be the values
  you use for ```$DB_USER```, ```$DB_PASS```, and ```$DB_NAME``` respectively
in the ```custom_config.php``` file. ```$DB_HOST``` (unless you know what
you're doing) should stay ```127.0.0.1``` or ```'localhost'```.

### Navigate to Webpage

And that's it! Go to your web browser type in http://testsite.local (the http://
is sometimes necessary as the website may interpret the unfamiliar .local suffix
instead as a search term without the gopher prefix) and you should see the
welcome page.

How to Develop the Platform
---------------------------

If you're looking to develop the platform itself, rather than develop on the
platform, your development requirements should not be very different. Just keep
in mind that you should be using the Git Hooks! Code that does not pass
automated testing but gets pushed anyway should only be there for a VERY good
reason.

### Installing Git Hooks

This project comes with a pre-push git hook (all it does it run phpunit on the
unit tests before pushing). In order to install the git hook, simply execute
```php gen_git_hooks.php```.  Note that the pre-push git hook requires Git
1.8.2+ to function.

The pre-push git hook runs all unit tests in ```tests/unit_tests``` using
PHPUnit and will reject the push if any tests fail.

What This is Built On
---------------------

The following are not requirements that need to be installed separately; they
come bundled with the program files or are automatically included via 3rd-party
CDNs.  Nonetheless they form part of the foundation of the Sirius platform.

+ Twitter Bootstrap
+ jQuery
