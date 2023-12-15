<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson',
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
        'searchFields' => 'gnd_id,name,geographic_area_code,gender,gnd_status',
        'iconfile' => 'EXT:dmnorm/Resources/Public/Icons/tx_dmnorm_domain_model_gndperson.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, gnd_id, name, date_of_birth, date_of_death, geographic_area_code, gender, works, place_of_birth, place_of_death, place_of_activity',
    ],
    'types' => [
        '1' => ['showitem' => 'gnd_id, name, date_of_birth, date_of_death, geographic_area_code, gender, unmodified_gnd_data, gnd_status, final, works, place_of_birth, place_of_death, place_of_activity, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
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
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_dmnorm_domain_model_gndperson',
                'foreign_table_where' => 'AND {#tx_dmnorm_domain_model_gndperson}.{#pid}=###CURRENT_PID### AND {#tx_dmnorm_domain_model_gndperson}.{#sys_language_uid} IN (-1,0)',
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
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.gnd_id',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'date_of_birth' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.date_of_birth',
            'config' => [
                'dbType' => 'date',
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 7,
                'eval' => 'date',
                'default' => null,
            ],
        ],
        'date_of_death' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.date_of_death',
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
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.geographic_area_code',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'gender' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.gender',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'works' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.works',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_dmnorm_domain_model_work',
                'foreign_field' => 'firstcomposer',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1
                ],
            ],

        ],
        'place_of_birth' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.place_of_birth',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_dmnorm_domain_model_gndplace',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
        'place_of_death' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.place_of_death',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_dmnorm_domain_model_gndplace',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
        'place_of_activity' => [
            'exclude' => true,
            'label' => 'LLL:EXT:dmnorm/Resources/Private/Language/locallang_db.xlf:tx_dmnorm_domain_model_gndperson.place_of_activity',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_dmnorm_domain_model_gndplace',
                'MM' => 'tx_dmnorm_gndperson_placeofactivity_gndplace_mm',
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
    
    ],
];
