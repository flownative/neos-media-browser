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

interface AssetProxyQueryResult extends \Countable, \Iterator, \ArrayAccess
{
    /**
     * Returns a clone of the query object
     *
     * @return AssetProxyQuery
     */
    public function getQuery(): AssetProxyQuery;

    /**
     * Returns the first asset proxy in the result set
     *
     * @return AssetProxy|null
     */
    public function getFirst(): ?AssetProxy;

    /**
     * Returns an array with the asset proxies in the result set
     *
     * @return AssetProxy[]
     */
    public function toArray(): array;
}
