test:
	./vendor/bin/pest --colors=always

test-watch:
	./vendor/bin/pest --colors=always --watch

test.ci:
	./vendor/bin/pest --colors=always --ci
