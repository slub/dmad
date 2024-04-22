<?php
namespace Slub\DmNorm\Domain\Model;

use Slub\DmNorm\Common\GndLib;

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
 * GndPerson
 */
class GndPerson extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * works
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\DmNorm\Domain\Model\GndWork> $works
     */
    protected $works = null;

    /**
     * gndId
     * 
     * @var string
     */
    protected $gndId = '';

    /**
     * name
     * 
     * @var string
     */
    protected $name = '';

    /**
     * dateOfBirth
     * 
     * @var \DateTime
     */
    protected $dateOfBirth = null;

    /**
     * dateOfDeath
     * 
     * @var \DateTime
     */
    protected $dateOfDeath = null;

    /**
     * placeOfBirth
     * 
     * @var string
     */
    protected $placeOfBirth = '';

    /**
     * placeOfDeath
     * 
     * @var string
     */
    protected $placeOfDeath = '';

    /**
     * placeOfActivity
     * 
     * @var string
     */
    protected $placeOfActivity = '';

    /**
     * geographicAreaCode
     * 
     * @var string
     */
    protected $geographicAreaCode = '';

    /**
     * gender
     * 
     * @var string
     */
    protected $gender = '';

    /**
     * gndStatus
     * 
     * @var string
     */
    protected $gndStatus = '';

    /**
     * @param \Slub\DmNorm\Domain\Repository\GndWorkRepository $workRepository
     */
    public function injectWorkRepository(\Slub\DmNorm\Domain\Repository\GndWorkRepository $workRepository): void
    {
        $this->workRepository = $workRepository;
    }

    /**
     * Returns the gndId
     * 
     * @return string $gndId
     */
    public function getGndId(): string
    {
        return $this->gndId;
    }

    /**
     * Sets the gndId
     * 
     * @param string $gndId
     * @return GndPerson
     */
    public function setGndId($gndId): GndPerson
    {
        $this->gndId = $gndId;
        return $this;
    }

    /**
     * Returns the name
     * 
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the dateOfBirth
     * 
     * @return \DateTime $dateOfBirth
     */
    public function getDateOfBirth(): \DateTime
    {
        return $this->dateOfBirth;
    }

    /**
     * Returns the dateOfDeath
     * 
     * @return \DateTime $dateOfDeath
     */
    public function getDateOfDeath(): \DateTime
    {
        return $this->dateOfDeath;
    }

    /**
     * Returns the placeOfBirth
     * 
     * @return string $placeOfBirth
     */
    public function getPlaceOfBirth(): string
    {
        return $this->placeOfBirth;
    }

    /**
     * Returns the placeOfDeath
     * 
     * @return string $placeOfDeath
     */
    public function getPlaceOfDeath(): string
    {
        return $this->placeOfDeath;
    }

    /**
     * Returns the placeOfActivity
     * 
     * @return string $placeOfActivity
     */
    public function getPlaceOfActivity(): string
    {
        return $this->placeOfActivity;
    }

    /**
     * Returns the geographicAreaCode
     * 
     * @return string $geographicAreaCode
     */
    public function getGeographicAreaCode(): string
    {
        return $this->geographicAreaCode;
    }

    /**
     * Returns the gender
     * 
     * @return string $gender
     */
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * pull information from gndId
     * 
     * @param $gndId
     * @return bool
     */
    public function pullGndInfo(): bool
    {
        $dates = [];
        $url = GndLib::DATASERVER . GndLib::DATAPATH . $this->gndId;
        $headers = @get_headers($url);
        if (!$headers || $headers[0] == 'HTTP/1.0 404 Not Found' || $headers[0] == 'HTTP/1.1 404 Not Found') {
            return false;
        }
        $deepArray = json_decode(file_get_contents($url), true);
        $personArray = GndLib::flattenDataSet($deepArray);

        // purge placeOfActivity as multiple values will be concatenated later
        $this->placeOfActivity = '';
        if ($personArray['100'][0]['a']) {
            $this->name = $personArray['100'][0]['a'];
        }
        if ($personArray[548]) {
            foreach ($personArray[548] as $set) {
                if ($set['i'] == 'Exakte Lebensdaten') {
                    $dates = explode('-', $set['a']);
                }
            }
        }
        if ($dates) {
            $dates = array_combine(['birth', 'death'], $dates);
            foreach ($dates as $type => $date) {
                $dates[$type] = array_slice(explode('.', $date), 0, 3);
                if (isset($dates[$type][2]) && preg_match('/[1-2X][0-9X][0-9X][0-9X]/', $dates[$type][2])) {
                    $dates[$type] = null;
                } else if (!isset($dates[$type][2])) {
                    if (!isset($dates[$type][1])) {
                        $dates[$type]['y'] = $dates[$type][0];
                        $dates[$type]['m'] = '01';
                        $dates[$type]['d'] = '01';
                    } else {
                        $dates[$type]['y'] = $dates[$type][1];
                        $dates[$type]['m'] = $dates[$type][0];
                        $dates[$type]['d'] = '01';
                    }
                } else {
                    $dates[$type]['y'] = $dates[$type][2];
                    $dates[$type]['m'] = $dates[$type][1];
                    $dates[$type]['d'] = $dates[$type][0];
                }
                if ($dates[$type]) {
                    foreach ($dates[$type] as $key => $cipher) {
                        if ($cipher == 'XX') {
                            $dates[$type][$key] = '01';
                        }
                    }
                }
            }
            if ($dates['birth'] && $dates['birth']['d'] != '' && $dates['birth']['m'] != '' && $dates['birth']['y'] != '') {
                $this->dateOfBirth = $dates['birth'] ? new \DateTime($dates['birth']['y'] . '-' . $dates['birth']['m'] . '-' . $dates['birth']['d'] . 'T00:00:00P') : null;
            }
            if ($dates['death'] && $dates['death']['d'] != '' && $dates['death']['m'] != '' && $dates['death']['y'] != '') {
                $this->dateOfDeath = $dates['death'] ? new \DateTime($dates['death']['y'] . '-' . $dates['death']['m'] . '-' . $dates['death']['d'] . 'T00:00:00P') : null;
            }
        }
        if (isset($personArray[551])) {
            foreach ($personArray['551'] as $set) {
                if ($set['i'] == 'Geburtsort') {
                    $this->placeOfBirth = $set['a'];
                }
                if ($set['i'] == 'Sterbeort') {
                    $this->placeOfDeath = $set['a'];
                }
                if ($set['i'] == 'Wirkungsort') {
                    if ($this->placeOfActivity == '') {
                        $this->placeOfActivity = $set['a'];
                    } else {
                        $this->placeOfActivity = $this->placeOfActivity . ', ' . $set['a'];
                    }
                }
            }
        }
        if (isset($personArray[375])) {
            foreach ($personArray['375'] as $cell) {
                if ($cell['a'] == "1") {
                    $this->gender = "m";
                } else {
                    if ($cell['a'] == "2") {
                        $this->gender = "f";
                    }
                }
            }
        }
        $this->geographicAreaCode = '';
        if (isset($deepArray['043'])) {
            foreach ($deepArray['043'][0]['__'] as $geoCode) {
                $this->geographicAreaCode = $this->geographicAreaCode ? $this->geographicAreaCode . ', ' . $geoCode['c'] : $geoCode['c'];
            }

            //$this->geographicAreaCode = $personArray['043']['c'];
        }
        $this->unmodifiedGndData = true;

        return true;
    }

    /**
     * Returns the gndStatus
     * 
     * @return string gndStatus
     */
    public function getGndStatus(): string
    {
        return $this->gndStatus;
    }

    /**
     * Gets a list of works that the person is firstComposer of
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\DmNorm\Domain\Model\Work>
     */
    public function getWorks(): ObjectStorage
    {
        return $this->works;
    }
}
