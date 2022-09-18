<?php

/**
 * Redirect Manager
 *
 * Copyright (C) 2019-2022 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */



namespace RedirectManager\Backend;

use RedirectManager\Model\Redirect as RedirectModel;

use Contao\Backend as Contao_Backend;
use Contao\Database;
use Contao\Datacontainer;
use Contao\FilesModel;
use Contao\Image;
use Contao\Input;
use Contao\PageModel;
use Contao\Versions;


class Redirect extends Contao_Backend
{

	public function updatePublished()
	{
		Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='1' WHERE IF(start = '', 0, CONVERT(start, UNSIGNED)) < ? AND IF(start = '', 0, CONVERT(start, UNSIGNED)) > 0 AND (IF(stop = '', 0, CONVERT(stop, UNSIGNED)) > ? OR IF(stop = '', 0, CONVERT(stop, UNSIGNED)) = 0)")->execute(time(), time());
		Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='' WHERE IF(stop = '', 0, CONVERT(stop, UNSIGNED)) < ? AND IF(stop = '', 0, CONVERT(stop, UNSIGNED)) > 0")->execute(time());
	}

    public function generateLabel($row, $label, $dc, $args)
    {
        $objRedirect = RedirectModel::findByPk($row['id']);

		$strLabel = '<span class="category">[' .$objRedirect->category .']</span> <span class="code">' .$objRedirect->type .'</span>: <span class="redirect">' .$objRedirect->redirect ."</span>";

		if ($objRedirect->target_url) {
			$strLabel .= ' <span class="arrow">&rarr;</span> <span class="target">' .$objRedirect->target_url ."</span>";
 		} else if ($objRedirect->target_page) {
			$objPage = PageModel::findByPk($objRedirect->target_page);
			if ($objPage) {
				$strLabel .= ' <span class="arrow">&rarr;</span> <span class="page">' .$objPage->title ."</span>";
			}
 		} else if ($objRedirect->target_file) {
			$objFile = FilesModel::findByUuid($objRedirect->target_file);
			if ($objFile) {
				$strLabel .= ' <span class="arrow">&rarr;</span> <span class="file">' .$objFile->path ."</span>";
			}
 		}
		$arg[0] = $strLabel;

		return $strLabel;
    }


	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		$objVersions = new Versions('tl_redirect_manager', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_redirect_manager']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_redirect_manager']['fields']['published']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_redirect_manager SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_redirect_manager.id='.$intId.'" has been created'.$this->getParentEntries('tl_redirect_manager', $intId), __METHOD__, TL_GENERAL);
	}

}
