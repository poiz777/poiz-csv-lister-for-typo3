<?php
return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poizcsvlister_domain_model_pzcsvrender',
        'label' => 'file_id',
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
		'searchFields' => 'file_id,payload,data',
        'iconfile' => 'EXT:poiz_csv_lister/Resources/Public/Icons/poiz_csv_lister_icon.svg'
    ],
    'interface' => [
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, file_id, payload, data',
    ],
    'types' => [
		'1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, file_id, payload, data'],
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
                'foreign_table' => 'tx_poizcsvlister_domain_model_pzcsvrender',
                'foreign_table_where' => 'AND tx_poizcsvlister_domain_model_pzcsvrender.pid=###CURRENT_PID### AND tx_poizcsvlister_domain_model_pzcsvrender.sys_language_uid IN (-1,0)',
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
        'file_id' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poizcsvlister_domain_model_pzcsvrender.file_id',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int,required'
			]
	    ],
	    'payload' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poizcsvlister_domain_model_pzcsvrender.payload',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'data' => [
	        'exclude' => false,
	        'label' => 'LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poizcsvlister_domain_model_pzcsvrender.data',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
    ],
];
