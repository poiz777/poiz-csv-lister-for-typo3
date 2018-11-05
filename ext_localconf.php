<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
	{

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'POiz.PoizCsvLister',
            'Pzcsvlister',
            [
                'PzCSVLister' => 'render, viewList'
            ],
            // non-cacheable actions
            [
                'PzCSVLister' => 'render, viewList'
            ]
        );

	// wizards
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
		'mod {
			wizards.newContentElement.wizardItems.plugins {
				elements {
					pzcsvlister {
						icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey) . 'Resources/Public/Icons/user_plugin_pzcsvlister.svg
						title = LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poiz_csv_lister_domain_model_pzcsvlister
						description = LLL:EXT:poiz_csv_lister/Resources/Private/Language/locallang_db.xlf:tx_poiz_csv_lister_domain_model_pzcsvlister.description
						tt_content_defValues {
							CType = list
							list_type = poizcsvlister_pzcsvlister
						}
					}
				}
				show = *
			}
	   }'
	);
    },
    $_EXTKEY
);
