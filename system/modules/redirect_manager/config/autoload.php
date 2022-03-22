<?php

/**
 * Redirect Manager
 *
 * Copyright (C) 2019-2022 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */



/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    'RedirectManager\Model\Redirect' 		=> 'system/modules/redirect_manager/library/RedirectManager/Model/Redirect.php',
    'RedirectManager\Frontend\Redirect' 	=> 'system/modules/redirect_manager/library/RedirectManager/Frontend/Redirect.php',
    'RedirectManager\Backend\Redirect' 		=> 'system/modules/redirect_manager/library/RedirectManager/Backend/Redirect.php',
    'RedirectManager\Module\Redirect404' 	=> 'system/modules/redirect_manager/library/RedirectManager/Backend/Redirect404.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'mod_redirect_manager' 			=> 'system/modules/redirect_manager/templates/modules'
));
