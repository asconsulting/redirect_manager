<?php

/**
 * Redirect Manager
 *
 * Copyright (C) 2019-2026 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */



namespace RedirectManager\EventListener\DataContainer;


use RedirectManager\Model\Redirect as RedirectModel;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\FilesModel;
use Contao\PageModel;
use Symfony\Contracts\Translation\TranslatorInterface;


#[AsCallback(table: 'tl_asc_redirect', target: 'list.label.label')]
class RedirectLabelCallbackListener
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    
    public function __invoke(array $row, string $label, DataContainer $dc, array $labels): array
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
		$labels[0] = $strLabel;

		return $strLabel;
    }
}
