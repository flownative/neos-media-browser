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

use Flownative\Media\Browser\AssetSource\AssetProxyInterface;
use Flownative\Media\Browser\AssetSource\AssetProxyQueryInterface;
use Flownative\Media\Browser\AssetSource\AssetProxyQueryResultInterface;
use Neos\Flow\Persistence\QueryResultInterface;

final class NeosAssetProxyQueryResult implements AssetProxyQueryResultInterface
{
    /**
     * @var QueryResultInterface
     */
    private $flowPersistenceQueryResult;

    /**
     * @var NeosAssetProxyQuery
     */
    private $query;

    /**
     * @param QueryResultInterface $flowPersistenceQueryResult
     */
    public function __construct(QueryResultInterface $flowPersistenceQueryResult)
    {
        $this->flowPersistenceQueryResult = $flowPersistenceQueryResult;
    }

    /**
     * @return AssetProxyQueryInterface
     */
    public function getQuery(): AssetProxyQueryInterface
    {
        if ($this->query === null) {
            $this->query = new NeosAssetProxyQuery($this->flowPersistenceQueryResult->getQuery());
        }
        return $this->query;
    }

    public function getFirst(): ?AssetProxyInterface
    {
        return new NeosAssetProxy($this->flowPersistenceQueryResult->getFirst());
    }

    /**
     * @return NeosAssetProxy[]
     */
    public function toArray(): array
    {
        $assetProxies = [];
        foreach ($this->flowPersistenceQueryResult->toArray() as $asset) {
            $assetProxies[] = new NeosAssetProxy($asset);
        }
        return $assetProxies;
    }

    public function current()
    {
        return new NeosAssetProxy($this->flowPersistenceQueryResult->current());
    }

    public function next()
    {
        $this->flowPersistenceQueryResult->next();
    }

    public function key()
    {
        return $this->flowPersistenceQueryResult->key();
    }

    public function valid()
    {
        return $this->flowPersistenceQueryResult->valid();
    }

    public function rewind()
    {
        $this->flowPersistenceQueryResult->rewind();
    }

    public function offsetExists($offset)
    {
        return $this->flowPersistenceQueryResult->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return new NeosAssetProxy($this->flowPersistenceQueryResult->offsetGet($offset));
    }

    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Unsupported operation: ' . __METHOD__, 1510060444556);
    }

    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Unsupported operation: ' . __METHOD__, 1510060467733);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->flowPersistenceQueryResult->count();
    }
}
