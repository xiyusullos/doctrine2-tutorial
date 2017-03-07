# doctrine2-tutorial

---

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

## What are Entities?

Entities are PHP Objects that can be identified over many requests by a unique identifier or primary key. These classes don’t need to extend any abstract base class or interface. An entity class must not be final or contain final methods. Additionally it must not implement **clone** nor **wakeup**, unless it [does so safely](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/implementing-wakeup-or-clone.html).

An entity contains persistable properties. A persistable property is an instance variable of the entity that is saved into and retrieved from the database by Doctrine’s data mapping capabilities.

## An Example Model: Bug Tracker

For this Getting Started Guide for Doctrine we will implement the Bug Tracker domain model from the [Zend_Db_Table ](http://framework.zend.com/manual/1.12/en/zend.db.adapter.html)documentation. Reading their documentation we can extract the requirements:

- A Bug has a description, creation date, status, reporter and engineer
- A Bug can occur on different Products (platforms)
- A Product has a name.
- Bug reporters and engineers are both Users of the system.
- A User can create new Bugs.
- The assigned engineer can close a Bug.
- A User can see all his reported or assigned Bugs.
- Bugs can be paginated through a list-view.

## Project Setup

Create a new empty folder for this tutorial project, for example **doctrine2-tutorial** and create a new file **composer.json** with the following contents:

    {
        "require": {
            "doctrine/orm": "2.4.*",
            "symfony/yaml": "2.*"
        },
        "autoload": {
            "psr-0": {"": "src/"}
        }
    }


Install Doctrine using the Composer Dependency Management tool, by calling:

    composer install

This will install the packages Doctrine Common, Doctrine DBAL, Doctrine ORM, Symfony YAML and Symfony Console into the vendor directory. The Symfony dependencies are not required by Doctrine but will be used in this tutorial.

Add the following directories:

    doctrine2-tutorial
    |-- config
    |   |-- xml
    |   `-- yaml
    `-- src
    
