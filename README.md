# CMOA 2016

WordPress theme and plugin set for the [Carnegie Museum of Art website](http://www.cmoa.org), based on the [Sage starter theme](http://roots.io/sage).

## Project Setup

* Save `wp-config-sample.php` to `wp-config.php` with local server details.

* From the **root project directory**, [download Composer](https://getcomposer.org/download/) and run `php composer.phar install` to install PHP packages.

  * This project also makes use of "premium" plugins that require license keys and must be installed independently.
    * [Advanced Custom Fields](https://www.advancedcustomfields.com/)
    * [WP Migrate DB Pro](https://deliciousbrains.com/wp-migrate-db-pro/)

* For front-end tooling, first install [node](http://nodejs.org/) (recommended version 6.x and higher)
  
  > Use [nvm](https://github.com/creationix/nvm) to manage multiple node versions on your development machine.

* After node is installed, run `npm install -g gulp-cli` and `npm install -g bower` to install Gulp and Bower, respectively.

* From within the **theme directory**, run `npm install` and `bower install` to install any other dependencies.

## Running the Project

* From the **root project directory**, run `docker-compose up -d` to pull and run the Docker containers for this project.

* From the **theme directory**, run `gulp` to load a development server with [Browsersync](https://www.browsersync.io/) enabled. Static assets will be  recompiled/bundled when saved and your browser will be automatically reloaded.

### First run 🚀

* Visit `http://localhost` in your browser and follow the steps to set up a new WordPress site. This setup is temporary and will be overwritten when the database is synced.

  * For Multi-site installs, [follow the steps in the WordPress documentation](https://codex.wordpress.org/Create_A_Network) to set up your network locally.

* Activate the WP Migrate DB Pro plugin and choose "Pull" to sync the production database to your local database.

## Building Assets

Run `gulp production` from within the theme directory to build assets for production and update the asset manifest.
