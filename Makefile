default: help

help:
	@echo "Please use 'make <target>' where <target> is one of"
	@echo "  tests                  Executes the Unit tests"

tests:
	./bin/phpunit

.PHONY: tests