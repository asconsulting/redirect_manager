<?php
 
/**
 * Redirect Manager
 *
 * Copyright (C) 2019 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */

 
/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
    'Asc\Model\Redirect' 		=> 'system/modules/redirect_manager/library/Asc/Model/Redirect.php',
    'Asc\Frontend\Redirect' 	=> 'system/modules/redirect_manager/library/Asc/Frontend/Redirect.php',
    'Asc\Backend\Redirect' 		=> 'system/modules/redirect_manager/library/Asc/Backend/Redirect.php',
    'Asc\Module\Redirect404' 	=> 'system/modules/redirect_manager/library/Asc/Backend/Redirect404.php',
));
