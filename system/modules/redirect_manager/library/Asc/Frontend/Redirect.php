<?php
 
/**
 * Redirect Manager
 *
 * Copyright (C) 2019 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */

 
namespace Asc\Frontend;

use Asc\Model\Redirect as RedirectModel;

/**
 * Class Asc\DirectoryPage
 */
class Redirect {
	
	public function lookupRedirect($arrFragments)
    {
		echo \Environment::get('request') ."<br>";
		echo \Environment::get('host') ."<br>";
		var_dump($arrFragments);
		die();
		
		//\Controller::redirect();
		
        return $arrFragments;
    }
}