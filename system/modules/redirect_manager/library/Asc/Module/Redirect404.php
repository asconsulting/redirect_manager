<?php
 
/**
 * Redirect Manager
 *
 * Copyright (C) 2019 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */

 
namespace Asc\Module;
 
use Asc\Model\Redirect as RedirectModel;
 
/**
 * Class Asc\Module\DirectoryReader
 */
class Redirect404 extends \Contao\Module
{
 
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_html';

 
    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new \BackendTemplate('be_wildcard');
 
            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['dir_list'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&table=tl_module&act=edit&id=' . $this->id;
 
            return $objTemplate->parse();
        }
 
        return parent::generate();
    }
	
	 
    /**
     * Generate the module
     */
    protected function compile()
    {	
		
		$objRedirect = RedirectModel::findBy('published', '1', array('order' => 'sorting'));
		if ($objRedirect) {			
			echo \Environment::get('request') ."<br>";
			echo \Environment::get('host') ."<br>";
			die();
			$this->Template->redirect = $strRedirect;
		}
		
			echo \Environment::get('request') ."<br>";
			echo \Environment::get('host') ."<br>";
			die();
			$this->Template->redirect = $strRedirect;
		
		return;
    }

}