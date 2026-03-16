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


use Contao\LayoutModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\Database;
use Contao\DataContainer;

use Symfony\Component\HttpFoundation\RequestStack;


#[AsCallback(table: 'tl_asc_redirect', target: 'config.onload')]
class RedirectOnloadCallbackListener
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
	
    public function __invoke(DataContainer $dc): void
    {
		Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='1' WHERE IF(start = '', 0, CONVERT(start, UNSIGNED)) < ? AND IF(start = '', 0, CONVERT(start, UNSIGNED)) > 0 AND (IF(stop = '', 0, CONVERT(stop, UNSIGNED)) > ? OR IF(stop = '', 0, CONVERT(stop, UNSIGNED)) = 0)")->execute(time(), time());
		Database::getInstance()->prepare("UPDATE tl_asc_redirect SET published='' WHERE IF(stop = '', 0, CONVERT(stop, UNSIGNED)) < ? AND IF(stop = '', 0, CONVERT(stop, UNSIGNED)) > 0")->execute(time());
		
    }

}
