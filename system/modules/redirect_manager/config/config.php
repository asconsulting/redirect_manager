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
 * Back end modules
 */
if (!is_array($GLOBALS['BE_MOD']['redirect_manager']))
{
    array_insert($GLOBALS['BE_MOD'], 1, array('redirect_manager' => array()));
}

array_insert($GLOBALS['BE_MOD']['redirect_manager'], 0, array
( 
	'redirects' => array
	(
		'tables' => array('tl_asc_redirect'),
		'icon'   => '/system/modules/redirect_manager/assets/redirect.svg'
	)
));


// Front end modules
$GLOBALS['FE_MOD']['redirect_manager'] = array('redirect_404' => 'Asc\Module\Redirect404');

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_asc_redirect'] = 'Asc\Model\Redirect';


/**
 * Hooks
 */
//$GLOBALS['TL_HOOKS']['getPageIdFromUrl'][] = array('Asc\Frontend\Redirect', 'lookupRedirect');


/**
 * Styles
 */
 if (version_compare(VERSION, '4.4', '>=')) {
	$GLOBALS['TL_CSS'][] = 'system/modules/redirect_manager/assets/css/backend-contao4.css|static';
}