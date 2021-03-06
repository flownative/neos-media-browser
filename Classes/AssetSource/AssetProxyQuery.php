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

interface AssetProxyQuery
{
    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void;

    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void;

    /**
     * @return int
     */
    public function getLimit(): int;

    /**
     * @param string $searchTerm
     */
    public function setSearchTerm(string $searchTerm);

    /**
     * @return string
     */
    public function getSearchTerm();

    /**
     * @return AssetProxyQueryResult
     */
    public function execute(): AssetProxyQueryResult;

    /**
     * @return int
     */
    public function count(): int;
}
