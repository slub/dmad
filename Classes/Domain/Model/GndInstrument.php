<?php
namespace Slub\DmNorm\Domain\Model;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \Slub\DmNorm\Domain\Repository\GndInstrumentRepository;

/***
 *
 * This file is part of the "Publisher Database" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Matthias Richter <matthias.richter@slub-dresden.de>, SLUB Dresden
 *
 ***/
/**
 * GndInstrument
 */
class GndInstrument extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     * 
     * @var string
     */
    protected $name = '';

    /**
     * displayAs
     * 
     * @var string
     */
    protected $displayAs = '';

    /**
     * gndId
     * 
     * @var string
     */
    protected $gndId = '';

    /**
     * superGndInstrument
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SLUB\DmNorm\Domain\Model\GndInstrument>
     */
    protected $superGndInstrument = null;

    /**
     * __construct
     */
    public function __construct()
    {

        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     * 
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->superGndInstrument = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the name
     * 
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the displayAs
     * 
     * @return string $displayAs
     */
    public function getDisplayAs()
    {
        return $this->displayAs;
    }

    /**
     * Returns the gndId
     * 
     * @return string $gndId
     */
    public function getGndId()
    {
        return $this->gndId;
    }

    /**
     * Pulls information from GND
     * 
     * @return void
     */
    public function pullGndInfo()
    {

        // get instrument repository to set superInstrument
        $repo = GeneralUtility::makeInstance(GndInstrumentRepository::class);
        $url = 'http://sdvlodpro.slub-dresden.de:9200/gnd_marc21/_doc/' . $this->gndId . '/_source';
        $headers = @get_headers($url);
        if (!$headers || $headers[0] == 'HTTP/1.0 404 Not Found' || $headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        $instrumentArray = json_decode(file_get_contents($url), true);
        $instrumentArray = \SLUB\DmNorm\Lib\GndLib::flattenDataSet($instrumentArray);
        $this->name = $instrumentArray[150][0]['a'];

        // does superInstrument exist?
        $superId = false;
        if (isset($formArray[550])) {
            foreach ($instrumentArray[550] as $field) {
                if ($field['i'] == 'Oberbegriff allgemein') {

                    // find superInstrument's gndId
                    foreach ($field as $cell) {
                        if (strpos($cell, 'd-nb.info/gnd')) {
                            $superId = str_replace('https://d-nb.info/gnd/', '', $cell);
                        }
                    }
                }
            }
        }
        if ($superId) {
            if ($repo->findOneByGndId($superId)) {
                $this->superInstrument = $repo->findOneByGndId($superId);
                $this->superInstrument->pullGndInfo();
                $repo->update($this->superInstrument);
            } else {
                $this->superInstrument = GeneralUtility::makeInstance(self::class);
                $this->superInstrument->gndId = $superId;
                $this->superInstrument->pullGndInfo();
                $repo->add($this->superInstrument);
            }
        }
        //$this->displayAs = $this->superInstrument == null ? $this->name : $this->name . ' (' . $this->superInstrument->displayAs . ')';
    }

    /**
     * Returns the superInstrument
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SLUB\DmNorm\Domain\Model\Instrument> $superInstrument
     */
    public function getSuperInstrument()
    {
        return $this->superInstrument;
    }
}
