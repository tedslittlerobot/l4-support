
install: clean
	@composer install

test:
	@phpunit

clean-test:
	@rm -rf report

coverage:
	@phpunit --coverage-html ./report

report: coverage
	@open ./report/index.html

clean: clean-test
	@rm -rf vendor
	@rm -f composer.lock

todo:
	@echo "@TODO"
	@echo "====="
	@grep -rn "@TODO" . | awk -F ":" '$$1 != "./Makefile" {print $$3; print $$1 $$2  }'
	@echo
	@echo "@todo"
	@echo "====="
	@grep -rn "@todo" . | awk -F ":" '$$1 != "./Makefile" {print $$3; print $$1 $$2  }'
