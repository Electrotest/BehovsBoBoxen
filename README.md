To use mod_rewrite from within .htaccess files (which is a very common use case), edit the default VirtualHost with

sudo nano /etc/apache2/sites-available/000-default.conf

Search for “DocumentRoot /var/www/html” and add the following lines directly below:

<Directory "/var/www/html">
    AllowOverride All
</Directory>

Save and exit the nano editor via CTRL-X, “y” and ENTER.

Restart the server again:

sudo service apache2 restart

Voila! To check if mod_rewrite is installed correctly, check your phpinfo() output. It should have this in it:
Loaded modules: ... mod_rewrite ...

file permissions 	666 read/write for all
					755 rwx for owner, rx for group, rx for all
					777 read, write, execute for all

Find out which extensions are loaded in apache2: sudo apache2ctl -M | sort


httpd -M will tell you which modules are built-in or shared