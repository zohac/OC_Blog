# OC_Blog

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0e6227d3-00f4-4a82-bb3b-c51fe43cb8c9/big.png)](https://insight.sensiolabs.com/projects/0e6227d3-00f4-4a82-bb3b-c51fe43cb8c9)

## About

Development of my own blog system in php.

## Requirements

* PHP: OC_Blog requires PHP version 7.1 or greater. [![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg?style=flat-square)](https://php.net/)
* MySQL: for the database. [![Minimum MySQL Version](https://img.shields.io/badge/MySQL-%3E%3D5.7-blue.svg?style=flat-square)](https://www.mysql.com/fr/downloads/)
* Composer: to install the dependencies. [![Minimum Composer Version](https://img.shields.io/badge/Composer-%3E%3D1.6-red.svg?style=flat-square)](https://getcomposer.org/download/)

## Installation

### Git Clone

You can also download the OC_Blog source directly from the Git clone:

    git clone https://github.com/zohac/OC_Blog.git
    cd OC_Blog
    composer update

Setting up the database by importing the blog.sql file (in the /app/Config/ folder).
Update the /app/Config/config.xml file accordingly.

Then register to the address /registration.
The first registered user gets the Administrator role, the next ones will have the Subscriber role.

Remember to give write permissions in the /web/upload folder.

## Dependency

* Twig: <https://twig.symfony.com/>
* swiftmailer: <https://swiftmailer.symfony.com/>

## Issues

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/zohac/OC_Blog/issues)

## Author

Simon JOUAN
[https://p5.jouan.ovh](https://p5.jouan.ovh)
