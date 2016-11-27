ModuleLinuxBundle
========================

Bundle with custom rendering of modules for [NetopeerGUI](https://github.com/CESNET/Netopeer-GUI).

## Installation

### Step 1.
Edit composer.json, add following
	"repositories":
    [
        {
            "type": "vcs",
            "url": "https://github.com/CESNET/Netopeer-GUI-module-linux"
        }
    ],

Add bundle into app using composer:

	php composer.phar require cesnet/module-linux-bundle "dev-master"

Add "leafo/lessphp": "dev-master" into "require": {}

### Step 2.
Enable the bundle in the kernel:

	<?php
	// app/AppKernel.php

	public function registerBundles()
	{
	    $bundles = array(
	        // ...
	        new FIT\Bundle\ModuleLinuxBundle\FITModuleLinuxBundle(),
	    );
	}

### Step 3.
Add filter for translating less

    Edit app/config/config.yml:
    add lessphp

    assetic:
        filter:
            ...
            lessphp:
                apply_to: "\.less$"
                formatter: "compressed"
                preserve_comments: false



### Step 4.
Enable the bundle for custom module name in DB, just like that:

	INSERT INTO "ModuleController" ("moduleName", "moduleNamespace", "controllerActions") VALUES ('system', 'urn:ietf:params:xml:ns:yang:ietf-system', 'FIT\Bundle\ModuleLinuxBundle\Controller\ModuleController::moduleAction');
	INSERT INTO "ModuleController" ("moduleName", "moduleNamespace", "controllerActions") VALUES ('interfaces', 'urn:ietf:params:xml:ns:yang:ietf-interfaces', 'FIT\Bundle\ModuleLinuxBundle\Controller\ModuleController::moduleAction');

It is necessary to do this by hand, because no administration or command is created yet.

