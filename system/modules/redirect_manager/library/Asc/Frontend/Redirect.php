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
	
	public function updatePublished()
	{
		\Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='1' WHERE start < ? AND (stop > ? OR stop = 0)")->execute(time(), time());
		\Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='' WHERE stop < ? ")->execute(time());
	}
	
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