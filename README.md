# Papertowel
A alternative CMS for the Web

## Setup

copy .env.dist to .env
```
cp .env.dist .env 
```
Setup MySQL in .env
```
nano .env
```

Create the Schema
```
php bin/console doctrine:schema:create
```

Create the default Language
```
php bin/console papertowel:language:create en_EN English
```

Add a Website to the Database
```
php bin/console papertowel:website:create localhost Test Base
```

Now it _should_ work and you can modify the Code

## Theme System
I have implemented a Theme System in a early state. Its based on the Namespace System of Twig.
You can reference a other Theme by adding '@Base' as example before the Path. If you have static files you can directly reference them. 

Example:
    Bare/public/img/test.png -> /public/img/test.png
    
This enables overriding at Filelayer. But leads to higher CPU usage because every file needs to get through PHP, can be better but I currently don't know how.


## Todo:
- Backend
- ~~Plugin System~~ (Missing better autoloading)
- Less Compiler
- Typescript Support
- Optimizing
- Themes without Namespaces

## Pull Request
Please help this Project to grow :)