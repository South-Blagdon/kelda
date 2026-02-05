
# ================ Variables =========================

SHELL := /bin/bash

HTML_FILES := $(wildcard html/*.html)

# Set default platform
# Used ?= to allow overriding from command line, e.g., make PLATFORM=pico or an environment variable
PLATFORM ?= www # Here this is used for the vscode extention dir. For my MCU make file this also helps include any Arduino spsific files foe example

REPO_ROOT  := $(shell pwd) #there wasn't a Makefile ENV var for this?
BUILD_DIR := build/kelda
BUILD_ASSETS := $(BUILD_DIR)/assets

SRC_DIR := src
SRC_FILES := $(SRC_DIR)/content	# Extra file that need to be copied to the web page as is like images etc.
SRC_ASSETS   := $(SRC_FILES)/assets

REMOTE_ALIAS := web-user # ssh alias name for the web server in case we want to use rsync to copy the files from the local build folder. (this was the old way I did it)
REMOTE_DIR := /var/www/projects/kelda-rsync

VSE_DIR = $(HOME)/var/addons/vscode/$(PLATFORM)/insiders/extensions

.PHONY: ALL
ALL: build

.PHONY: build

build_menu:
	@echo "Building menu..."
	php build_sidebar.php

build: $(HTML_FILES) 
	@echo ""
	@echo "Building static pages..."
	@mkdir -pv $(BUILD_ASSETS)
#	@mkdir -pv $(BUILD_DIR)/assets/images
#	@mkdir -pv $(BUILD_DIR)/assets/css

#	using -r (-r = --relative) Linux will automatically calculate the ../../../ for you!
	@ln -srf -$(SRC_ASSETS)/images $(BUILD_ASSETS)/images
	@ln -srf $(SRC_ASSETS)/css $(BUILD_ASSETS)/css
	
	php build_sidebar.php
	@php build.php

#	@cp -ur src/content/assets/images/* $(BUILD_DIR)/assets/images/
#	@cp -urv src/content/assets/css/*.css $(BUILD_DIR)/assets/css/
	@cp -urv $(SRC_FILES)/favicon/* $(BUILD_DIR)/
	@cp -urv $(SRC_FILES)/new404.html $(BUILD_DIR)/
	@cp -urv $(SRC_FILES)/.htaccess $(BUILD_DIR)/

copy_assets:
	@echo ""
	@echo "Copying assets..."
	@mkdir -pv $(BUILD_DIR)/assets/images
	@mkdir -pv $(BUILD_DIR)/assets/css
	@cp -ur src/content/assets/images/* $(BUILD_DIR)/assets/images/
	@cp -urv src/content/assets/css/*.css $(BUILD_DIR)/assets/css/
	@cp -urv src/content/favicon/* $(BUILD_DIR)/
	@cp -urv src/content/new404.html $(BUILD_DIR)/
	@cp -urv src/content/.htaccess $(BUILD_DIR)/

debug:
	@php -d display_errors=1 -d error_reporting=E_ALL test/test_dir_scan.php
	@cp -ur $(BUILD_DIR)/* /srv/http/kelda/

deploy_local: build
	rm -rf /srv/http/kelda/*
	cp -ur $(BUILD_DIR)/* /srv/http/kelda/

# Deployment rule using rsync
deploy: clean build
	rsync -avz --update $(BUILD_DIR)/ $(REMOTE_ALIAS):$(REMOTE_DIR)

clean:
	rm -rf docs/assets # docs is the dir for the github github-pages on the develop branch
	rm -rf docs/kelda
	rm -rf build/*

.PHONY: edit
edit:
	code-insiders --extensions-dir="$(VSE_DIR)" kelda_www.code-workspace

start_apache:
	@echo "Starting Apache using shell script..."
	./start_apache.sh

# todo check if all of the components are still used
install_components:
	composer require "twig/twig:^3.0"
	composer require "ezyang/htmlpurifier:^4.16.0"
	composer require "symfony/yaml: ^6.2"