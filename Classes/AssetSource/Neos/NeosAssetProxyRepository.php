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

use Flownative\Media\Browser\AssetSource\AssetNotFoundException;
use Flownative\Media\Browser\AssetSource\AssetProxy;
use Flownative\Media\Browser\AssetSource\AssetProxyQueryResult;
use Flownative\Media\Browser\AssetSource\AssetProxyRepository;
use Flownative\Media\Browser\AssetSource\AssetTypeFilter;
use Neos\Flow\Annotations\Inject;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\Tag;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Media\Domain\Repository\AudioRepository;
use Neos\Media\Domain\Repository\DocumentRepository;
use Neos\Media\Domain\Repository\ImageRepository;
use Neos\Media\Domain\Repository\VideoRepository;

final class NeosAssetProxyRepository implements AssetProxyRepository
{
    /**
     * @Inject
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var NeosAssetSource
     */
    private $assetSource;

    /**
     * @var AssetRepository
     */
    private $assetRepository;

    /**
     * @var string
     */
    private $assetTypeFilter = 'All';

    /**
     * @var array
     */
    private $assetRepositoryClassNames = [
        'All' => AssetRepository::class,
        'Image' => ImageRepository::class,
        'Document' => DocumentRepository::class,
        'Video' => VideoRepository::class,
        'Audio' => AudioRepository::class
    ];

    /**
     * @param NeosAssetSource $assetSource
     */
    public function __construct(NeosAssetSource $assetSource)
    {
        $this->assetSource = $assetSource;
    }

    /**
     * @return void
     */
    public function initializeObject(): void
    {
        $this->assetRepository = $this->objectManager->get($this->assetRepositoryClassNames[$this->assetTypeFilter]);
    }

    /**
     * @param string $identifier
     * @return AssetProxy
     * @throws AssetNotFoundException
     */
    public function getAssetProxy(string $identifier): AssetProxy
    {
        $asset = $this->assetRepository->findByIdentifier($identifier);
        if ($asset === null || !$asset instanceof AssetInterface) {
            throw new AssetNotFoundException('The specified asset was not found.', 1509632861103);
        }
        return new NeosAssetProxy($asset, $this->assetSource);
    }

    /**
     * @param AssetTypeFilter $assetType
     */
    public function filterByType(AssetTypeFilter $assetType = null): void
    {
        $this->assetTypeFilter = (string)$assetType ?: 'All';
        $this->initializeObject();
    }

    /**
     * @return AssetProxyQueryResult
     */
    public function findAll(): AssetProxyQueryResult
    {
        return new NeosAssetProxyQueryResult($this->assetRepository->findAll(), $this->assetSource);
    }

    /**
     * @param string $searchTerm
     * @return AssetProxyQueryResult
     */
    public function findBySearchTerm(string $searchTerm): AssetProxyQueryResult
    {
        return new NeosAssetProxyQueryResult($this->assetRepository->findBySearchTermOrTags($searchTerm, []), $this->assetSource);
    }

    /**
     * @param Tag $tag
     * @return AssetProxyQueryResult
     */
    public function findByTag(Tag $tag): AssetProxyQueryResult
    {
        return new NeosAssetProxyQueryResult($this->assetRepository->findByTag($tag), $this->assetSource);
    }

    /**
     * @return AssetProxyQueryResult
     */
    public function findUntagged(): AssetProxyQueryResult
    {
        return new NeosAssetProxyQueryResult($this->assetRepository->findUntagged(), $this->assetSource);
    }

    /**
     * @return int
     */
    public function countAll(): int
    {
        return $this->assetRepository->countAll();
    }
}
