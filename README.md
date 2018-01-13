# Papertowel
A alternative CMS for the Web

## Theme System
I have implemented a Theme System in a early state. Its based on the Namespace System of Twig.
You can reference a other Theme by adding '@Base' as example before the Path. If you have static files you can directly reference them. 

Example:
    Bare/public/img/test.png -> /public/img/test.png
    
This enables overriding at Filelayer. But leads to higher CPU usage because every file needs to get through PHP, can be better but I currently don't know how.


## Todo:
- Backend
- Plugin System
- Less Compiler
- Typescript Support
- Optimizing
- 

## Pull Request
Please help this Project to grow :)