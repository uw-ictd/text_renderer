This is a php website for rendering fonts using Pango and Cairo.
These libraries are used because the php function imagettftext does not render some Indic scripts correctly.

Setup and Installation
============

Setup a Ubuntu/Debian based PHP server on which you are admin (for one click solutions see Bitnami or Turnkey).

Run the following commands (as root or using sudo)::

	apt-get update
	apt-get install pango-graphite pkg-config php5-dev aptitude libcairo2-dev make libpango1.0-dev
	apt-get install ttf-indic-fonts-core

Follow this guide: http://fatalweb.com/articles/28/how+to+install+phppango+and+phpcairo+on+linux+centos+ubuntu+server
*Helpful hints for the guide::

	If you aren't root make sure to use sudo when configuring and making.
	
	If you need to find a file (for example cairo.so) try:
	find / -name cairo.so

In your php project directory clone this repository::
	
	Bitnami:
	cd /opt/bitnami/apache2/htdocs
	Turnkey:
	cd /var/www
	
	git clone https://nathanathan@github.com/nathanathan/font_renderer.git

Set the ownership of this site so the server can read/write to it::

	Bitnami:
	chown -r bitnami:daemon font_renderer
	Turnkey:
	chown -r www-data font_renderer

When you are done reset the server::

	Bitnami:
	sudo /opt/bitnami/ctlscript.sh restart apache
	Turnkey:
	/etc/init.d/apache2 restart
	
Now go to [your domain name]/font_renderer and see if the site works.
	
If something is not working check your server logs::

	Bitnami:
	tail -f /opt/bitnami/apache2/logs/error_log
	Turnkey:
	tail -f /var/log/apache2/error.log