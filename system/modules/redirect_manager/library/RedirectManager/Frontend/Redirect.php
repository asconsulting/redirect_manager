<?php

/**
 * Redirect Manager
 *
 * Copyright (C) 2019-2022 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */



namespace RedirectManager\Frontend;

use RedirectManager\Model\Redirect as RedirectModel;

use Contao\Environment;
use Contao\Frontend as Contao_Frontend;
use Contao\Database;


/**
 * Class RedirectManager\DirectoryPage
 */
class Redirect extends Contao_Frontend {

	public function updatePublished()
	{
		Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='1' WHERE start < ? AND (stop > ? OR stop = 0)")->execute(time(), time());
		Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='' WHERE stop < ? ")->execute(time());
	}

	public function lookupRedirect($arrFragments)
    {
		echo Environment::get('request') ."<br>";
		echo Environment::get('host') ."<br>";
		var_dump($arrFragments);
		die();

        return $arrFragments;
    }
}
