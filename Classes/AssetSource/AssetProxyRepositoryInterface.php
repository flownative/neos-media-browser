<?php
namespace Flownative\Media\Browser\AssetSource;

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

use Neos\Media\Domain\Model\Tag;

interface AssetProxyRepositoryInterface
{
    /**
     * @param string $identifier
     * @return AssetProxyInterface
     * @throws AssetNotFoundException
     * @throws AssetSourceConnectionException
     */
    public function getAssetProxy(string $identifier): AssetProxyInterface;

    /**
     * @param AssetTypeFilter $assetType
     */
    public function filterByType(AssetTypeFilter $assetType = null): void;

    /**
     * @return AssetProxyQueryResultInterface
     * @throws AssetSourceConnectionException
     */
    public function findAll(): AssetProxyQueryResultInterface;

    /**
     * @param string $searchTerm
     * @return AssetProxyQueryResultInterface
     */
    public function findBySearchTerm(string $searchTerm): AssetProxyQueryResultInterface;

    /**
     * @param Tag $tag
     * @return AssetProxyQueryResultInterface
     */
    public function findByTag(Tag $tag): AssetProxyQueryResultInterface;

    /**
     * @return AssetProxyQueryResultInterface
     */
    public function findUntagged(): AssetProxyQueryResultInterface;

    /**
     * Count all assets, regardless of tag or collection
     *
     * @return int
     */
    public function countAll(): int;
}
