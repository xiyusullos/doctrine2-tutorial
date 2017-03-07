# doctrine2-tutorial

## Getting Started with Doctrine

This guide covers getting started with the Doctrine ORM. After working through the guide you should know:

- How to install and configure Doctrine by connecting it to a database
- Mapping PHP objects to database tables
- Generating a database schema from PHP objects
- Using the `EntityManager` to insert, update, delete and find objects in the database.

## Guide Assumptions

This guide is designed for beginners that haven’t worked with Doctrine ORM before. There are some prerequesites for the tutorial that have to be installed:

- PHP (latest stable version)
- Composer Package Manager([Install Composer](http://getcomposer.org/doc/00-intro.md))

The code of this tutorial is [available on Github](https://github.com/doctrine/doctrine2-orm-tutorial).
 
## What is Doctrine?
 
Doctrine 2 is an [object-relational mapper (ORM)](http://en.wikipedia.org/wiki/Object-relational_mapping) for PHP 5.4+ that provides transparent persistence for PHP objects. It uses the Data Mapper pattern at the heart, aiming for a complete separation of your domain/business logic from the persistence in a relational database management system.

The benefit of Doctrine for the programmer is the ability to focus on the object-oriented business logic and worry about persistence only as a secondary problem. This doesn’t mean persistence is downplayed by Doctrine 2, however it is our belief that there are considerable benefits for object-oriented programming if persistence and entities are kept separated.
