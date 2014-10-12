install:
	curl -sS https://getcomposer.org/installer | php
	php composer.phar install

run:
	foreman start -f Procfile.dev

db_init:
	mysql -u root -p < api/test_environment.sql
	mysql gb_test -u root -p < api/db.schema.sql
