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
    
## Obtaining the EntityManager

Doctrine’s public interface is the EntityManager, it provides the access point to the complete lifecycle management of your entities and transforms entities from and back to persistence. You have to configure and create it to use your entities with Doctrine 2. I will show the configuration steps and then discuss them step by step:
    
    <?php
    // bootstrap.php
    use Doctrine\ORM\Tools\Setup;
    use Doctrine\ORM\EntityManager;
    
    require_once "vendor/autoload.php";
    
    // Create a simple "default" Doctrine ORM configuration for Annotations
    $isDevMode = true;
    $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src"), $isDevMode);
    // or if you prefer yaml or XML
    //$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
    //$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);
    
    // database configuration parameters
    $conn = array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/db.sqlite',
    );
    
    // obtaining the entity manager
    $entityManager = EntityManager::create($conn, $config);

The first require statement sets up the autoloading capabilities of Doctrine using the Composer autoload.

The second block consists of the instantiation of the ORM **Configuration** object using the Setup helper. It assumes a bunch of defaults that you don’t have to bother about for now. You can read up on the configuration details in the [reference chapter on configuration](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/configuration.html).

The third block shows the configuration options required to connect to a database, in my case a file-based sqlite database. All the configuration options for all the shipped drivers are given in the [DBAL Configuration section of the manual](http://www.doctrine-project.org/documentation/manual/2_0/en/dbal).

The last block shows how the **EntityManager** is obtained from a factory method.

## Generating the Database Schema

Now that we have defined the Metadata mappings and bootstrapped the EntityManager we want to generate the relational database schema from it. Doctrine has a Command-Line Interface that allows you to access the SchemaTool, a component that generates the required tables to work with the metadata.

For the command-line tool to work a **cli-config.php** file has to be present in the project root directory, where you will execute the doctrine command. Its a fairly simple file:

    <?php
    // cli-config.php
    require_once "bootstrap.php";
    
    return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);

You can then change into your project directory and call the Doctrine command-line tool:

    cd project/
    vendor/bin/doctrine orm:schema-tool:create

At this point no entity metadata exists in src so you will see a message like “No Metadata Classes to process.” Don’t worry, we’ll create a Product entity and corresponding metadata in the next section.

You should be aware that during the development process you’ll periodically need to update your database schema to be in sync with your Entities metadata.

You can easily recreate the database:

    vendor/bin/doctrine orm:schema-tool:drop --force
    vendor/bin/doctrine orm:schema-tool:create

Or use the update functionality:

    vendor/bin/doctrine orm:schema-tool:update --force
The updating of databases uses a Diff Algorithm for a given Database Schema, a cornerstone of the **Doctrine\DBAL** package, which can even be used without the Doctrine ORM package.