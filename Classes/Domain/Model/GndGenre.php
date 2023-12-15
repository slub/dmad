<?php
namespace Slub\DmNorm\Domain\Model;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \Slub\DmNorm\Domain\Repository\GndGenreRepository;

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
 * GndGenre
 */
class GndGenre extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * superGndGenre
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SLUB\PublisherDb\Domain\Model\GndGenre>
     */
    protected $superGndGenre = null;

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
        $this->superGndGenre = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * Returns the superForm
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\SLUB\PublisherDb\Domain\Model\Form> $superForm
     */
    public function getSuperForm()
    {
        return $this->superForm;
    }

    /**
     * Pulls information from GND
     * 
     * @return void
     */
    public function pullGndInfo()
    {

        // get form repository to set superForm
        $repo = GeneralUtility::makeInstance(GndGenreRepository::class);
        $url = 'http://sdvlodpro.slub-dresden.de:9200/gnd_marc21/_doc/' . $this->gndId . '/_source';
        $headers = @get_headers($url);
        if (!$headers || $headers[0] == 'HTTP/1.0 404 Not Found' || $headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        $formArray = json_decode(file_get_contents($url), true);
        $formArray = \SLUB\PublisherDb\Lib\GndLib::flattenDataSet($formArray);
        $this->name = $formArray[150][0]['a'];

        // does superForm exist?
        if (isset($formArray[550])) {
            foreach ($formArray[550] as $field) {
                if ($field['i'] == 'Oberbegriff allgemein') {

                    // find superForm's gndId
                    foreach ($field as $cell) {
                        if (strpos($cell, 'd-nb.info/gnd')) {
                            $superId = str_replace('https://d-nb.info/gnd/', '', $cell);
                            break;
                        }
                    }
                    if ($superId) {
                        if ($repo->findOneByGndId($superId)) {
                            $superForm = $repo->findOneByGndId($superId);
                            $superForm->pullGndInfo();
                            $repo->update($superForm);
                        } else {
                            $superForm = GeneralUtility::makeInstance(self::class);
                            $superForm->gndId = $superId;
                            $superForm->pullGndInfo();
                            $repo->add($superForm);
                        }
                        $this->superForm->attach($superForm);
                    }
                }
            }
        }
        $superString = '';
        foreach ($this->superForm as $singleSuperForm) {
            $superString = $superString . ' ' . $singleSuperForm->displayAs;
        }
        if ($superString) {
            $this->displayAs = $this->name . ' (' . $superString . ')';
        } else {
            $this->displayAs = $this->name;
        }
    }
}
