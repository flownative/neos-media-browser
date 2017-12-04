<?php
namespace Flownative\Media\Browser\AssetSource\Neos;

/*
 * This file is part of the Flownative.Media.Browser package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 * (c) Robert Lemke, Flownative GmbH - www.flownative.com
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Flownative\Media\Browser\AssetSource\AssetProxyQueryInterface;
use Flownative\Media\Browser\AssetSource\AssetProxyQueryResultInterface;
use Neos\Flow\Persistence\QueryInterface;

final class NeosAssetProxyQuery implements AssetProxyQueryInterface
{
    /**
     * @var QueryInterface
     */
    private $flowPersistenceQuery;

    /**
     * NeosAssetProxyQuery constructor.
     *
     * @param QueryInterface $flowPersistenceQuery
     */
    public function __construct(QueryInterface $flowPersistenceQuery)
    {
        $this->flowPersistenceQuery = $flowPersistenceQuery;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->flowPersistenceQuery->setOffset($offset);
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->flowPersistenceQuery->getOffset();
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->flowPersistenceQuery->setLimit($limit);
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->flowPersistenceQuery->getLimit();
    }

    /**
     * @return AssetProxyQueryResultInterface
     */
    public function execute(): AssetProxyQueryResultInterface
    {
        return new NeosAssetProxyQueryResult($this->flowPersistenceQuery->execute());
    }

    public function setSearchTerm(string $searchTerm)
    {

    }

    public function getSearchTerm()
    {
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->flowPersistenceQuery->count();
    }
}
