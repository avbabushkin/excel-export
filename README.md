Export to Excel via Symfony 3.4
======


#Requirements

``` bash
$ composer require egyg33k/csv-bundle

```
``` bash
$ composer require phpoffice/phpspreadsheet

```

#Prepare database

Change your parameters.yml 

and create database:

``` bash
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force

```