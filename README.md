Add the following to the application composer.json to load all necessary modules


"require": {

"magentoese/module-luma-de-setup":"*"
	
 }

"repositories": [{
	 
"type": "git",
	 
"url": "git@gitlab.the1umastory.com:md/module-luma-de-setup.git"
      
},

{
        
 "type": "git",
 
"url": "git@gitlab.the1umastory.com:md/module-luma-de-attributes.git"

},
    
{

"type": "git",

"url": "git@gitlab.thelumastory.com:md/module-luma-de-categories.git"

},

{

"type": "git",

"url": "git@gitlab.thelumastory.com:md/module-luma-de-products.git"

}]