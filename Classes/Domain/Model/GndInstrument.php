<?php
namespace Slub\DmNorm\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Slub\DmNorm\Common\GndLib;
use Slub\DmNorm\Domain\Repository\GndInstrumentRepository;

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
     * Sets the gndId
     * 
     * @var string $gndId
     * @return GndInstrument
     */
    public function setGndId(string $gndId): GndInstrument
    {
        $this->gndId = $gndId;
        return $this;
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

        // get instrument repository to set superGndInstrument
        $repo = GeneralUtility::makeInstance(GndInstrumentRepository::class);
        $url = 'http://sdvlodpro.slub-dresden.de:9200/gnd_marc21/_doc/' . $this->gndId . '/_source';
        $headers = @get_headers($url);
        if (!$headers || $headers[0] == 'HTTP/1.0 404 Not Found' || $headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        $instrumentArray = json_decode(file_get_contents($url), true);
        $instrumentArray = GndLib::flattenDataSet($instrumentArray);
        $this->name = $instrumentArray[150][0]['a'];

        // does superGndInstrument exist?
        $superId = false;
        if (isset($formArray[550])) {
            foreach ($instrumentArray[550] as $field) {
                if ($field['i'] == 'Oberbegriff allgemein') {

                    // find superGndInstrument's gndId
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
                $this->superGndInstrument = $repo->findOneByGndId($superId);
                $this->superGndInstrument->pullGndInfo();
                $repo->update($this->superGndInstrument);
            } else {
                $this->superGndInstrument = GeneralUtility::makeInstance(self::class);
                $this->superGndInstrument->gndId = $superId;
                $this->superGndInstrument->pullGndInfo();
                $repo->add($this->superGndInstrument);
            }
        }
        //$this->displayAs = $this->superInstrument == null ? $this->name : $this->name . ' (' . $this->superInstrument->displayAs . ')';
    }

    /**
     * Returns the superGndInstrument
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SLUB\DmNorm\Domain\Model\GndInstrument> $superGndInstrument
     */
    public function getSuperGndInstrument()
    {
        return $this->superGndInstrument;
    }
}
