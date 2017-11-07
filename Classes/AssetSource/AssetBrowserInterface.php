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

interface AssetBrowserInterface
{
    /**
     * @param string $identifier
     * @return AssetProxyInterface
     * @throws AssetNotFoundException
     * @throws AssetSourceConnectionException
     */
    public function getAssetProxy(string $identifier): AssetProxyInterface;

    /**
     * @return array
     * @throws AssetSourceConnectionException
     */
    public function findAll(): array;

    /**
     * @param string $searchTerm
     * @return array
     */
    public function findBySearchTerm(string $searchTerm): array;
}
