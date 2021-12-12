## Spress add-ons installer

[![Build Status](https://travis-ci.org/spress/Spress-installer.png?branch=master)](https://travis-ci.org/spress/Spress-installer)

Plugin and theme installer for Spress using Composer.

Package type supported:

* spress-plugin
* spress-theme

### Installation

Adds the following to your Spress plugin or theme `composer.json` file:

```json
"require": {
    "yosymfony/spress-installer": "~2.1"
}
```
**Notice** PluginInstaller 2.1 requires **Spress >= 2.2.0**.

### Extra values

```json
"extra": {
    "spress_class": {
        "MyVendor\\MyPlugin\\PluginClass1",
        "MyVendor\\MyPlugin\\PluginClass2"
    }
}
```

* **spress_class**: class names of the plugins (including namespaces).

### An example of `composer.json` file

First, an example for a simple plugin without namespaces:

```json
{
    "name": "myname/my-spress-plugin",
    "type": "spress-plugin",
    "license": "MIT",
    "require": {
        "yosymfony/spress-installer": "~2.1"
    }
}
```

Second, an example for a plugin using namespace:


```json
{
    "name": "myname/my-spress-plugin",
    "type": "spress-plugin",
    "license": "MIT",
    "require": {
        "yosymfony/spress-installer": "~2.1"
    },
    "extra": {
        "spress_class": {
            "MyVendor\\MyPlugin\\PluginClass"
        }
    }
}
```

Unit tests
----------

You can run the unit tests with the following command:
```bash
$ cd your-path
$ composer.phar install
$ phpunit
```
