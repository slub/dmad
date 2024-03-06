<?php
namespace Slub\DmNorm\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Illuminate\Support\Collection;
use \Slub\DmNorm\Common\GndLib;
use Slub\DmNorm\Domain\Repository\GndPersonRepository;
use Slub\DmNorm\Domain\Repository\GndWorkRepository;
use Slub\DmNorm\Domain\Repository\GndGenreRepository;
use Slub\DmNorm\Domain\Repository\GndInstrumentRepository;
use Slub\DmNorm\Domain\Model\GndPerson;
use Slub\DmNorm\Domain\Model\GndForm;
use Slub\DmNorm\Domain\Model\GndInstrument;

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
 * GndWork
 */
class GndWork extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    const GNDMAP = array('Oberbegriff partitiv' => 'superWork', 'Relation (allgemein)' => 'intertextualEntity', 'Komponist1' => 'firstcomposer');
    const TITLEMAP = array('Adagios' => 'Adagio', 'Andantes' => 'Andante', 'Bagatellen' => 'Bagatelle', 'Capriccios' => 'Capriccio', 'Divertimenti' => 'Divertimento', 'Etüden' => 'Etüde', 'Fantasien' => 'Fantasie', 'Fugen' => 'Fuge', 'Fughetten' => 'Fughetta', 'Giguen' => 'Gigue', 'Gesänge' => 'Gesang', 'Inventionen' => 'Invention', 'Kanzonen' => 'Kanzone', 'Konzerte' => 'Konzert', 'Lieder' => 'Lied', 'Menuette' => 'Menuett', 'Messen' => 'Messe', 'Oktette' => 'Oktett', 'Ouvertüren' => 'Ouvertüre', 'Ouverturen' => 'Ouvertüre', 'Pastoralen' => 'Pastorale', 'Präludien' => 'Präludium', 'Partiten' => 'Partita', 'Romanzen' => 'Romanze', 'Rondos' => 'Rondo', 'Septette' => 'Septett', 'Serenaden' => 'Serenade', 'Sextette' => 'Sextett', 'Sinfonien' => 'Sinfonie', 'Sonaten' => 'Sonate', 'Studien' => 'Studie', 'Stücke' => 'Stück', 'Suiten' => 'Suite', 'Tokkaten' => 'Toccata', 'Trios' => 'Trio', 'Variationen' => 'Variation', 'Quartette' => 'Quartett', 'Quintette' => 'Quintett');
    const LOC = 'Location: ';
    const DATASERVER = 'https://data.slub-dresden.de/';
    const DATAPATH = 'source/gnd_marc21/';

    /**
     * personRepository
     * 
     * @TYPO3\CMS\Extbase\Annotation\Inject
     * @var \Slub\DmNorm\Domain\Repository\GndPersonRepository
     */
    protected $personRepository = null;

    /**
     * workRepository
     * 
     * @var \Slub\DmNorm\Domain\Repository\GndWorkRepository
     */
    protected $workRepository = null;

    /**
     * gndId
     * 
     * @var string
     */
    protected $gndId = '';

    /**
     * fullTitle
     * 
     * @var string
     */
    protected $fullTitle = '';

    /**
     * title
     * 
     * @var string
     */
    protected $title = '';

    /**
     * individualTitle
     * 
     * @var string
     */
    protected $individualTitle = '';

    /**
     * genericTitle
     * 
     * @var string
     */
    protected $genericTitle = '';

    /**
     * dateOfProduction
     * 
     * @var \DateTime
     */
    protected $dateOfProduction = null;

    /**
     * geographicAreaCode
     * 
     * @var string
     */
    protected $geographicAreaCode = '';

    /**
     * geographicalAreaCode
     * 
     * @var string
     */
    protected $geographicalAreaCode = '';

    /**
     * opusNo
     * 
     * @var string
     */
    protected $opusNo = '';

    /**
     * indexNo
     * 
     * @var string
     */
    protected $indexNo = '';

    /**
     * mediumOfPerformance
     * 
     * @var string
     */
    protected $mediumOfPerformance = '';

    /**
     * gndStatus
     * 
     * @var string
     */
    protected $gndStatus = '';

    /**
     * tonality
     * 
     * @var string
     */
    protected $tonality = '';

    /**
     * titleNo
     * 
     * @var string
     */
    protected $titleNo = '';

    /**
     * titleInstrument
     * 
     * @var string
     */
    protected $titleInstrument = '';

    /**
     * altTitles
     * 
     * @var string
     */
    protected $altTitles = '';

    /**
     * language
     * 
     * @var string
     */
    protected $language = '';

    /**
     * instrumentIds
     * 
     * @var string
     */
    protected $instrumentIds = '';

    /**
     * altInstrumentNames
     * 
     * @var string
     */
    protected $altInstrumentNames = '';

    /**
     * genreIds
     * 
     * @var string
     */
    protected $genreIds = '';

    /**
     * intertextualEntity
     * 
     * @var \Slub\DmNorm\Domain\Model\GndWork
     */
    protected $intertextualEntity = null;

    /**
     * superWork
     * 
     * @var \Slub\DmNorm\Domain\Model\GndWork
     */
    protected $superWork = null;

    /**
     * firstcomposer
     * 
     * @var \Slub\DmNorm\Domain\Model\GndPerson
     */
    protected $firstcomposer = null;

    /**
     * instruments
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\DmNorm\Domain\Model\GndInstrument>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $instruments = null;

    /**
     * form
     * 
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\DmNorm\Domain\Model\GndGenre>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Lazy
     */
    protected $gndGenre = null;

    /**
     * @param \Slub\DmNorm\Domain\Repository\GndWorkRepository $workRepository
     */
    public function injectGndWorkRepository(GndWorkRepository $workRepository): void
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
     * @return GndWork
     */
    public function setGndId($gndId): GndWork
    {
        $this->gndId = $gndId;

        return $this;
    }

    /**
     * Returns the intertextualEntity
     * 
     * @return \Slub\DmNorm\Domain\Model\GndWork $intertextualEntity
     */
    public function getIntertextualEntity(): GndWork
    {
        return $this->intertextualEntity;
    }

    /**
     * Returns the genericTitle
     * 
     * @return string $genericTitle
     */
    public function getGenericTitle(): string
    {
        return $this->genericTitle;
    }

    /**
     * Sets the full title
     *
     * @return void
     */
    private function setFullTitle(): void
    {
        if (isset(self::TITLEMAP[$this->title])) {
            $this->fullTitle = self::TITLEMAP[$this->title];
        } else {
            $this->fullTitle = $this->title;
        }
        if ($this->titleInstrument != '') {
            $this->fullTitle .= ' für ' . $this->titleInstrument;
        }
        if ($this->titleNo != '') {
            $this->fullTitle .= ' ' . $this->titleNo;
        }
        if ($this->tonality != '') {
            $this->fullTitle .= ' in ' . $this->tonality;
        }
        if ($this->individualTitle) {
            $this->fullTitle .= ', ' . $this->individualTitle;
        }
    }

    /**
     * Returns Full Title
     * 
     * @return string $fullTitle
     */
    public function getFullTitle(): string
    {
        return $this->fullTitle;
    }

    /**
     * Sets the title
     * @param string $title
     * @return void
     */
    private function setTitle($title): void
    {
        $this->title = self::TITLEMAP[$this->genericTitle] ?? $this->genericTitle;
        $this->setFullTitle();
    }

    /**
     * Gets the title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Returns the dateOfProduction
     * 
     * @return \DateTime $dateOfProduction
     */
    public function getDateOfProduction(): \DateTime
    {
        return $this->dateOfProduction;
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
     * Returns the geographicalAreaCode
     * 
     * @return string $geographicalAreaCode
     */
    public function getGeographicalAreaCode(): string
    {
        return $this->geographicalAreaCode;
    }

    /**
     * Returns the superWork
     * 
     * @return \Slub\DmNorm\Domain\Model\GndWork $superWork
     */
    public function getSuperWork(): GndWork
    {
        return $this->superWork;
    }

    /**
     * Returns the opusNo
     * 
     * @return string $opusNo
     */
    public function getOpusNo(): string
    {
        return $this->opusNo;
    }

    /**
     * Returns the indexNo
     * 
     * @return string $indexNo
     */
    public function getIndexNo(): string
    {
        return $this->indexNo;
    }

    /**
     * Returns the mediumOfPerformance
     * 
     * @return string $mediumOfPerformance
     */
    public function getMediumOfPerformance(): string
    {
        return $this->mediumOfPerformance;
    }

    /**
     * Pulls information from GND
     * 
     * @return boolean
     */
    public function pullGndInfo(
        GndWorkRepository $workRepository,
        GndPersonRepository $personRepository,
        GndInstrumentRepository $instrumentRepo, 
        GndGenreRepository $formRepo
    ): bool
    {
        // get repositories and object manager in order
        // to create references
        // throw away after getting rid of instrument / form
        $getQuery = function(string $gndId): string {
            return 'http://sdvlodpro:9200/gnd_marc21/_search?q=035.__.a:%22(DE-101)' . $gndId . '%22';
        };
        //$instrumentRepo = GeneralUtility::makeInstance(InstrumentRepository::class);
        //$formRepo = GeneralUtility::makeInstance(FormRepository::class);

        // download gnd data
        $url = self::DATASERVER . self::DATAPATH . $this->gndId;
        $headers = @get_headers($url);

        // if not found check for redirects
        if (
            !$headers || 
            $headers[0] == 'HTTP/1.0 404 Not Found' || 
            $headers[0] == 'HTTP/1.1 404 Not Found' ||
            $headers[0] == 'HTTP/1.0 500 INTERNAL SERVER ERROR' ||
            $headers[0] == 'HTTP/1.1 500 INTERNAL SERVER ERROR'
        ) {
            $url = $getQuery($this->gndId);
            $headers = @get_headers($url);
            if (!$headers || $headers[0] == 'HTTP/1.0 404 Not Found' || $headers[0] == 'HTTP/1.1 404 Not Found') {
                return false;
            }
            $array = json_decode(file_get_contents($url), true);
            $this->gndId = $array['hits']['hits'][0]['_id'];
            if (!$this->gndId) {
                return false;
            }
            $url = self::DATASERVER . self::DATAPATH . $this->gndId;
        }

        $workArray = json_decode(file_get_contents($url), true);

        $this->titleInstrument = '';
        if (isset($workArray[100])) {
            foreach ($workArray[100][0]['1_'] as $cell) {
                if (isset($cell['m'])) {
                    $this->titleInstrument = $this->titleInstrument ? $this->titleInstrument . ', ' . $cell['m'] : $cell['m'];
                }
            }
        }

        $getId = function(array $item): string {
            $filterId = function(array $cell): bool {
                if (isset($cell[0])) {
                    if (is_array($cell[0])) {
                        return str_contains($cell[0][0], 'd-nb.info');
                    }
                    return str_contains($cell[0], 'd-nb.info');
                }
                return false;
            };
            $url = Collection::wrap($item['__'])
                ->filter( $filterId )
                ->toArray();
            if (isset(array_values($url)[0][0])) {
                return str_replace('https://d-nb.info/gnd/', '', array_values($url)[0][0]);
            }
            return '';
        };

        $getPv = function($item) {
            $filterP = function($cell) {
                return array_key_exists('p', $cell);
            };
            $filterV = function($cell) {
                return array_key_exists('v', $cell);
            };
            $p = Collection::wrap($item['__'])
                ->filter( $filterP )
                ->toArray();
            $p = isset(array_values($p)[0]['p']) ? array_values($p)[0]['p'] : '';
            $v = Collection::wrap($item['__'])
                ->filter( $filterV )
                ->toArray();
            $v = isset(array_values($v)[0]['v']) ? substr(array_values($v)[0]['v'], 17) : '';
            if ($p && $v)
                //$debug($p, $v);
                return $v . '>' . $p;
            return '';
        };

        if (isset($workArray[380])) {
            $this->genreIds = Collection::wrap($workArray[380])
                ->map($getId)
                ->filter(function($item) { return $item != '';})
                ->join('$');
        }
        if (isset($workArray[382])) {
            $this->instrumentIds = Collection::wrap($workArray[382])
                ->map($getId)
                ->filter(function($item) { return $item != '';})
                ->join('$');
            $this->altInstrumentNames = Collection::wrap($workArray[382])
                ->map($getPv)
                ->filter(function($item) { return $item != '';})
                ->join('$');
        }
        $workArray = GndLib::flattenDataSet($workArray);

        // process gnd data
        $ids = [];
        if (isset($workArray[500])) {
            foreach ($workArray[500] as $key => $field) {

                // is $field a related object ?
                if ($field['i'] && array_key_exists($field['i'], self::GNDMAP)) {
                    foreach ($field as $cell) {

                        // find gnd id of related object
                        if (strpos($cell, 'd-nb.info/gnd')) {
                            $ids[self::GNDMAP[$field['i']]] = str_replace('https://d-nb.info/gnd/', '', $cell);
                        }
                    }
                }
            }
        }
        foreach ($ids as $key => $id) {
            $repo = $key == 'firstcomposer' ? 
                $personRepository : $workRepository;
            $class = $key == 'firstcomposer' ? Person::class : GndWork::class;
            if ($repo->findOneByGndId($id)) {
                $entityArray[$key] = $repo->findOneByGndId($id);
                $entityArray[$key]->pullGndInfo(
                    $workRepository,
                    $personRepository,
                    $instrumentRepo, 
                    $formRepo
                );
                $repo->update($entityArray[$key]);
            } else {
                $entityArray[$key] = GeneralUtility::makeInstance($class);
                $entityArray[$key]->setGndStatus($this->gndStatus);
                $entityArray[$key]->setGndId($id);
                $entityArray[$key]->pullGndInfo(
                    $workRepository,
                    $personRepository,
                    $instrumentRepo, 
                    $formRepo
                );
                $repo->add($entityArray[$key]);
            }
        }
        if (isset($entityArray['firstcomposer'])) {
            $this->firstcomposer = $entityArray['firstcomposer'];
        }
        if (isset($entityArray['superWork'])) {
            $this->superWork = $entityArray['superWork'];
        }
        if (isset($entityArray['intertextualEntity'])) {
            $this->intertextualEntity = $entityArray['intertextualEntity'];
        }
        if (isset($workArray[380])) {
            foreach ($workArray[380] as $form) {
                foreach ($form as $cell) {
                    if (is_string($cell) && strpos($cell, 'd-nb.info/gnd/')) {
                        $id = str_replace('https://d-nb.info/gnd/', '', $cell);
                        $form = $formRepo->findOneByGndId($id);
                        if (!$form) {
                            $form = GeneralUtility::makeInstance(Form::class);
                            $form->setGndId($id);
                            $formRepo->add($form);
                        }
                        $form->pullGndInfo();
                        $this->form->attach($form);
                        break;
                    }
                }
            }
        }
        $this->setInstruments();
        if (isset($workArray[382])) {
            foreach ($workArray[382] as $instrument) {
                $add = true;
                foreach ($instrument as $cell) {
                    if (is_string($cell) && strpos($cell, 'lternativ')) {
                        $add = false;
                    }
                }
                foreach ($instrument as $cell) {
                    if ($add && is_string($cell) && strpos($cell, 'd-nb.info/gnd/')) {
                        $id = str_replace('https://d-nb.info/gnd/', '', $cell);
                        $instrument = $instrumentRepo->findOneByGndId($id);
                        if (!$instrument) {
                            $instrument = GeneralUtility::makeInstance(Instrument::class);
                            $instrument->setGndId($id);
                            $instrumentRepo->add($instrument);
                        }
                        $instrument->pullGndInfo();
                        $this->instruments->attach($instrument);
                        break;
                    }
                }
            }
        }

        $this->individualTitle = isset($workArray[100][0]['p']) ? $workArray[100][0]['p'] : '';
        $this->title = isset($workArray[100][0]['t']) ? $workArray[100][0]['t'] : '';
        if ($this->title == '' && isset($workArray[130])) {
            $this->title = isset($workArray[130][0]['a']) ? $workArray[130][0]['a'] : '';
        }
        $this->title = str_replace("", "", $this->title);
        $this->title = str_replace("", "", $this->title);
        if (isset($workArray['383'])) {
            foreach ($workArray['383'] as $titleInfo) {
                if (isset($titleInfo['a'])) {
                    $this->titleNo = $titleInfo['a'];
                }
                if (isset($titleInfo['b'])) {
                    $this->opusNo = str_replace('op. ', '', $titleInfo['b']);
                }
                if (isset($titleInfo['c'])) {
                    $this->indexNo = $titleInfo['c'];
                }
            }
        }

        //$this->indexNo = $workArray['383'][0]['c'] ? $workArray['383'][0]['c'] : '';
        //$this->opusNo = $workArray['383'][0]['b'] ? $workArray['383'][0]['b'] : '';
        //$this->opusNo = str_replace('op. ', '', $this->opusNo);
        $yearString = isset($workArray['548'][0]['a']) ? $this->getDate($workArray['548'][0]['a']) : '';
        $dateString = $yearString != '' ? $yearString . '-01-01T00:00:00P' : null;
        $this->dateOfProduction = $dateString ? new \DateTime(str_replace('X', '0', $dateString)) : null;
        if (isset($workArray['384'])){
            $this->tonality = $workArray['384'][0]['a'] ? $workArray['384'][0]['a'] : '';
        }
        if ($this->tonality != '') {
            $titleArray = explode(' ', $this->title);
            foreach ($titleArray as $key => $word) {
                if (array_key_exists($word, self::TITLEMAP)) {
                    $titleArray[$key] = self::TITLEMAP[$word];
                }
            }
            $this->title = implode(' ', $titleArray);
        }

        $this->altTitles = '';
        if (isset($workArray['400'])) {
            foreach ($workArray['400'] as $title) {
                if (isset($title['t']))
                    $this->altTitles = $this->altTitles ? $this->altTitles . ' $ ' . $title['t'] : $title['t'];
            }
        }
    }

    /**
     * Returns a date from a string
     * 
     * @param string $string
     * @return string
     */
    private function getDate(string $string): string
    {
        preg_match('/1[0-9][0-9X][0-9X]/', $string, $result);
        return isset($result[0]) ? $result[0] : '';
    }

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
    protected function initStorageObjects(): void
    {
        $this->instruments = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->form = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->altInstrumentation = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->gndGenres = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->publisherMakroItems = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Adds a Instrument
     * 
     * @param \Slub\DmNorm\Domain\Model\GndInstrument $instrument
     * @return void
     */
    public function addInstrument(\Slub\DmNorm\Domain\Model\GndInstrument $instrument): void
    {
        $this->instruments->attach($instrument);
    }

    /**
     * Removes a Instrument
     * 
     * @param \Slub\DmNorm\Domain\Model\GndInstrument $instrumentToRemove The Instrument to be removed
     * @return void
     */
    public function removeInstrument(\Slub\DmNorm\Domain\Model\GndInstrument $instrumentToRemove): void
    {
        $this->instruments->detach($instrumentToRemove);
    }

    /**
     * Returns the instruments
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\DmNorm\Domain\Model\GndInstrument> instruments
     */
    public function getInstruments(): ObjectStorage
    {
        return $this->instruments;
    }

    /**
     * Returns the firstcomposer
     * 
     * @return \Slub\DmNorm\Domain\Model\GndPerson
     */
    public function getFirstcomposer(): GndPerson
    {
        return $this->firstcomposer;
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
     * Returns the gndGenre
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\DmNorm\Domain\Model\GndGenre> $gndGenre
     */
    public function getGndGenre(): GndGenre
    {
        return $this->gndGenre;
    }

    /**
     * Returns the tonality
     * 
     * @return string $tonality
     */
    public function getTonality(): string
    {
        return $this->tonality;
    }

    /**
     * Returns the titleNo
     * 
     * @return string $titleNo
     */
    public function getTitleNo(): string
    {
        return $this->titleNo;
    }

    /**
     * Returns the titleInstrument
     * 
     * @return string $titleInstrument
     */
    public function getTitleInstrument(): string
    {
        return $this->titleInstrument;
    }

    /**
     * Returns the altTitles
     * 
     * @return string $altTitles
     */
    public function getAltTitles(): string
    {
        return $this->altTitles;
    }

    /**
     * Returns the language
     * 
     * @return string $language
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Returns all subWorks of the work
     * 
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Slub\DmNorm\Domain\Model\GndWork>
     */
    public function getSubWorks(): ObjectStorage
    {
        return $this->workRepository->findBySuperWork($this);
    }

    /**
     * Returns the instrumentIds
     * 
     * @return string instrumentIds
     */
    public function getInstrumentIds(): string
    {
        return $this->instrumentIds;
    }

    /**
     * Returns the altInstrumentNames
     * 
     * @return string altInstrumentNames
     */
    public function getAltInstrumentNames(): string
    {
        return $this->altInstrumentNames;
    }

    /**
     * Returns the genreIds
     * 
     * @return string genreIds
     */
    public function getGenreIds(): string
    {
        return $this->genreIds;
    }
}
