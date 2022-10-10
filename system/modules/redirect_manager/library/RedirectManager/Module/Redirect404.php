<?php

/**
 * Redirect Manager
 *
 * Copyright (C) 2019-2022 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */



namespace RedirectManager\Module;

use RedirectManager\Model\Redirect as RedirectModel;

use Contao\BackendTemplate;
use Contao\Controller;
use Contao\Database;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Module as Contao_Module;
use Contao\PageModel;


/**
 * Class RedirectManager\Module\DirectoryReader
 */
class Redirect404 extends Contao_Module
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_redirect_manager';


    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            $objTemplate = new BackendTemplate('be_wildcard');

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

		Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='1' WHERE IF(start = '', 0, CONVERT(start, UNSIGNED)) < ? AND IF(start = '', 0, CONVERT(start, UNSIGNED)) > 0 AND (IF(stop = '', 0, CONVERT(stop, UNSIGNED)) > ? OR IF(stop = '', 0, CONVERT(stop, UNSIGNED)) = 0)")->execute(time(), time());
		Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='' WHERE IF(stop = '', 0, CONVERT(stop, UNSIGNED)) < ? AND IF(stop = '', 0, CONVERT(stop, UNSIGNED)) > 0")->execute(time());

		$strProtocol = (Environment::get('ssl') ? "https" : "http");
		$redirect = false;
		$redirect_code = false;
		$objRedirect = RedirectModel::findBy('published', '1', array('order' => 'sorting'));
		if ($objRedirect) {
			while($objRedirect->next() && !$redirect) {
				if ($objRedirect->domain == "" || $objRedirect->domain == Environment::get('host')) {

					switch ($objRedirect->type) {
						case "regex":
							if (preg_match($objRedirect->redirect, Environment::get('request'), $arrMatches)) {
								if ($objRedirect->target_url) {
									$redirect = $objRedirect->target_url;
									foreach ($arrMatches as $index => $match) {
										$redirect = str_replace('$'.$index, $match, $redirect);
									}
									$redirect_code = $objRedirect->code;
								} else {
									$objPage = PageModel::findByPk($objRedirect->target_page);
									if ($objPage) {
										$redirect = $objPage->getFrontendUrl();
										$redirect_code = $objRedirect->code;
									}
								}
							}
						break;

						case "directory":
							$strRedirect = trim($objRedirect->redirect, "/");
							if (substr(Environment::get('request'), 0, strlen($strRedirect)) == $strRedirect && (substr(Environment::get('request'), strlen($strRedirect), 1) == "/" || Environment::get('request') == ltrim($objRedirect->redirect, "/"))) {
								if ($objRedirect->target_url) {
									$strTarget = trim($objRedirect->target_url, "/");
									$redirect = $strTarget .substr(Environment::get('request'), strlen($strRedirect));
									$redirect_code = $objRedirect->code;
								} else {
									$objPage = PageModel::findByPk($objRedirect->target_page);
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

							if (preg_match('/http[s]?:\/\//i', $objRedirect->redirect_domain)) {
								preg_match_all('/(http[s]?):\/\/([a-z0-9-\.]{4,})\/?/i', $objRedirect->redirect_domain, $arrUrl);
								if ($arrUrl[2]) {
									$strRedirectDomain = $arrUrl[2][0];
									$strRedirectProtocol = $arrUrl[1][0];
								}
							} else {
								$arrUrl = explode('/', $objRedirect->redirect_domain);
								if (preg_match('/[a-z0-9-\.]{4,}\/?/i', $arrUrl[0])) {
									$strRedirectDomain = $arrUrl[0];
									$strRedirectProtocol = $strProtocol;
								}
							}

							if ($objRedirect->target_domain != "") {
								if (preg_match('/http[s]?:\/\//i', $objRedirect->target_domain)) {
									preg_match_all('/(http[s]?):\/\/([a-z0-9-\.]{4,})\/?/i', $objRedirect->target_domain, $arrUrl);
									if ($arrUrl[2]) {
										$strTargetDomain = $arrUrl[2][0];
										$strTargetProtocol = $arrUrl[1][0];
									}
								} else {
									$arrUrl = explode('/', $objRedirect->target_domain);
									if (preg_match('/([a-z0-9-\.]{4,})\/?/i', $arrUrl[0])) {
										$strTargetDomain = $arrUrl[0];
										$strTargetProtocol = $strProtocol;
									}
								}
							}

							if ($strRedirectDomain == Environment::get('host')) {
								if ($objRedirect->target_domain != "") {
									if ($strTargetProtocol != $strRedirectProtocol || $strTargetDomain != $strRedirectDomain) {
										$redirect = $strTargetProtocol .'://' .$strTargetDomain .'/' .Environment::get('request');
										$redirect_code = $objRedirect->code;
									}
								} else {
									$objPage = PageModel::findByPk($objRedirect->target_page);
									if ($objPage) {
										$redirect = $objPage->getFrontendUrl();
										$redirect_code = $objRedirect->code;
									}
								}
							}
						break;

						default:
							if (Environment::get('request') == $objRedirect->redirect) {
								if ($objRedirect->target_url) {
									$redirect = $objRedirect->target_url;
									$redirect_code = $objRedirect->code;
								} else if ($objRedirect->target_page) {
									$objPage = PageModel::findByPk($objRedirect->target_page);
									if ($objPage) {
										$redirect = $objPage->getFrontendUrl();
										$redirect_code = $objRedirect->code;
									}
								} else if ($objRedirect->target_file) {
									$objFile = FilesModel::findByUuid($objRedirect->target_file);
									if ($objFile) {
										$redirect = Environment::get('base') .'/' .$objFile->path;
										$redirect_code = $objRedirect->code;
									}
								}
								
								
								
			
							}
						break;
					}
				}
			}

			if ($redirect) {
				Controller::redirect($redirect, ($redirect_code ? $redirect_code : NULL));
			}

			$this->Template->redirect = $redirect;
		}

		return;
    }

}
