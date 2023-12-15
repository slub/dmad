<?php
namespace Slub\DmNorm\Domain\Repository;

use Slub\DmNorm\Domain\Model\GndPerson;
use Slub\MpdbCore\Lib\DbArray;
use Slub\MpdbCore\Domain\Model\Publisher;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\EndTimeRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\StartTimeRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

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
 * The repository for Works
 */
class GndWorkRepository extends Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = ['title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];

    /**
     * lookupWork
     * 
     * @param string $name
     */
    public function lookupWork(string $name)
    {
        $identityQuery = $this->createQuery();
        $identityQuery->matching($identityQuery->equals('title', $name, $caseSensitive = FALSE));
        $likeQuery = $this->createQuery();
        $likeQuery->matching($likeQuery->like('title', '%' . $name . '%'))->setLimit(100);
        $altQuery = $this->createQuery();
        $altQuery->matching($altQuery->like('altTitles', '%' . $name . '%'))->setLimit(100);
        $works = $identityQuery->execute()->toArray();
        $works = array_merge($works, $likeQuery->execute()->toArray());
        $works = array_merge($works, $altQuery->execute()->toArray());
        return array_unique($works);
    }

    /**
     * Get currently created qualified work data sets
     */
    public function getQualified()
    {
        return $this->get('q');
    }

    /**
     * Get currently created new work data sets
     */
    public function getNew()
    {
        return $this->get('n');
    }

    /**
     * Count qualified work data sets
     */
    public function countQualified()
    {
        return $this->count('q')->count();
    }

    /**
     * Count new work data sets
     */
    public function countChecked()
    {
        return $this->count('p')->count();
    }

    /**
     * Count new work data sets
     */
    public function countNew()
    {
        return $this->count('n')->count();
    }

    /**
     * @param string $type
     */
    private function count(string $type)
    {
        $query = $this->createQuery();
        return $query->matching($query->equals('gnd_status', $type));
    }

    /**
     * @param string $type
     */
    private function get(string $type)
    {
        $query = $this->createQuery();
        return $query->matching(
        $query->logicalAnd(
        [
        $query->equals('gnd_status', $type), 
        $query->logicalNot($query->equals('title', '')), 
        $query->logicalNot($query->equals('gnd_id', 'lokal'))
        ]
        )
        )->setLimit(30)->execute();
    }

    /**
     * @param string $name
     * @param int $level
     */
    public function search(string $name, int $level)
    {
        $query = $this->createQuery();
        $query->matching(
        $query->logicalAnd(
        [
        $query->logicalOr(
        [
        $query->like('title', '%' . $name . '%'), 
        $query->like('alt_titles', '%' . $name . '%')
        ]
        ), 
        $query->greaterThanOrEqual('final', $level)
        ]
        )
        )->setLimit(100);
        return $query->execute();
    }

    /**
     * @param \Slub\DmNorm\Domain\Model\GndPerson $person
     * @param int $level
     */
    public function listForPerson(GndPerson $person, int $level)
    {
        $query = $this->createQuery();
        $query->matching(
        $query->logicalAnd(
        [
        $query->equals('firstComposer', $person), 
        $query->greaterThanOrEqual('final', $level)
        ]
        )
        );
        return $query->execute();
    }

    /**
     *
     */
    public function listInstrumentLinks()
    {
        $getMvdbId = function ($array) {
            return $array['mvdbinstrument_id'];
        };
        $getMappedIds = function ($array) {
            return $array['mapped_ids'];
        };

        $parse = function ($array) use ($getMvdbId, $getMappedIds) {
            return [
                'work' => $array['groupObject'], 
                'gnd_links' => explode('$', $array['group'][0]['instrument_ids']), 
                'mvdb_links' => (new DbArray($array['group']))->map($getMvdbId)->toArray(),
                'mapped_ids' => explode('$', $array['group'][0]['mapped_ids'])
            ];
        };

        $getWork = function ($array) {
            return $array['work_id'];
        };
        
        $id = function($e) { return $e; };

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_publisherdb_domain_model_work');
        $eb = $qb->expr();
        $qb->getRestrictions()
            ->removeByType(DeletedRestriction::class)
            ->removeByType(HiddenRestriction::class)
            ->removeByType(StartTimeRestriction::class)
            ->removeByType(EndTimeRestriction::class);

        $data = $qb
            ->select(
                'work.gnd_id AS work_id', 
                'work.instrument_ids', 
                'work.alt_instrument_names',
                'instrument.gnd_id AS mvdbinstrument_id',
                'mapped_ids'
            )
            ->from('tx_publisherdb_domain_model_work', 'work')
            ->leftJoin(
                'work', 
                'tx_publisherdb_domain_model_mvdbinstrumentation',
                'instrumentation', 
                $eb->eq('instrumentation.uid', $qb->quoteIdentifier('work.main_instrumentation'))
            )
            ->leftJoin(
                'instrumentation', 
                'tx_publisherdb_mvdbinstrumentation_mvdbinstrument_mm',
                'instrumentationinstrument', 
                $eb->eq('instrumentationinstrument.uid_local', $qb->quoteIdentifier('instrumentation.uid'))
            )
            ->leftJoin(
                'instrumentation', 
                'tx_publisherdb_domain_model_mvdbinstrument', 
                'instrument', 
                $eb->eq('instrument.uid', $qb->quoteIdentifier('instrumentationinstrument.uid_foreign'))
            )
            ->where(
                $eb->eq('work.deleted', '0')
            )
            ->execute()
            ->fetchAll();
        return (new DbArray())
            ->set($data)
            ->group($getWork, $id)
            ->map($parse)
            ->toArray();
    }

    /**
     *
     */
    public function listGenreLinks()
    {
        $getMvdbId = function ($array) {
            return $array['mvdbgenre_id'];
        };
        $getMappedIds = function ($array) {
            return $array['mapped_ids'];
        };

        $parse = function ($array) use ($getMvdbId, $getMappedIds) {
            return [
                'work' => $array['groupObject'], 
                'gnd_links' => explode('$', $array['group'][0]['genre_ids']), 
                'mvdb_links' => (new DbArray($array['group']))->map($getMvdbId)->toArray(),
                'mapped_ids' => explode('$', $array['group'][0]['mapped_ids'])
            ];
        };

        $getWork = function ($array) {
            return $array['work_id'];
        };
        
        $id = function($e) { return $e; };

        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_publisherdb_domain_model_work');
        $eb = $qb->expr();
        $qb->getRestrictions()
            ->removeByType(DeletedRestriction::class)
            ->removeByType(HiddenRestriction::class)
            ->removeByType(StartTimeRestriction::class)
            ->removeByType(EndTimeRestriction::class);

        $data = $qb
            ->select(
                'work.gnd_id AS work_id', 
                'work.genre_ids', 
                'genre.gnd_id AS mvdbgenre_id',
                'mapped_ids'
            )
            ->from('tx_publisherdb_domain_model_work', 'work')
            ->leftJoin(
                'work', 
                'tx_publisherdb_work_mvdbgenre_mm', 
                'workgenre', 
                $eb->eq('workgenre.uid_local', $qb->quoteIdentifier('work.uid'))
                )
            ->leftJoin(
                'workgenre', 
                'tx_publisherdb_domain_model_mvdbgenre', 
                'genre', 
                $eb->eq('workgenre.uid_foreign', $qb->quoteIdentifier('genre.uid'))
                )
            ->where(
                $eb->eq('work.deleted', '0')
            )
            ->execute()
            ->fetchAll();
        return (new DbArray())
            ->set($data)
            ->group($getWork, $id)
            ->map($parse)
            ->toArray();
    }

    public function findByGenres($genre)
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_publisherdb_domain_model_work');
        $eb = $qb->expr();

        return $qb
            ->select(
                'work.uid AS uid',
                'person.name AS composer',
                'work.title AS title')
            ->from('tx_publisherdb_domain_model_work', 'work')
            ->join(
                'work',
                'tx_publisherdb_work_mvdbgenre_mm',
                'workgenre',
                $eb->eq('workgenre.uid_local', $qb->quoteIdentifier('work.uid')))
            ->join('workgenre',
                'tx_publisherdb_domain_model_mvdbgenre',
                'genre',
                $eb->eq('workgenre.uid_foreign', $qb->quoteIdentifier('genre.uid')))
            ->join('genre',
                'tx_publisherdb_domain_model_person',
                'person',
                $eb->eq('work.first_composer', $qb->quoteIdentifier('person.uid')))
            ->where($eb->eq('genre.uid', $genre->getUid()))
            ->execute()
            ->fetchAll();
    }

    public function findByInstrument($instrument)
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_publisherdb_domain_model_work');
        $eb = $qb->expr();

        $mainData = $qb
            ->select(
                'work.uid AS uid',
                'person.name AS composer',
                'work.title AS title')
            ->from('tx_publisherdb_domain_model_work', 'work')
            ->join(
                'work',
                'tx_publisherdb_domain_model_mvdbinstrumentation',
                'maininstrumentation',
                $eb->eq('maininstrumentation.uid', $qb->quoteIdentifier('work.main_instrumentation')))
            ->join('maininstrumentation',
                'tx_publisherdb_mvdbinstrumentation_mvdbinstrument_mm',
                'instrumentationinstrument',
                $eb->eq('instrumentationinstrument.uid_local', $qb->quoteIdentifier('maininstrumentation.uid')))
            ->join('instrumentationinstrument',
                'tx_publisherdb_domain_model_mvdbinstrument',
                'instrument',
                $eb->eq('instrumentationinstrument.uid_foreign', $qb->quoteIdentifier('instrument.uid')))
            ->join('instrument',
                'tx_publisherdb_domain_model_person',
                'person',
                $eb->eq('work.first_composer', $qb->quoteIdentifier('person.uid')))
                ->where($eb->eq('instrument.uid', $instrument->getUid()))
                ->execute()
                ->fetchAll();
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_publisherdb_domain_model_work');
        $eb = $qb->expr();
        $altData = $qb
            ->select(
                'work.uid AS uid',
                'person.name AS composer',
                'work.title AS title')
            ->from('tx_publisherdb_domain_model_work', 'work')
            ->join(
                'work',
                'tx_publisherdb_domain_model_mvdbinstrumentation',
                'instrumentation',
                $eb->inSet('work.alt_instrumentation', $qb->quoteIdentifier('instrumentation.uid')))
            ->join('instrumentation',
                'tx_publisherdb_mvdbinstrumentation_mvdbinstrument_mm',
                'instrumentationinstrument',
                $eb->eq('instrumentationinstrument.uid_local', $qb->quoteIdentifier('instrumentation.uid')))
            ->join('instrumentationinstrument',
                'tx_publisherdb_domain_model_mvdbinstrument',
                'instrument',
                $eb->eq('instrumentationinstrument.uid_foreign', $qb->quoteIdentifier('instrument.uid')))
            ->join('instrument',
                'tx_publisherdb_domain_model_person',
                'person',
                $eb->eq('work.first_composer', $qb->quoteIdentifier('person.uid')))
                ->where($eb->eq('instrument.uid', $instrument->getUid()))
                ->execute()
                ->fetchAll();

        return (new DbArray())
            ->set($mainData)
            ->concat($altData)
            ->values();
    }

    public function findByPublisher(Publisher $publisher = null) {
        if ($publisher) {
            $qb = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable('tx_publisherdb_domain_model_work');
            $eb = $qb->expr();
            return $qb->select('work.*', 'composer.name')
                ->from('tx_publisherdb_domain_model_work', 'work')
                ->join(
                    'work',
                    'tx_publisherdb_publishermakroitem_work_mm',
                    'work_publishermakroitem',
                    $eb->eq('work.uid', $qb->quoteIdentifier('uid_foreign'))
                )
                ->join(
                    'work_publishermakroitem',
                    'tx_publisherdb_domain_model_publishermakroitem',
                    'publishermakroitem',
                    $eb->eq('publishermakroitem.uid', $qb->quoteIdentifier('uid_local'))
                )
                ->join(
                    'publishermakroitem',
                    'tx_publisherdb_domain_model_person',
                    'composer',
                    $eb->eq('work.first_composer', $qb->quoteIdentifier('composer.uid'))
                )
                ->where(
                $eb->eq('publishermakroitem.publisher', $publisher->getUid())
                )
                ->execute()
                ->fetchAll();
        } else {
            return $this->createQuery()->execute();
        }
    }
}
