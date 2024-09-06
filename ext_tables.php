<?php

defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardpages('tx_dmnorm_domain_model_gndgenre');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardpages('tx_dmnorm_domain_model_gndinstrument');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardpages('tx_dmnorm_domain_model_gndperson');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardpages('tx_dmnorm_domain_model_gndwork');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardpages('tx_dmnorm_domain_model_gndplace');
