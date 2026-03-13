<?php

/**
 * Redirect Manager
 *
 * Copyright (C) 2019-2026 Andrew Stevens Consulting
 *
 * @package    asconsulting/redirect_manager
 * @link       https://andrewstevens.consulting
 */



/**
 * Table tl_asc_redirect
 */
$GLOBALS['TL_DCA']['tl_asc_redirect'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode' 					=> 1,
            'fields' 				=> array('sorting'),
            'flag' 					=> 1,
            'panelLayout' 			=> 'filter;search,limit'
        ),
        'label' => array
        (
            'fields' 				=> array('rule'),
            'format' 				=> '%s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label' 			=> &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' 				=> 'act=select',
                'class' 			=> 'header_edit_all',
                'attributes' 		=> 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit',
            'copy',
            'delete',
            'toggle',
			'show'
        )
    ),

    // Palettes
    'palettes' => array
    (
		'__selector__'                => array('type'),
        'default'                     => '{config_legend},type,category',
		'regular'					  => '{config_legend},type,category;{redirect_legend},code,redirect,target_page,target_file,target_url;{domain_legend},domain;{publish_legend},published,start,stop',
		'regex'					 	  => '{config_legend},type,category;{redirect_legend},code,redirect,target_page,target_file,target_url;{domain_legend},domain;{publish_legend},published,start,stop',
		'directory'					  => '{config_legend},type,category;{redirect_legend},code,redirect,target_page,target_file,target_url;{domain_legend},domain;{publish_legend},published,start,stop',
		'domain'					  => '{config_legend},type,category;{redirect_legend},code,redirect_domain,target_domain;{publish_legend},published,start,stop'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
		'sorting' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
		'category' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['category'],
            'search'                  => true,
			'filter'				  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>128, 'tl_class'=>'w50'),
            'sql'                     => "varchar(128) NOT NULL default ''"
        ),
		'type' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['type'],
			'default'                 => 'regular',
			'filter'				  => true,
			'inputType'               => 'select',
			'options'                 => array('regular' => 'Regular', 'regex' => 'Regular Expression', 'directory' => 'Directory', 'domain' => 'Domain'),
			'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'clr w50', 'includeBlankOption'=>true),
			'sql'                     => "varchar(32) NOT NULL default ''"
        ),
		'code' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['code'],
			'filter'				  => true,
			'inputType'               => 'select',
			'options'                 => array('301' => '301 - Moved Permanently', '302' => '302 - Found', '303' => '303 - See Other', '307' => '307 - Temporary Redirect', '418' => "418 - I'm a Teapot"),
			'eval'                    => array('tl_class'=>'clr w50'),
			'sql'                     => "varchar(4) NOT NULL default ''"
        ),
		'redirect_domain' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['redirect_domain'],
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'clr w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
		'target_domain' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['target_domain'],
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
		'redirect' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['redirect'],
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
		'target_page' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['target_page'],
			'inputType'               => 'pageTree',
			'foreignKey'              => 'tl_page.title',
			'eval'                    => array('fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
		),
		'target_file' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['target_file'],
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr'),
			'sql'                     => "binary(16) NULL"
		),
		'target_url' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['target_url'],
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('decodeEntities'=>false, 'maxlength'=>255, 'tl_class'=>'clr w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
		'domain' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['domain'],
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'clr w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),

		// Publish Fields
		'published' => array
		(
			'exclude'                 => true,
			'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['published'],
			'inputType'               => 'checkbox',
			'filter'                  => true,
			'toggle' 				  => true,
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'start' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['start'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		),
		'stop' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_asc_redirect']['stop'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
			'sql'                     => "varchar(10) NOT NULL default ''"
		)
    )
);
