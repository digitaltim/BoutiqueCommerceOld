QUICK AND DIRTY:
Create Apache site/host (with SSL support) with root Src/public
Create postgresql db from the postgresDb.sql dump.
Copy Src/config/env-sample.php to Src/config/env.php and fill in the blanks (especially db section)
Navigate to site/index.php then login with btqbtq / btqbtqbtqbtq
--------------------------------------------------------

SSL Apache2 Config (Setup for BTQCM)
-Modify BoutiqueCommerce.conf Apache configuration file and store in /etc/apache2/sites-enabled/
-Add entry to /etc/hosts
-Copy key and cert to /etc/apache2/ssl
-Run:
	sudo a2enmod ssl
	sudo a2enmod rewrite
	service apache2 restart

Gulp (Setup for BTQCM)
-Installed Node & npm using apt-get
-How To Get Started with Gulp.js on your VPS
-Run:
	gulp watch
	Fix errors:
-created sym link to fix Ubuntu package name issue
sudo ln -s /usr/bin/nodejs /usr/bin/node
-fixed errors from prompt

NOTES
Use Chrome 57 and above for CSS Grid
