Add the following to the application composer.json to load all necessary modules


"require": {

"magentoese/module-luma-de-setup":"*"
	
 }

"repositories": {

"magentoese-module-luma-de-setup":{
            
"type": "git",
            
"url": "git@gitlab.the1umastory.com:md/module-luma-de-setup.git"

},

"magentoese-module-luma-de-attributes":{

"type": "git",

"url": "git@gitlab.the1umastory.com:md/module-luma-de-attributes.git"

},

"magentoese-module-luma-de-categories":{

"type": "git",

"url": "git@gitlab.thelumastory.com:md/module-luma-de-categories.git"

},

"magentoese-module-luma-de-products":{

"type": "git",

"url": "git@gitlab.thelumastory.com:md/module-luma-de-products.git"

}

}