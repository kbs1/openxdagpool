# OpenXDAGPool
This software allows you to easily open a Dagger (XDAG) pool with a nice, comfortable UI available to your users.

# Features
- detailed pool and network statistics, including graphs (hashrate, active miners, difficulty, ...)
- address balance checker tool
- leaderboard
- detailed guides on how to set-up miners
- rich administration interface allowing to customise many aspects of the website
- independent of 3rd party services (all data is exported / queried on local pool software)
- secure, with clean code and expandability in mind
- optional registration allows users to manage their miners in one place
- detailed payouts (exportable) and hashrate history for registered miners
- miner offline / miner back online alerts for registered miners

# Planned features
- ability to check payouts history for non-registered miners by entering miner's address, this is possible as whole pool's payment history from the beginning of operation is stored in the database
- ability to approximate earnings based on hashrate
- ability to record and plot all times  the pool found a block
- utilize new 'block' command in pool software, currently it is unused
- mobile friendly design tweaks
- translations and languages support, support for a simple CMS (setup pages, other pool documents and similar)
- code refactoring, mainly `PayoutsController`, use repository and presenters for models, other improvements
- add the ability to customise website design a bit, for example by allowing to upload a `favicon.ico`, or change hero color to a different one, or change bulma theme as a whole

# Expected skills
In order to run the pool you should be fluent in Unix / Linux administration and have basic understanding of computer programming.

# Pull requests
Please submit your pull requests with new features, improvements and / or bugfixes. Utilize the GitHub issue tracker if necessary. Please note that in order to develop the pool,
good Laravel 5, webpack, mix, blade, sass, javascript and bulma experience is needed. All pull requests must have reasonable code quality and security.

# Dependencies
- pool version at least T13.895 (previous versions printed network hashrate as an average over one hour, since 895 it is averaged over 4 hours)
- nginx, php7+, mariadb or mysql, npm 8.x

# How the pool website works
The pool website periodically fetches exported data from the pool daemon. Pool daemon-side scripts are in a [separate repository](https://github.com/kbs1/openxdagpool-scripts).
This data is stored locally and then processed.
Data flow is one way only, from pool daemon (exports) to the pool website. Only exception is balance checking, which calls `/balance.php`
on pool-daemon server, but this URL is configurable in `.env`. You can use any other balance checker that *contains* compatible output (`x.xxxxxxxxx` - the address in question balance with 9 decimal places) and
can accept XDAG address in question as a GET / route parameter.

Processed results are most often stored in a database. The pool re-reads imported text files whenever necessary.

This means the pool website is totally independent of the pool itself. Should the pool software side cron tasks stop, the pool website would just endlessly display the last exported information
from the pool daemon.

# Installation
This giude expects that the pool software with required scripts ([openxdagpool-scripts](https://github.com/kbs1/openxdagpool-scripts)) is up and running, either on website server or on a different server.
This installation guide gives an overview on how to get the pool website up and running. It can't go in-depth on every step, however all important details are provided.

Perform the following steps in order to get the website up and running:
1. install all PHP7.0 requirements, for Ubuntu 16.04, use `apt-get install php7.0-bcmath php7.0-cli php7.0-common php7.0-fpm php7.0-json php7.0-mbstring php7.0-mcrypt php7.0-mysql php7.0-opcache php7.0-readline php7.0-sqlite3 php7.0-xml php7.0-zip`. Next configure `php.ini` to your preference. Set `memory_limit` to at least `256M`, `expose_php` to `Off`, set `error_reporting` to `E_ALL`.
2. install mysql 5.7 or mariadb. Create new database, for example `openxdagpool`, with `CREATE DATABASE openxdagpool CHARACTER SET utf8mb4 COLLATE utg8mb4_unicode_ci` run as mysql's `root` user. Grant all privileges to a new user: `GRANT ALL ON openxdagpool.* TO openxdagpool@'%' IDENTIFIED BY 'PWD!!!!'`. Choose your own password!
3. install nginx and set up a PHP FPM pool running as user of your choice.
4. clone this project into `/var/www/openxdagpool`. Proceed as `www-data` or other user that the PHP FPM pool runs as
5. configure nginx to properly execute this website
6. install [composer](https://getcomposer.org/download/) and [npm 8.x](https://nodejs.org/en/download/package-manager/#debian-and-ubuntu-based-linux-distributions)
7. `cp .env.example .env`
8. edit `.env` and set up correct values, read the comments for help. Mail settings are required for miner alerts to work properly.
9. in `/var/www/openxdagpool`, run `composer install`
10. run `php artisan key:generate`
11. run `php artisan migrate`
12. run `npm install` and then `npm run production`
13. install a letsencrypt certificate or other https certificate (optional)
14. visit the web site, and register. First registered user is an administrator.
15. visit the administration interface to set up your pool settings.
16. payouts exports of large datasets require the mysql files privilege. Edit `/etc/mysql/mysql.conf.d/mysqld.cnf` and in the `[mysqld]` section, add `secure-file-priv=/var/www/openxdagpool/public/payouts/`. Then execute `GRANT FILE ON *.* TO 'pool'@'localhost';` as mysql `root` user. Restart the mysql daemon using `service mysql restart` as `root`.
17. as the PHP FPM pool user, execute `crontab -e` and enter one cron line: `* * * * * php /var/www/openxdagpool/artisan schedule:run >> /dev/null 2>&1`

Done! Enjoy your new OpenXDAGPool instance! ;-)
