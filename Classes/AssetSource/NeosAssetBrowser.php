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

use Neos\Flow\Annotations\Inject;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Repository\AssetRepository;

final class NeosAssetBrowser implements AssetBrowserInterface
{
    /**
     * @Inject()
     * @var AssetRepository
     */
    protected $assetRepository;

    /**
     * @param string $identifier
     * @return AssetProxyInterface
     * @throws AssetNotFoundException
     */
    public function getAssetProxy(string $identifier): AssetProxyInterface
    {
        $asset = $this->assetRepository->findByIdentifier($identifier);
        if ($asset === null || !$asset instanceof AssetInterface) {
            throw new AssetNotFoundException('The specified asset was not found.', 1509632861103);
        }
        return new NeosAssetProxy($asset);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $assetProxies = [];
        foreach ($this->assetRepository->findAll() as $asset) {
            $assetProxies[] = new NeosAssetProxy($asset);
        }
        return $assetProxies;
    }

    /**
     * @param string $searchTerm
     * @return array
     */
    public function findBySearchTerm(string $searchTerm): array
    {
        $assetProxies = [];
        foreach ($this->assetRepository->findBySearchTermOrTags($searchTerm, []) as $asset) {
            $assetProxies[] = new NeosAssetProxy($asset);
        }
        return $assetProxies;
    }
}
