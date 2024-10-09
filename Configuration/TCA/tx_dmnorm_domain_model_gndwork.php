<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndwork',
        'label' => 'gnd_id',
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
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'gnd_id,generic_title,individual_title,geographic_area_code,opus_no,index_no,gnd_status,tonality,title_no,title_instrument,alt_titles,language,instrument_ids,alt_instrument_names,genre_ids,title',
        'iconfile' => 'EXT:dm_norm/Resources/Public/Icons/tx_dmnorm_domain_model_gndwork.gif'
    ],
    'interface' => [
    ],
    'types' => [
        '1' => ['showitem' => 'gnd_id, generic_title, individual_title, date_of_production, geographic_area_code, geographical_area_code, opus_no, index_no, medium_of_performance, unmodified_gnd_data, gnd_status, tonality, title_no, title_instrument, alt_titles, language, final, instrument_ids, alt_instrument_names, confirmed_by_slub, genre_ids, title, intertextual_entity, super_work, instruments, gnd_genres, genre, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'gnd_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang.xlf:gnd_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'individual_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:individualTitle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'generic_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:genericTitle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'date_of_production' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:dateOfProduction',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
            ],
        ],
        'geographic_area_code' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:geographicAreaCode',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'opus_no' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:opusNo',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'index_no' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:indexNo',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'medium_of_performance' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:mediumOfPerformance',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'publishers' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:publishers',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'tonality' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:tonality',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'title_no' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:titleNo',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'title_instrument' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:titleInstrument',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'alt_titles' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:altTitles',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'language' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:language',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'instrument_ids' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:instrumentIds',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'alt_instrument_names' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:altInstrumentNames',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'genre_ids' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:genreIds',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'full_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:fullTitle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'intertextual_entity' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:intertextualEntity',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_dmnorm_domain_model_gndwork',
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
        'super_work' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:superWork',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_dmnorm_domain_model_gndwork',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
        'instruments' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:instruments',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_dmnorm_domain_model_gndinstrument',
                'MM' => 'tx_dmnorm_gndwork_gndinstrument_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
            
        ],
        'gnd_genres' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:form',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_dmnorm_domain_model_gndgenre',
                'MM' => 'tx_dmnorm_gndwork_gndgenre_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
        'firstcomposer' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dm_norm/Resources/Private/Language/locallang_csh_work.xlf:firstComposer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_dmnorm_domain_model_gndperson',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ]
        ]
    ]
];
