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
use Flownative\Media\Browser\AssetSource\SupportsCollections;
use Flownative\Media\Browser\AssetSource\SupportsSorting;
use Flownative\Media\Browser\AssetSource\SupportsTagging;
use Neos\Flow\Annotations\Inject;
use Neos\Flow\ObjectManagement\ObjectManagerInterface;
use Neos\Flow\Persistence\Exception\InvalidQueryException;
use Neos\Flow\Persistence\QueryInterface;
use Neos\Media\Domain\Model\AssetCollection;
use Neos\Media\Domain\Model\AssetInterface;
use Neos\Media\Domain\Model\Tag;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Media\Domain\Repository\AudioRepository;
use Neos\Media\Domain\Repository\DocumentRepository;
use Neos\Media\Domain\Repository\ImageRepository;
use Neos\Media\Domain\Repository\VideoRepository;

final class NeosAssetProxyRepository implements AssetProxyRepository, SupportsSorting, SupportsCollections, SupportsTagging
{
    /**
     * @Inject
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Doctrine's Entity Manager. Note that "ObjectManager" is the name of the related interface ...
     *
     * @Inject
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $entityManager;

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
     * @var AssetCollection
     */
    private $activeAssetCollection;

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
     * Sets the property names to order results by. Expected like this:
     * array(
     *  'foo' => \Neos\Flow\Persistence\QueryInterface::ORDER_ASCENDING,
     *  'bar' => \Neos\Flow\Persistence\QueryInterface::ORDER_DESCENDING
     * )
     *
     * @param array $orderings The property names to order by by default
     * @return void
     * @api
     */
    public function orderBy(array $orderings):void
    {
        $this->assetRepository->setDefaultOrderings($orderings);
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
     * NOTE: This needs to be refactored to use an asset collection identifier instead of Media's domain model before
     *       it can become a public API for other asset sources.
     *
     * @param AssetCollection $assetCollection
     */
    public function filterByCollection(AssetCollection $assetCollection = null): void
    {
        $this->activeAssetCollection = $assetCollection;
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
     * @return AssetProxyQueryResult
     */
    public function findAll(): AssetProxyQueryResult
    {
        $queryResult = $this->assetRepository->findAll($this->activeAssetCollection);
        $query = $this->filterOutImportedAssetsFromOtherAssetSources($queryResult->getQuery());
        return new NeosAssetProxyQueryResult($query->execute(), $this->assetSource);
    }

    /**
     * @param string $searchTerm
     * @return AssetProxyQueryResult
     */
    public function findBySearchTerm(string $searchTerm): AssetProxyQueryResult
    {
        $queryResult = $this->assetRepository->findBySearchTermOrTags($searchTerm, [], $this->activeAssetCollection);
        $query = $this->filterOutImportedAssetsFromOtherAssetSources($queryResult->getQuery());
        return new NeosAssetProxyQueryResult($query->execute(), $this->assetSource);
    }

    /**
     * @param Tag $tag
     * @return AssetProxyQueryResult
     */
    public function findByTag(Tag $tag): AssetProxyQueryResult
    {
        $queryResult = $this->assetRepository->findByTag($tag, $this->activeAssetCollection);
        $query = $this->filterOutImportedAssetsFromOtherAssetSources($queryResult->getQuery());
        return new NeosAssetProxyQueryResult($query->execute(), $this->assetSource);
    }

    /**
     * @return AssetProxyQueryResult
     */
    public function findUntagged(): AssetProxyQueryResult
    {
        $queryResult = $this->assetRepository->findUntagged($this->activeAssetCollection);
        $query = $this->filterOutImportedAssetsFromOtherAssetSources($queryResult->getQuery());
        return new NeosAssetProxyQueryResult($query->execute(), $this->assetSource);
    }

    /**
     * @return int
     */
    public function countAll(): int
    {
        $query = $this->filterOutImportedAssetsFromOtherAssetSources($this->assetRepository->createQuery());
        $query = $this->filterOutAssetsFromOtherAssetCollections($query);
        return $query->count();
    }

    /**
     * @return int
     */
    public function countUntagged(): int
    {
        $query = $this->assetRepository->createQuery();
        try {
            $query->matching($query->isEmpty('tags'));
        } catch (InvalidQueryException $e) {
        }

        $query = $this->filterOutImportedAssetsFromOtherAssetSources($query);
        $query = $this->filterOutAssetsFromOtherAssetCollections($query);
        $query = $this->filterOutImageVariants($query);
        return $query->count();
    }

    /**
     * @param QueryInterface $query
     * @return QueryInterface
     */
    private function filterOutImportedAssetsFromOtherAssetSources(QueryInterface $query): QueryInterface
    {
        $constraint = $query->getConstraint();
        $query->matching(
            $query->logicalAnd([
                $constraint,
                $query->equals('assetSourceIdentifier', 'neos')
            ])
        );
        return $query;
    }

    /**
     * @param QueryInterface $query
     * @return QueryInterface
     */
    private function filterOutImageVariants(QueryInterface $query): QueryInterface
    {
        $queryBuilder = $query->getQueryBuilder();
        $queryBuilder->andWhere('e NOT INSTANCE OF Neos\Media\Domain\Model\ImageVariant');
        return $query;
    }

    /**
     * @param QueryInterface $query
     * @return QueryInterface
     */
    private function filterOutAssetsFromOtherAssetCollections(QueryInterface $query): QueryInterface
    {
        if ($this->activeAssetCollection === null) {
            return $query;
        }

        $constraints = $query->getConstraint();
        try {
            $query->matching(
                $query->logicalAnd([
                    $constraints,
                    $query->contains('assetCollections', $this->activeAssetCollection)
                ])
            );
        } catch (InvalidQueryException $e) {
        }
        return $query;
    }
}
