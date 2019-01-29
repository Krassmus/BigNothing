Modules in BigNothing
=====================

Except for a very few corefeatures almost everything in the BigNothing framework is a module. The stream, the profile page, group-overviews, plugins - they all are modules.
Now a module is just a php-script that follows some rules. It should completely be one directory (with files and subdirectories of course) with one php class that is the module. The directory has these typical (but not necessary) files:

* **`assets/`** - Here we have all kind of javascripts, stylesheets and images. This is the only folder that can be directly accessed as if it was within the public folder. `bignothing.tld/mymodule/assets/my.js` would be redirected to the my.js file in the assets folder as if it was public. This magic works only for the assets folder. 
* **`controller/`** - here we expect to be some controllers that render pages. The module should use controllers for all kinds of actions, pages and requests.
* **`mypluginname.php`** - the main module file with a class that is named `mypluginname` and must extend the class `Module`.