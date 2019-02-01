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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_asc_redirect']['category'] 			= array('Category', 'Arbitrary text description. Searchable and filterable.');
$GLOBALS['TL_LANG']['tl_asc_redirect']['type'] 				= array('Type', 'Type of Redirect');
$GLOBALS['TL_LANG']['tl_asc_redirect']['code'] 				= array('Code', 'HTTP Status code. <a href="https://en.wikipedia.org/wiki/List_of_HTTP_status_codes" target="_blank">List of Status Codes</a>');
$GLOBALS['TL_LANG']['tl_asc_redirect']['redirect'] 			= array('Redirect', 'Enter the old URL or patern to match.');
$GLOBALS['TL_LANG']['tl_asc_redirect']['redirect_domain'] 	= array('Redirect Domain', 'Enter the old domain.');
$GLOBALS['TL_LANG']['tl_asc_redirect']['target_domain'] 	= array('Target Domain', 'Enter the new/target domain.');
$GLOBALS['TL_LANG']['tl_asc_redirect']['target_page'] 		= array('Target Page', 'Please enter the title.');
$GLOBALS['TL_LANG']['tl_asc_redirect']['target_url'] 		= array('Target URL', 'Enter redirect target. Overrides Target Page attribute. Use $1 format for regex matches.');
$GLOBALS['TL_LANG']['tl_asc_redirect']['domain'] 			= array('Domain', 'Limit this redirect to this domain only.');
$GLOBALS['TL_LANG']['tl_asc_redirect']['published'] 		= array('Publish', 'Enable/Disable this redirect.');
$GLOBALS['TL_LANG']['tl_asc_redirect']['start'] 			= array('Enable from', 'Enable this redirect on this date.');
$GLOBALS['TL_LANG']['tl_asc_redirect']['stop'] 				= array('Disable after', 'Disable this redirect on this date.');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_asc_redirect']['config_legend'] 	= 'Redirect details';
$GLOBALS['TL_LANG']['tl_asc_redirect']['redirect_legend'] 	= 'Redirect configuration';
$GLOBALS['TL_LANG']['tl_asc_redirect']['domain_legend'] 	= 'Domain restrictions';
$GLOBALS['TL_LANG']['tl_asc_redirect']['publish_legend'] 	= 'Publish';
 
 
/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_asc_redirect']['new']    			= array('New redirect', 'Add a new redirect');
$GLOBALS['TL_LANG']['tl_asc_redirect']['show']   			= array('Redirect details', 'Show the details of redirect ID %s');
$GLOBALS['TL_LANG']['tl_asc_redirect']['edit']   			= array('Edit redirect', 'Edit redirect ID %s');
$GLOBALS['TL_LANG']['tl_asc_redirect']['copy']   			= array('Copy redirect', 'Copy redirect ID %s');
$GLOBALS['TL_LANG']['tl_asc_redirect']['delete'] 			= array('Delete redirect', 'Delete redirect ID %s');
$GLOBALS['TL_LANG']['tl_asc_redirect']['toggle'] 			= array('Toggle redirect', 'Toggle redirect ID %s');
