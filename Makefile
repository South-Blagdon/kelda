HTML_FILES := $(wildcard html/*.html)

.PHONY: build

build: $(HTML_FILES)
	@echo "Building static pages..."
	@mkdir -pv build/kelda
	@mkdir -pv build/kelda/assets/images
	@mkdir -pv build/kelda/assets/css
	@php build.php
	@cp -ur src/content/assets/images/* build/kelda/assets/images/
	@cp -urv src/content/assets/css/*.css build/kelda/assets/css/

debug:
	@php -d display_errors=1 -d error_reporting=E_ALL test/test_dir_scan.php
	@cp -ur build/kelda/* /srv/http/kelda/
clean:
	rm -rf docs/assets
	rm -rf docs/kelda
	rm -rf build/*
	
.PHONY: edit
edit:
	code-insiders --extensions-dir="../vscode/insiders/extensions" kelda_www.code-workspace
