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
- ability to record and plot all times, when the pool found a block
- utilize new 'block' command in pool software, now it's not used at all

# Required skills
- in order to run the pool you should be fluent in Unix / Linux administration skills and have basic understanding of computer programming

# Pull requests
Please submit your pull requests with new features, improvements and / or bugfixes. Utilize the github issue tracker if necessary. Please note that in order to develop the pool,
good Laravel 5, webpack, mix, blade, sasa, javascript and bulma experience is needed. Please do not be offended if I don't accept your pull requests or have comments / questions about them.

# Dependencies
- pool version at least T13.895
- nginx, php7+, mariadb or mysql, npm

# How the pool works
The pool website periodically fetches exported data from the pool daemon. Pool daemon-side scripts are in a [https://github.com/kbs1/openxdagpool-scripts](separate repository).
This data is stored locally and then processed.
Data flow is one way only, from pool daemon (exports) to the pool website. Only exception is balance checking, which calls `/balance.php` on pool-daemon server.

Processed results are most often stored in a database. The pool re-reads imported text files whenever necessary.

This means the pool website is totally independent of the pool itself. Should the pool software die, or it's cron tasks stop, the pool website would just endlessly display the last exported information
from the pool daemon.

# Scope of this readme
This readme gives an overview on how to get the pool website up and running. It can't go in-depth on every step, only overview is provided.

# Installation
This giude expects the pool software with required scripts ([https://github.com/kbs1/openxdagpool-scripts](separate repository)) is up and running, either on this server or on a different server.

Perform the following steps in order to get the website up and running:
1. Install all PHP7.0 requirements, including PDO and MySQL drivers, OpCache
2. Install mysql 5.7 or mariadb and create new user, `pooldb` for example
3. Install nginx and set up a PHP FPM pool.
4. clone this project into `/var/www`. Proceed as `www-data` or other user that the PHP FPM pool runs as
5. configure nginx to properly execute this website
6. install composer and npm 8.x
7. `cp .env.example .env`
8. edit `.env` and set up correct values, read the comments for help
9. in `/var/www`, run `composer install`
10. run `php artisan key:generate`
11. run `php artisan migrate`
12. run `npm run production`
13. install a letsencrypt certificate or similar (optional)
14. visit the web site, and register. First registered user is an administrator.
15. visit the administration interface to set up your pool settings.

Done! Happy usage from OpenXDAGPool! :)
