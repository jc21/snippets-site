Snippets Site based on Yii2
================================================

A Yii2 Site for hosting your own Snippets with Member accounts

- Showcase your code snippets
- Basic search functionality
- Registrations with email verifications
- Password reset functionality
- Bookmarks
- Basic statistics tracking

### Installation

Install Composer if you don't have it already

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

Get the code:

```bash
git clone https://github.com/jc21/snippets-site.git
cd snippets-site
```

Next, run Composer commands to install the required Yii2 plugins and code for the app:

```bash
composer global require "fxp/composer-asset-plugin:~1.0.3"
composer install
```
Now move the example config file and change the contents for your database setup:

```bash
mv common/config/main.php.example common/config/main.php
vi common/config/main.php
```

Then edit the params file and set your own secret string and fromEmail:

```bash
vi common/config/params.php
```

We need to make sure that some directories are writable by the server. This step only applies to unix based
systems. I've got a script that will do this for you:

```bash
./make_writable
```

Assuming your database config is all correct you can run the Yii migration to inject the database schema.

*Note: This is written for MySQL, if you want to use a different database type you'll need to edit the
migration file to suit: console/migrations/m150922_000000_initial.php*

```bash
./yii migrate
```

And finally you'll need to setup Apache (or whatever). There's an example config file:

```bash
cat common/config/httpd.conf
```

### Using

Once you're setup you can visit the site in your browser. To setup your initial user you can just Register.

Registration requires that your systems email is working. You can specify swiftmail options in the config
if you want to use a SMTP or something else.

To close registrations all you have to do is edit the params.php file and change registrationsOpen to false.

### Support

There is none. I'm sharing this for the sake of sharing. I offer zero support and there's a high chance I won't
respond to any issues.
