
all :
	spress site:build
	mkdir -pv docs/kelda
	cp -ur build/kelda/* docs/kelda/
	cp -ur build/404 docs
	mkdir -pv docs/assets/images
	mv -u build/assets/*.pdf docs/assets
	cp -ur build/assets/images/* docs/assets/images
	mkdir -pv docs/assets/css
	cp -ur build/assets/css/style.css docs/assets/css
	cp -u build/*.html build/favicon.ico docs/
	git add .
	git commit -m 'fixing html paths'
	git push

clean:
	rm -rf docs/assets
	rm -rf docs/kelda
	rm -rf build/*
