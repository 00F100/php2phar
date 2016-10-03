.PHONY: all test update-repo mkdir-dist clean-dist mkdir-bin download-composer composer-run composer-dev-run create-phar

all: update-repo mkdir-dist clean-dist mkdir-bin download-composer composer-run create-phar commit-push-changes-git
test:  update-repo mkdir-dist clean-dist mkdir-bin download-composer composer-dev-run create-phar

update-repo:
	git reset --hard;
	git checkout master;
	git pull origin master;

mkdir-dist:
	if [ ! -d "dist" ] ; then \
		mkdir dist; \
	fi

clean-dist:
	if [ -f "dist/php2phar.phar" ] ; then \
		rm dist/php2phar.phar; \
	fi
	if [ -f "dist/php2phar.phar.gz" ] ; then \
		rm dist/php2phar.phar.gz; \
	fi

mkdir-bin:
	if [ ! -d "bin" ] ; then \
		mkdir bin; \
	fi

download-composer:
	if [ ! -f "bin/composer.phar" ] ; then \
		cd bin; \
		php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
		php composer-setup.php; \
		php -r "unlink('composer-setup.php');"; \
	fi;

composer-run:
	if [ -f "composer.lock" ] ; then \
		php bin/composer.phar update --no-dev; \
	else \
		php bin/composer.phar install --no-dev; \
	fi

composer-dev-run:
	if [ -f "composer.lock" ] ; then \
		php bin/composer.phar update; \
	else \
		php bin/composer.phar install; \
	fi

create-phar:
	php src/index.php -d ./ -i src/index.php -o dist/php2phar.phar;

commit-push-changes-git:
	git add "dist/php2phar.phar";
	git commit -m "Jenkins update the php2phar.phar";
	git push origin master;