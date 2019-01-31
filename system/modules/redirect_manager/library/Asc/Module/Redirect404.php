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
		$redirect = false;
		$redirect_code = false;
		$objRedirect = RedirectModel::findBy('published', '1', array('order' => 'sorting'));
		if ($objRedirect) {			
			while($objRedirect->next() && !$redirect) {
				if ($objRedirect->domain == "" || $objRedirect->domain == \Environment::get('host')) {
					switch ($objRedirect->type) {
						case "regex":
						
						break;
						
						case "directory":
						
						break;
						
						case "domain":
							
						break;
						
						default:
							if (\Environment::get('request') == $objRedirect->redirect) {
								if ($objRedirect->target_url) {
									$redirect = $objRedirect->target_url;
									$redirect_code = $objRedirect->code;
								} else {
									$objPage = \PageModel::findByPk($objRedirect->target_page);
									if ($objPage) {
										$redirect = $objPage->getFrontendUrl();
										$redirect_code = $objRedirect->code;
									}
								}
							}
						break;
					}
				}
			}
			
			if ($redirect) {
				\Controller::redirect($redirect, ($redirect_code ? $redirect_code : NULL));
			}
			
			$this->Template->redirect = $strRedirect;
		}
		
		return;
    }

}