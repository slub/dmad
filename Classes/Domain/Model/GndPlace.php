<?php
namespace Slub\PublisherDb\Domain\Model;


/***
 *
 * This file is part of the "Publisher Database" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2022 Matthias Richter <matthias.richter@slub-dresden.de>, SLUB Dresden
 *
 ***/
/**
 * GndPlace
 */
class GndPlace extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     * 
     * @var string
     */
    protected $name = '';

    /**
     * altNames
     * 
     * @var string
     */
    protected $altNames = '';

    /**
     * longitude
     * 
     * @var int
     */
    protected $longitude = 0;

    /**
     * latitude
     * 
     * @var int
     */
    protected $latitude = 0;

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
     * Returns the altNames
     * 
     * @return string $altNames
     */
    public function getAltNames(): string
    {
        return $this->altNames;
    }

    /**
     * Returns the longitude
     * 
     * @return int $longitude
     */
    public function getLongitude(): int
    {
        return $this->longitude;
    }

    /**
     * Returns the latitude
     * 
     * @return int $latitude
     */
    public function getLatitude(): int
    {
        return $this->latitude;
    }

    /**
     * Pulls data from GND
     * 
     * @return void
     */
    public function pullFromGnd(): void
    {
    }
}
