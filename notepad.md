## Setting up phpunit tests for a wp plugin

Guide: [Plugin Integration Tests](https://make.wordpress.org/cli/handbook/misc/plugin-unit-tests/)

- initialize composer
  - `composer init`
    - skip the autoload stuff
- install phpunit
  - `composer require --dev phpunit/phpunit ^9`
- generate test scaffold
  - `wp scaffold plugin-tests my-plugin`
    - must be run from the WP-CLI client for the wp command to be visible
      - in Local, this is the Open site shell option
- initialize the local test environment
  - `bash bin/install-wp-tests.sh local_test root root localhost:10004 latest`
    - this will create a new database called local_test
    - the root user will be given access to the database
    - the database will be created in the localhost environment
      - Local runs the database on port 10004
      - If not foudn there, use `lsof` or Activity Monitor to find the port of mysqld
    - the latest version of wordpress will be installed
- install polyfills
  - `composer require --dev yoast/phpunit-polyfills:"^2.0"`
  - [project site](https://github.com/Yoast/PHPUnit-Polyfills)
- rename tests/test-sample.php to match required naming tests/SampleTest.php
- update phpunit.xml.dist
  - change the testsuite name to the plugin name
  - update the testsuite directory
    - remove prefix
    - change suffix to `Test.php`
  - remove exclusion

### phpunit must be run explicitly from vendor

`.vendor/bin/phpunit`

### the tests must be run under WP-CLI as well

Unfortunately, this precludes using a PHPUnint extension

### Needed to grant local access to sql database

Used this solution: [MySQL Host '::1' or '127.0.0.1' is not allowed to connect to this MySQL server](https://stackoverflow.com/questions/44871109/mysql-host-1-or-127-0-0-1-is-not-allowed-to-connect-to-this-mysql-server#answer-67574175)

### Alternative set up instructions

A few other approaches to consider:
- [Setting up PHPUnit for WordPress Plugin Development](https://www.smashingmagazine.com/2017/12/automated-testing-wordpress-plugins-phpunit/)
- [Plugin Unit Test](https://www.codetab.org/tutorial/wordpress-plugin-development/unit-test/plugin-unit-testing/)

## VS Code will complain about WP_UnitTestCase

Intelephense stubs are required

`composer require --dev php-stubs/wordpress-tests-stubs`

## Articles on mocking WP globals

[THE PRACTICE OF WORDPRESS UNIT TESTING](https://wp-punk.com/the-practice-of-wordpress-unit-testing/)

## PDF generation

We would like to generate a PDF certificate for course completion.

[PDFlib](https://www.pdflib.com/) is a commercial product and watermarks the result without a license.
[FPDF](http://www.fpdf.org/) is a free, open source package.
[FPDI](https://www.setasign.com/products/fpdi/about) can be used to import an existing PDF to use as a template. Then FPDF can be used to add content.

There are also libraries that can generate a pdf from HTML and CSS.
[dompdf](https://github.com/dompdf/dompdf)

## Zipping plugin

The plugin must be zipped for deployment to the live site. The macos Compress command will not work because it includes the enclosing folder in the zip file. Instead, use the zip command from the terminal.

`zip -vr ada-aba.zip ada-aba`

This will zip the contents `ada-aba` folder into a file called `ada-aba.zip`, with verbose output and recursion.