<?php
namespace Slub\DmNorm\Domain\Repository;

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
 * The repository for Instruments
 */
class GndInstrumentRepository extends Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = ['name' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];

    /**
     * lookupInstrument
     * 
     * @param string $name
     */
    public function lookupInstrument(string $name)
    {
        $identityQuery = $this->createQuery();
        $identityQuery->matching($identityQuery->equals('name', $name, $caseSensitive = FALSE));
        $likeQuery = $this->createQuery();
        $likeQuery->matching($likeQuery->like('displayAs', '%' . $name . '%'));
        $instruments = $identityQuery->execute()->toArray();
        $instruments = array_merge($instruments, $likeQuery->execute()->toArray());
        return $instruments;
    }
}
