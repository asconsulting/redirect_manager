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
		$strProtocol = (\Environment::get('ssl') ? "https" : "http");
		$redirect = false;
		$redirect_code = false;
		$objRedirect = RedirectModel::findBy('published', '1', array('order' => 'sorting'));
		if ($objRedirect) {			
			while($objRedirect->next() && !$redirect) {
				if ($objRedirect->domain == "" || $objRedirect->domain == \Environment::get('host')) {
					switch ($objRedirect->type) {
						case "regex":
							if (preg_match($objRedirect->redirect, \Environment::get('request'), $arrMatches)) {
								if ($objRedirect->target_url) {
									$redirect = $objRedirect->target_url;
									foreach ($arrMatches as $index => $match) {
										$redirect = str_replace('$'.$index, $match, $redirect);
									}
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
						
						case "directory":
							$strRedirect = trim($objRedirect->redirect, "/");
							if (substr(\Environment::get('request'), 0, count($strRedirect)) == $strRedirect && (substr(\Environment::get('request'), count($strRedirect), 1) == "/" || \Environment::get('request') == $strRedirect)) {
								if ($objRedirect->target_url) {
									$strTarget = trim($objRedirect->target_url, "/");
									$redirect = $strTarget .substr(\Environment::get('request'), count($strRedirect));
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
						
						case "domain":
							$strRedirectDomain = false;
							$strRedirectProtocol = false;
							
							if (preg_match('/http[s]?:\/\//', $objRedirect->redirect, $arrProtocol) { 
								if (substr($objRedirect->redirect, 0, count($strProtocol)) == $strProtocol) {
									preg_match('/(http[s]?):\/\/([a-z0-9-\.]{4,})\/?/gi', $objRedirect->redirect, $arrUrl);
									if ($arrUrl[2]) {
										$strRedirectDomain = $arrUrl[2];
										$strRedirectProtocol = $arrUrl[1];
									}
								}
							} else {
								$arrUrl = explode('/', $objRedirect->redirect);
								if (preg_match('/([a-z0-9-\.]{4,})\/?/gi', $arrUrl[0])) {
									$strRedirectDomain = $arrUrl[0];
									$strRedirectProtocol = $strProtocol;
								}
							}
							
							if ($objRedirect->target_url) {
								if (preg_match('/http[s]?:\/\//', $objRedirect->target_url, $arrProtocol) { 
									if (substr($objRedirect->target_url, 0, count($strProtocol)) == $strProtocol) {
										preg_match('/(http[s]?):\/\/([a-z0-9-\.]{4,})\/?/gi', $objRedirect->target_url, $arrUrl);
										if ($arrUrl[2]) {
											$strTargetDomain = $arrUrl[2];
											$strTargetProtocol = $arrUrl[1];
										}
									}
								} else {
									$arrUrl = explode('/', $objRedirect->target_url);
									if (preg_match('/([a-z0-9-\.]{4,})\/?/gi', $arrUrl[0])) {
										$strTargetDomain = $arrUrl[0];
										$strTargetProtocol = $strProtocol;
									}
								}
							}
							
							if ($strRedirectDomain == \Environment::get('host')) {
								if ($objRedirect->target_url) {
									if ($strTargetProtocol != $strRedirectProtocol && $strTargetDomain != $strRedirectDomain) {
										$redirect = $strTargetProtocol .'://' .$strTargetDomain .'/' .\Environment::get('request');
										$redirect_code = $objRedirect->code;
									}
								} else {
									$objPage = \PageModel::findByPk($objRedirect->target_page);
									if ($objPage) {
										$redirect = $objPage->getFrontendUrl();
										$redirect_code = $objRedirect->code;
									}
								}
							}
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
				die($redirect);
				//\Controller::redirect($redirect, ($redirect_code ? $redirect_code : NULL));
			}
			
			$this->Template->redirect = $strRedirect;
		}
		
		return;
    }

}