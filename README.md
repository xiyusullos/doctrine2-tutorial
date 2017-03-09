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

Create a new empty folder for this tutorial project, for example **doctrine2-tutorial** and create a new file `composer.json` with the following contents:

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

For the command-line tool to work a `cli-config.php` file has to be present in the project root directory, where you will execute the doctrine command. Its a fairly simple file:

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

## Starting with the Product

We start with the simplest entity, the Product. Create a `src/Product.php` file to contain the **Product** entity definition:

    <?php
    // src/Product.php
    class Product
    {
        /**
         * @var int
         */
        protected $id;
        /**
         * @var string
         */
        protected $name;
    
        public function getId()
        {
            return $this->id;
        }
    
        public function getName()
        {
            return $this->name;
        }
    
        public function setName($name)
        {
            $this->name = $name;
        }
    }
Note that all fields are set to protected (not public) with a mutator (getter and setter) defined for every field except $id. The use of mutators allows Doctrine to hook into calls which manipulate the entities in ways that it could not if you just directly set the values with `entity#field = foo`;

The id field has no setter since, generally speaking, your code should not set this value since it represents a database id value. (Note that Doctrine itself can still set the value using the Reflection API instead of a defined setter function)

The next step for persistence with Doctrine is to describe the structure of the **Product** entity to Doctrine using a metadata language. The metadata language describes how entities, their properties and references should be persisted and what constraints should be applied to them.

Metadata for entities are configured using a XML, YAML or Docblock Annotations. This Getting Started Guide will show the mappings for all Mapping Drivers. References in the text will be made to the XML mapping.

```XML
<!-- config/xml/Product.dcm.xml -->
<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                    http://raw.github.com/doctrine/doctrine2/master/doctrine-mapping.xsd">

      <entity name="Product" table="products">
          <id name="id" type="integer">
              <generator strategy="AUTO" />
          </id>

          <field name="name" type="string" />
      </entity>
</doctrine-mapping>
```

```YAML
# config/yaml/Product.dcm.yml
Product:
  type: entity
  table: products
  id:
    id:
      type: integer
      generator:
        strategy: AUTO
  fields:
    name:
      type: string
```

```php
<?php
// src/Product.php
/**
 * @Entity @Table(name="products")
 **/
class Product
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string") **/
    protected $name;

    // .. (other code)
}
```
The top-level **entity** definition tag specifies information about the class and table-name. The primitive type **Product#name** is defined as a **field** attribute. The **id** property is defined with the **id** tag, this has a **generator** tag nested inside which defines that the primary key generation mechanism automatically uses the database platforms native id generation strategy (for example AUTO INCREMENT in the case of MySql or Sequences in the case of PostgreSql and Oracle).

Now that we have defined our first entity, let’s update the database:


    $ vendor/bin/doctrine orm:schema-tool:update --force --dump-sql

Specifying both flags `--force` and `--dump-sql` prints and executes the DDL statements.

Now create a new script that will insert products into the database:

```PHP
<?php
// create_product.php
require_once "bootstrap.php";

$newProductName = $argv[1];

$product = new Product();
$product->setName($newProductName);

$entityManager->persist($product);
$entityManager->flush();

echo "Created Product with ID " . $product->getId() . "\n";
```

Call this script from the command-line to see how new products are created:

    php create_product.php ORM
    php create_product.php DBAL

----------
*Here I got some errors:*

