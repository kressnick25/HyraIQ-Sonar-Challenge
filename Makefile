.PHONY: dev test linkvar

dev:
	php composer.phar install --profile
	php bin/console assets:install

test:
	php bin/phpunit \
		--testdox-html build/phpunit/testdox.html \
		--coverage-html build/phpunit/coverage

linkvar:
	# Link the var directory /var
	rm -rf var
	mkdir -p var
	ln -sf /var/log/symfony/ var/log
	# We need to remove the old cache directory or else Symfony boots with the old config - which might be so broken that nothing works
	rm -rf /var/cache/symfony/*
	ln -sf /var/cache/symfony/ var/cache
