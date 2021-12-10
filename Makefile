
all :
	spress site:build
	mkdir -pv docs/kelda
	cp -urv build/kelda/* docs/kelda/
	cp -urv build/404 docs
	mkdir -pv docs/assets/images
	mv -u build/assets/*.pdf docs/assets
	cp -urv build/assets/images/* docs/assets/images
	mkdir -pv docs/assets/css
	cp -urv build/assets/css/style.css docs/assets/css
	cp -u build/*.html build/favicon.ico docs/

clean:
	rm -rf docs/assets
	rm -rf docs/kelda
	rm -rf build/*