> $ php create_product.php ORM
> 
> Fatal error: Uncaught exception 'Doctrine\ORM\Mapping\MappingException' with message 'Class "Product" is not a valid entity or mapped super class.' in D:\04.Codes\doctrine2-tutorial\vendor\doctrine\orm\lib\Doctrine\ORM\Mapping\MappingException.php:336
> Stack trace:
> \#0 D:\04.Codes\doctrine2-tutorial\vendor\doctrine\orm\lib\Doctrine\ORM\Mapping\Driver\AnnotationDriver.php(89): Doctrine\ORM\Mapping\MappingException::classIsNotAValidEntityOrMappedSuperClass('Product')
> \#1 D:\04.Codes\doctrine2-tutorial\vendor\doctrine\orm\lib\Doctrine\ORM\Mapping\ClassMetadataFactory.php(116): Doctrine\ORM\Mapping\Driver\AnnotationDriver->loadMetadataForClass('Product', Object(Doctrine\ORM\Mapping\ClassMetadata))
> \#2 D:\04.Codes\doctrine2-tutorial\vendor\doctrine\common\lib\Doctrine\Common\Persistence\Mapping\AbstractClassMetadataFactory.php(332): Doctrine\ORM\Mapping\ClassMetadataFactory->doLoadMetadata(Object(Doctrine\ORM\Mapping\ClassMetadata), NULL, false, Array)
> \#3 D:\04.Codes\doctrine2-tutorial\vendor\doctrine\common\lib\Doctrine\Common\Pe in D:\04.Codes\doctrine2-tutorial\vendor\doctrine\orm\lib\Doctrine\ORM\Mapping\MappingException.php on line 336
> 

*Reasons*:
- Thanks for the PhpStrom "PHP Annotations" plugin!!! It generated codes like this: 

    ```PHP
    <?php
    use Doctrine\ORM\Mapping as ORM;
    
    /**
     * Created by PhpStorm.
     * User: xiyusullos
     * Date: 2017/3/9
     * Time: 15:29
     *
     * @ORM\Entity() @ORM\Table(name="products")
     */
    class Product
    {
        /**
         * @var int
         *
         * @ORM\Id() @ORM\Column(type="integer") @ORM\GeneratedValue()
         */
        protected $id;
    
        /**
         * @var string
         *
         * @ORM\Column(type="string")
         */
        protected $name;
        // ...
    }
    ```
- One more important is that only double quote works. This `@Table(name='products')` doesn't work. Make sure change the **'** to **"** like  `@Table(name="products")`
----------

What is happening here? Using the Product is pretty standard OOP. The interesting bits are the use of the EntityManager service. To notify the EntityManager that a new entity should be inserted into the database you have to call persist(). To initiate a transaction to actually perform the insertion, You have to explicitly call flush() on the EntityManager.

**This distinction between persist and flush is allows to aggregate all writes (INSERT, UPDATE, DELETE) into one single transaction, which is executed when flush is called. Using this approach the write-performance is significantly better than in a scenario where updates are done for each entity in isolation.**

Doctrine follows the **UnitOfWork pattern**(*[Intent: Maintains a list of objects that are affected by a business transaction and coordinates the writing out of changes and resolution of concurrency problems.](http://wiki.c2.com/?UnitOfWork)*) which additionally detects all entities that were fetched and have changed during the request. You don’t have to keep track of entities yourself, when Doctrine already knows about them.

As a next step we want to fetch a list of all the Products. Let’s create a new script for this:

```PHP
<?php
// list_products.php
require_once "bootstrap.php";

$productRepository = $entityManager->getRepository('Product');
$products = $productRepository->findAll();

foreach ($products as $product) {
    echo sprintf("-%s\n", $product->getName());
}
```

The `EntityManager#getRepository()` method can create a finder object (called a repository) for every entity. It is provided by Doctrine and contains some finder methods such as `findAll()`.

Let’s continue with displaying the name of a product based on its ID:

```PHP
<?php
// show_product.php <id>
require_once "bootstrap.php";

$id = $argv[1];
$product = $entityManager->find('Product', $id);

if ($product === null) {
    echo "No product found.\n";
    exit(1);
}

echo sprintf("-%s\n", $product->getName());
```

Updating a product name demonstrates the functionality UnitOfWork of pattern discussed before. We only need to find a product entity and all changes to its properties are written to the database:

```PHP
<?php
// update_product.php <id> <new-name>
require_once "bootstrap.php";

$id = $argv[1];
$newName = $argv[2];

$product = $entityManager->find('Product', $id);

if ($product === null) {
    echo "Product $id does not exist.\n";
    exit(1);
}

$product->setName($newName);

$entityManager->flush();
```

After calling this script on one of the existing products, you can verify the product name changed by calling the `show_product.php` script.

