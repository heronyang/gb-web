install:
	curl -sS https://getcomposer.org/installer | php
	php composer.phar install

run:
	foreman start -f Procfile.dev
