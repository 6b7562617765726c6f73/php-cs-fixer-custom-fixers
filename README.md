# PHP CS Fixer: custom fixers

[![Latest Stable Version](https://img.shields.io/packagist/v/kubawerlos/php-cs-fixer-custom-fixers.svg)](https://packagist.org/packages/kubawerlos/php-cs-fixer-custom-fixers)
[![PHP Version](https://img.shields.io/badge/php-%5E7.1-8892BF.svg)](https://php.net)
[![License](https://img.shields.io/github/license/kubawerlos/php-cs-fixer-custom-fixers.svg)](https://packagist.org/packages/kubawerlos/php-cs-fixer-custom-fixers)
[![Build Status](https://img.shields.io/travis/kubawerlos/php-cs-fixer-custom-fixers/master.svg)](https://travis-ci.org/kubawerlos/php-cs-fixer-custom-fixers)
[![Code coverage](https://img.shields.io/coveralls/github/kubawerlos/php-cs-fixer-custom-fixers/master.svg)](https://coveralls.io/github/kubawerlos/php-cs-fixer-custom-fixers?branch=master)

A set of custom fixers for [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer).

## Installation
PHP CS Fixer: custom fixers can be installed by running:
```bash
composer require --dev kubawerlos/php-cs-fixer-custom-fixers
```


## Usage
In your PHP CS Fixer configuration register fixers and use them:
```diff
 <?php
 
 return PhpCsFixer\Config::create()
+    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
     ->setRules([
         '@PSR2' => true,
         'array_syntax' => ['syntax' => 'short'],
+        PhpCsFixerCustomFixers\Fixer\NoLeadingSlashInGlobalNamespaceFixer::name() => true,
+        PhpCsFixerCustomFixers\Fixer\NoTwoConsecutiveEmptyLinesFixer::name() => true,
     ]);

```


## Fixers
- **ImplodeCallFixer** - function `implode` must be called with 2 arguments in the documented order.
```diff
 <?php
-implode($foo, "") . implode($bar);
+implode("", $foo) . implode('', $bar);

```

- **NoDoctrineMigrationsGeneratedCommentFixer** - there must be no comment generated by Doctrine Migrations.
```diff
 namespace Migrations;
 use Doctrine\DBAL\Schema\Schema;
 use Doctrine\Migrations\AbstractMigration;
-/**
- * Auto-generated Migration: Please modify to your needs!
- */
 final class Version20180609123456 extends AbstractMigration
 {
     public function up(Schema $schema)
     {
-        // this up() migration is auto-generated, please modify it to your needs
         $this->addSql("UPDATE t1 SET col1 = col1 + 1");
     }
     public function down(Schema $schema)
     {
-        // this down() migration is auto-generated, please modify it to your needs
         $this->addSql("UPDATE t1 SET col1 = col1 - 1");
     }
 }

```

- **NoLeadingSlashInGlobalNamespaceFixer** - when in global namespace there must be no leading slash for class.
```diff
 <?php
-$x = new \Foo();
+$x = new Foo();
 namespace Bar;
 $y = new \Baz();

```

- **NoPhpStormGeneratedCommentFixer** - there must be no comment generated by PhpStorm.
```diff
 <?php
-/**
- * Created by PhpStorm.
- * User: root
- * Date: 01.01.70
- * Time: 12:00
- */
 namespace Foo;

```

- **NoTwoConsecutiveEmptyLinesFixer** - there must be no two consecutive empty lines in code.
```diff
 <?php
 namespace Foo;
 
-
 class Bar {};

```

- **NoUselessClassCommentFixer** - there must be no comment like: "Class FooBar".
```diff
 <?php
 /**
- * Class FooBar
  * Class to do something
  */
 class FooBar {}

```

- **NoUselessConstructorCommentFixer** - there must be no comment like: "Foo constructor".
```diff
 class Foo
 {
     /**
-     * Foo constructor
      */
     public function __construct() {}
 }

```

- **NoUselessDoctrineRepositoryCommentFixer** - there must be no comment generated by the Doctrine ORM.
```diff
 <?php
-/**
- * FooRepository
- *
- * This class was generated by the Doctrine ORM. Add your own custom
- * repository methods below.
- */
 class FooRepository extends EntityRepository {}

```

- **PhpdocNoIncorrectVarAnnotationFixer** - `@var` must be correct in the code.
```diff
 <?php
-/** @var Foo $foo */
+
 $bar = new Foo();

```

- **PhpdocParamTypeFixer** - `@param` must have type.
```diff
 <?php
 /**
  * @param string $foo
- * @param        $bar
+ * @param mixed  $bar
  */

```

- **PhpdocVarAnnotationCorrectOrderFixer** - `@var` annotation must have type and name in the correct order.
```diff
 <?php
-/** @var $foo int */
+/** @var int $foo */
 $foo = 2 + 2;

```


## Contributing
Request a feature or report a bug by creating [issue](https://github.com/kubawerlos/php-cs-fixer-custom-fixers/issues).

Alternatively, fork the repo, develop your changes, regenerate `README.md`:
```bash
src/Readme/run > README.md
```
make sure all checks pass:
```bash
composer check
```
and submit a pull request.
