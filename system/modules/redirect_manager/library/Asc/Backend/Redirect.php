<?php
 
/**
 * Redirect Manager
 *
 * Copyright (C) 2019 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */

 
namespace Asc\Backend;

use Asc\Model\Redirect as RedirectModel;
use Contao\PageModel;

class Redirect extends \Backend
{

    public function generateLabel($row, $label, $dc, $args)
    {
        $objRedirect = RedirectModel::findByPk($row['id']);
		/*
		$strLabel = '<span class="category">[' .$objRedirect->category .']</span> <span class="code">' .$objRedirect->type .'</span>: <span class="redirect">' .$objRedirect->redirect ."</span>";
		
		if ($objRedirect['target_url']) {
			$strLabel .= ' <span class="arrow">&rarr;</span> <span class="target">' .$objRedirect['target_url'] ."</span>";
 		} else if ($objRedirect['target_page']) {
			$objPage = PageModel::findByPk($objRedirect['target_page']);
			$strLabel .= ' <span class="arrow">&rarr;</span> <span class="page">' .$objPage->title ."</span>";
 		}	
		$arg[0] = $strLabel; 
        */
		return $args;
    }
	

	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(\Input::get('tid')))
		{
			$this->toggleVisibility(\Input::get('tid'), (\Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ';
	}	
	

	public function toggleVisibility($intId, $blnVisible, \DataContainer $dc=null)
	{
		$objVersions = new \Versions('tl_redirect_manager', $intId);
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
