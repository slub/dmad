<?php
namespace Slub\DmNorm\Domain\Repository;

use Slub\MpdbCore\Lib\DbArray;
use Slub\DmNorm\Domain\Model\GndPerson;
use Slub\DmNorm\Domain\Model\GndWork;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
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
 * The repository for Persons
 */
class GndPersonRepository extends Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = ['name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];

    /**
     * lookupComposer
     * 
     * @param string $name
     */
    public function lookupComposer(string $name)
    {
        $identityQuery = $this->createQuery();
        $identityQuery->matching($identityQuery->equals('name', $name, $caseSensitive = FALSE));
        $likeQuery = $this->createQuery();
        $likeQuery->matching($likeQuery->like('name', '%' . $name . '%'))->setLimit(100);
        $persons = $identityQuery->execute()->toArray();
        $persons = array_merge($persons, $likeQuery->execute()->toArray());
        return array_unique($persons);
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
        $query->like('name', '%' . $name . '%'), 
        $query->greaterThanOrEqual('final', $level)
        ]
        )
        )->setLimit(100);
        return $query->execute();
    }

    /**
     * @param int $from
     * @param int $level
     */
    public function list(int $from, int $level)
    {
        $query = $this->createQuery();
        $query->matching($query->greaterThanOrEqual('final', $level));
        return $query->setOffset($from * 25)->setLimit(25)->execute();
    }

    /**
     * @param int $level
     */
    public function count(int $level)
    {
        $query = $this->createQuery();
        $query->matching($query->greaterThanOrEqual('final', $level));
        return $query->count();
    }

    /**
     * summarizes all publisheractions connected to person
     *
     * @param Person $Person
     * @param int $level
     */
    public function showPerson(GndPerson $person, int $level) {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_publisherdb_domain_model_publisheraction');
        $eb = $qb->expr();

        $getWork = function($array) {
            return $array['work_uid'];
        };

        $id = function($e) { return $e; };

        $parse = function($item) {
            $getPublisher = function($item) { return $item['publisher_shorthand']; };
            $getActionInfo = function($item) {
                return [
                    'uid' => $item['action_uid'],
                    'quantity' => $item['quantity'],
                    'date' => $item['date'] ]; };
            $group = new DbArray($item['group']);

            return [
                'work_uid' => $item['groupObject'],
                'full_title' => GndWork::formatTitle(
                    $item['group'][0]['work_title'], 
                    $item['group'][0]['work_instrument'], 
                    $item['group'][0]['work_no'], 
                    $item['group'][0]['work_tonality']),
                'super_work' => $item['work_superwork'],
                'publishers' => $group
                    ->map($getPublisher)
                    ->unique()
                    ->implode(', '),
                'actions' => $group
                    ->map($getActionInfo)
                    ->toArray()
            ];
        };
        $selectAction = function($array) { 
            return [
                'uid' => $array['action_uid'],
                'mikro_uid' => $array['mikro_uid'],
                'quantity' => $array['quantity'],
                'date' => $array['type'],
                'publisher' => $array['publisher_shorthand'] ];
        };
        
        $data = $qb
            ->select(
                'publisheraction.uid AS action_uid',
                'publisheraction.quantity AS quantity',
                'publisheraction.date_of_action AS date',
                'publishermikroitem.uid AS mikro_uid',
                'work.uid AS work_uid',
                'work.title AS work_title',
                'work.title_instrument AS work_instrument',
                'work.title_no AS work_no',
                'work.tonality AS work_tonality',
                'work.super_work AS work_superwork',
                'publishermakroitem.uid AS makro_uid',
                'publisher.uid AS publisher_uid',
                'publisher.name AS publisher_name',
                'publisher.shorthand AS publisher_shorthand'
            )
            ->from('tx_publisherdb_domain_model_publisheraction', 'publisheraction')
            ->leftJoin(
                'publisheraction',
                'tx_publisherdb_domain_model_publishermikroitem',
                'publishermikroitem',
                $eb->eq('publishermikroitem.uid', $qb->quoteIdentifier('publisheraction.publisher_mikro_item'))
            )
            ->leftJoin(
                'publishermikroitem',
                'tx_publisherdb_publishermikroitem_work_mm',
                'mikrowork',
                $eb->eq('publishermikroitem.uid', $qb->quoteIdentifier('mikrowork.uid_local'))
            )
            ->leftJoin(
                'mikrowork',
                'tx_publisherdb_domain_model_work',
                'work',
                $eb->eq('work.uid', $qb->quoteIdentifier('mikrowork.uid_foreign'))
            )
            ->leftJoin(
                'work',
                'tx_publisherdb_domain_model_publishermakroitem',
                'publishermakroitem',
                $eb->eq('publishermakroitem.uid', $qb->quoteIdentifier('publishermikroitem.publisher_makro_item'))
            )
            ->leftJoin(
                'publishermakroitem',
                'tx_publisherdb_domain_model_publisher',
                'publisher',
                $eb->eq('publisher.uid', $qb->quoteIdentifier('publishermakroitem.publisher'))
            )
            ->where(
                $eb->eq('work.first_composer', $person->getUid()),
                $eb->gte('work.final', $level),
                $eb->eq('publisheraction.type', $qb->createNamedParameter('print'))
            )
            ->execute()
            ->fetchAll();
        return (new DbArray())
            ->set($data)
            ->group($getWork, $id)
            ->map($parse)
            ->toArray();
    }
}
