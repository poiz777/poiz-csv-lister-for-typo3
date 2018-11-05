
plugin.tx_poizcsvlister_pzcsvlister {
  view {
    templateRootPaths.0 = EXT:poiz_csv_lister/Resources/Private/Templates/
    templateRootPaths.1 = plugin.tx_poizcsvlister_pzcsvlister.view.templateRootPath
    partialRootPaths.0 = EXT:poiz_csv_lister/Resources/Private/Partials/
    partialRootPaths.1 = plugin.tx_poizcsvlister_pzcsvlister.view.partialRootPath
    layoutRootPaths.0 = EXT:poiz_csv_lister/Resources/Private/Layouts/
    layoutRootPaths.1 = plugin.tx_poizcsvlister_pzcsvlister.view.layoutRootPath
  }
  persistence {
    storagePid = plugin.tx_poizcsvlister_pzcsvlister.persistence.storagePid
    #recursive = 1
  }
  features {
    #skipDefaultArguments = 1
  }
  mvc {
    #callDefaultActionIfActionCantBeResolved = 1
  }
}

plugin.tx_poizcsvlister._CSS_DEFAULT_STYLE (
    textarea.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    input.f3-form-error {
        background-color:#FF9F9F;
        border: 1px #FF0000 solid;
    }

    .tx-poiz-csv-lister table {
        border-collapse:separate;
        border-spacing:10px;
    }

    .tx-poiz-csv-lister table th {
        font-weight:bold;
    }

    .tx-poiz-csv-lister table td {
        vertical-align:top;
    }

    .typo3-messages .message-error {
        color:red;
    }

    .typo3-messages .message-ok {
        color:green;
    }
)

# Module configuration
module.tx_poizcsvlister_web_poizcsvlisterpzcsvlistermanager {
  persistence {
    storagePid = module.tx_poizcsvlister_pzcsvlistermanager.persistence.storagePid
  }
  view {
    templateRootPaths.0 = EXT:poiz_csv_lister/Resources/Private/Backend/Templates/
    templateRootPaths.1 = module.tx_poizcsvlister_pzcsvlistermanager.view.templateRootPath
    partialRootPaths.0 = EXT:poiz_csv_lister/Resources/Private/Backend/Partials/
    partialRootPaths.1 = module.tx_poizcsvlister_pzcsvlistermanager.view.partialRootPath
    layoutRootPaths.0 = EXT:poiz_csv_lister/Resources/Private/Backend/Layouts/
    layoutRootPaths.1 = module.tx_poizcsvlister_pzcsvlistermanager.view.layoutRootPath
  }
}
