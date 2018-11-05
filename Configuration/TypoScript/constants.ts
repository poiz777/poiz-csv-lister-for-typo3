
plugin.tx_poizcsvlister_pzcsvlister {
  view {
    # cat=plugin.tx_poizcsvlister_pzcsvlister/file; type=string; label=Path to template root (FE)
    templateRootPath = EXT:poiz_csv_lister/Resources/Private/Templates/
    # cat=plugin.tx_poizcsvlister_pzcsvlister/file; type=string; label=Path to template partials (FE)
    partialRootPath = EXT:poiz_csv_lister/Resources/Private/Partials/
    # cat=plugin.tx_poizcsvlister_pzcsvlister/file; type=string; label=Path to template layouts (FE)
    layoutRootPath = EXT:poiz_csv_lister/Resources/Private/Layouts/
  }
  persistence {
    # cat=plugin.tx_poizcsvlister_pzcsvlister//a; type=string; label=Default storage PID
    storagePid =
  }
}

module.tx_poizcsvlister_pzcsvlistermanager {
  view {
    # cat=module.tx_poizcsvlister_pzcsvlistermanager/file; type=string; label=Path to template root (BE)
    templateRootPath = EXT:poiz_csv_lister/Resources/Private/Backend/Templates/
    # cat=module.tx_poizcsvlister_pzcsvlistermanager/file; type=string; label=Path to template partials (BE)
    partialRootPath = EXT:poiz_csv_lister/Resources/Private/Backend/Partials/
    # cat=module.tx_poizcsvlister_pzcsvlistermanager/file; type=string; label=Path to template layouts (BE)
    layoutRootPath = EXT:poiz_csv_lister/Resources/Private/Backend/Layouts/
  }
  persistence {
    # cat=module.tx_poizcsvlister_pzcsvlistermanager//a; type=string; label=Default storage PID
    storagePid =
  }
}
