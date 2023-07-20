SHELL := /bin/bash

HTML_FILES := $(wildcard html/*.html)

# FLAVOR := $(shell uname -s)
# LSB_RELEASE := $(shell command -v lsb_release 2> /dev/null)

.PHONY: build

build_menu:
	@echo "Building menu..."
	php build_sidebar.php

copy_assets:
	@echo ""
	@echo "Copying assets..."

	@mkdir -pv build/kelda
	@mkdir -pv build/kelda/assets/images
	@mkdir -pv build/kelda/assets/css
	@cp -ur src/content/assets/images/* build/kelda/assets/images/
	@cp -urv src/content/assets/css/*.css build/kelda/assets/css/
	@cp -urv src/content/favicon/* build/kelda/
	@cp -urv src/content/new404.html build/kelda/
	@cp -urv src/content/.htaccess build/kelda/


build: $(HTML_FILES)
	@echo ""
	@echo "Building static pages..."

	@mkdir -pv build/kelda
	@mkdir -pv build/kelda/assets/images
	@mkdir -pv build/kelda/assets/css
	php build_sidebar.php
	@php build.php
	@cp -ur src/content/assets/images/* build/kelda/assets/images/
	@cp -urv src/content/assets/css/*.css build/kelda/assets/css/
	@cp -urv src/content/favicon/* build/kelda/
	@cp -urv src/content/new404.html build/kelda/
	@cp -urv src/content/.htaccess build/kelda/


debug:
	@php -d display_errors=1 -d error_reporting=E_ALL test/test_dir_scan.php
	@cp -ur build/kelda/* /srv/http/kelda/

deploy_local: build
	rm -rf /srv/http/kelda/*
	cp -ur build/kelda/* /srv/http/kelda/

clean:
	rm -rf docs/assets
	rm -rf docs/kelda
	rm -rf build/*
	
.PHONY: edit
edit:
	code-insiders --extensions-dir="../vscode/insiders/extensions" kelda_www.code-workspace

start_apache:
	@echo "Starting Apache using shell script..."
	./start_apache.sh

install_components:
	composer require "twig/twig:^3.0"


