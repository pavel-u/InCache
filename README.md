InCache
=======

InCache is a simple tool which will going to represent cache as a new level of abstraction via DSL.

## Installing

### Composer

The fastest way to install InCache in your project is using Composer.

1. Install Composer:

    ```    
    curl -s https://getcomposer.org/installer | php
    ```
    
1. Add InCache as a dependency to your `composer.json` file:

    ```js
    {
        "require": {
            "pavel-u/in-cache": "dev-master"
        }
    }
    ```
Currently project is in the development state. So, "dev-master" version should be used.

1. Install InCache:
    
    ```
    php composer.phar install
    ```

## Usage

### Configure Interceptor
Initialize InCache interceptor:
  ```php
  $interceptor = new \InCache\Interceptor;
  ```
Set Root path:
  ```php
  $interceptor->setRootPath(__DIR__)
  ```
Set path to the config file:
  ```php
  $interceptor->setConfigPath(realpath(__DIR__ . DIRECTORY_SEPARATOR . 'etc/cache.xml'))
  ```
Start to intercept methods calls:
  ```php
  $interceptor->listen()
  ```
Generate proxy classess. First parameter is used to process force code generation:
  ```php
  $interceptor->generate(true);
  ```

### Add config file

Here is an example of config file
  ```xml
  <?xml version="1.0" encoding="UTF-8" ?>
  <cache xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          xsi:noNamespaceSchemaLocation="../vendor/pavel-u/in-cache/src/InCache/cache.xsd">
      <config>
          <argument name="cacheDir" value="/var/www/test/_cache"/>
      </config>
      <types>
          <type name="file" driver="\Stash\Driver\FileSystem" pool="\Stash\Pool" />
      </types>
      <evict
          class="Vendor\TestClass"
          method="testEvictMethod"
          key="someCacheIdentifier"
          type="file"
      />
      <caching
          class="Vendor\TestClass"
          method="testMethod"
          ttl="5"
          type="file"
          key="someCacheIdentifier"
      />
      <caching
          class="Vendor\TestClass"
          method="testMethod2"
          ttl="5"
          type="file"
      />
  </cache>
  
  ```

Also, you could always check [XSD file](https://github.com/pavel-u/InCache/blob/master/src/InCache/cache.xsd) which will provide you more datails about DSL.
