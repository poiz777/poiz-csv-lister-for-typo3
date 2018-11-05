<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'POiz.PoizCsvLister',
            'Pzcsvlister',
            'Pz CSV Lister'
        );

        if (TYPO3_MODE === 'BE') {

            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'POiz.PoizCsvLister',
                'web',
                'pzcsvlistermanager',
                '',
                [
                    'PzCSVListerManager' => 'build,manage,delete,preview,viewList,updateForm'
                ],
                [
                    'access' => 'user,group',
					'icon'   => 'EXT:' . $extKey . '/Resources/Public/Icons/poiz_csv_lister_icon.svg',
                    'labels' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_pzcsvlistermanager.xlf',
                ]
            );

        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'Poiz CSV Lister');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_poizcsvlister_domain_model_pzcsvlistermanager', 'EXT:poiz_csv_lister/Resources/Private/Language/locallang_csh_tx_poizcsvlister_domain_model_pzcsvlistermanager.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_poizcsvlister_domain_model_pzcsvlistermanager');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_poizcsvlister_domain_model_pzcsvlister', 'EXT:poiz_csv_lister/Resources/Private/Language/locallang_csh_tx_poizcsvlister_domain_model_pzcsvlister.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_poizcsvlister_domain_model_pzcsvlister');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_poizcsvlister_domain_model_pzcsvfile', 'EXT:poiz_csv_lister/Resources/Private/Language/locallang_csh_tx_poizcsvlister_domain_model_pzcsvfile.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_poizcsvlister_domain_model_pzcsvfile');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_poizcsvlister_domain_model_pzcsvrender', 'EXT:poiz_csv_lister/Resources/Private/Language/locallang_csh_tx_poizcsvlister_domain_model_pzcsvrender.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_poizcsvlister_domain_model_pzcsvrender');

    },
    $_EXTKEY
);

$pluginSignature                                                            = 'poizcsvlister_pzcsvlister';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature]   = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue( $pluginSignature, 'FILE:EXT:poiz_csv_lister/Configuration/FlexForms/flexform_poiz_csv_lister.xml');