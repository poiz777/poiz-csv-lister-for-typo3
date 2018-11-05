<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poizcsvlister_domain_model_pzcsvfile',
        'label' => 'file_path',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
		'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => [
            'disabled' => 'hidden',
        ],
		'searchFields' => 'file_path,file_name,list_render',
        'iconfile' => 'EXT:poiz_csv_lister/Resources/Public/Icons/poiz_csv_lister_icon.svg'
    ],
    'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, file_path, list_render',
    ],
    'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, file_path, file_name, list_render'],
    ],
    'columns' => [
		'sys_language_uid' => [
			'exclude' => true,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => [
				'type' => 'select',
				'renderType' => 'selectSingle',
				'special' => 'languages',
				'items' => [
					[
						'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
						-1,
						'flags-multiple'
					]
				],
				'default' => 0,
			],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_poizcsvlister_domain_model_pzcsvfile',
                'foreign_table_where' => 'AND tx_poizcsvlister_domain_model_pzcsvfile.pid=###CURRENT_PID### AND tx_poizcsvlister_domain_model_pzcsvfile.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
		't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
		'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'file_name' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poizcsvlister_domain_model_pzcsvfile.file_name',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
        'file_path' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poizcsvlister_domain_model_pzcsvfile.file_path',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'list_render' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poizcsvlister_domain_model_pzcsvfile.list_render',
	        'config' => [
			    'type' => 'inline',
			    'foreign_table' => 'tx_poizcsvlister_domain_model_pzcsvrender',
			    'minitems' => 0,
			    'maxitems' => 1,
			    'appearance' => [
			        'collapseAll' => 0,
			        'levelLinksPosition' => 'top',
			        'showSynchronizationLink' => 1,
			        'showPossibleLocalizationRecords' => 1,
			        'showAllLocalizationLink' => 1
			    ],
			],
	    ],
    ],
];
