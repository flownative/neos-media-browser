<?php

namespace Flownative\Media\Browser\Controller;

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
use Flownative\Media\Browser\AssetSource\AssetSource;
use Flownative\Media\Browser\AssetSource\AssetSourceConnectionException;
use Flownative\Media\Browser\AssetSource\HasRemoteOriginal;
use Flownative\Media\Browser\AssetSource\MediaAssetSourceAware;
use Flownative\Media\Browser\Domain\Model\ImportedAsset;
use Flownative\Media\Browser\Domain\Repository\ImportedAssetRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\ResourceManagement\Exception;
use Neos\Flow\ResourceManagement\ResourceManager;
use Neos\Media\Domain\Model\Asset;
use Neos\Media\Domain\Repository\AssetRepository;
use Neos\Media\Domain\Strategy\AssetModelMappingStrategyInterface;

/**
 * @Flow\Scope("singleton")
 */
class AssetProxyController extends ActionController
{
    /**
     * @Flow\InjectConfiguration(path="assetSources")
     * @var array
     */
    protected $assetSourcesConfiguration;

    /**
     * @var AssetSource[]
     */
    protected $assetSources = [];

    /**
     * @Flow\Inject
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @Flow\Inject
     * @var AssetRepository
     */
    protected $assetRepository;

    /**
     * @Flow\Inject
     * @var ImportedAssetRepository
     */
    protected $importedAssetRepository;

    /**
     * @Flow\Inject
     * @var AssetModelMappingStrategyInterface
     */
    protected $assetModelMappingStrategy;

    /**
     * @return void
     */
    public function initializeObject()
    {
        foreach ($this->assetSourcesConfiguration as $assetSourceIdentifier => $assetSourceConfiguration) {
            if (is_array($assetSourceConfiguration)) {
                $this->assetSources[$assetSourceIdentifier] = new $assetSourceConfiguration['assetSource']($assetSourceIdentifier, $assetSourceConfiguration['assetSourceOptions']);
            }
        }
    }

    /**
     * @param string $assetSourceIdentifier
     * @param string $assetIdentifier
     * @return string
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function importAction(string $assetSourceIdentifier, string $assetIdentifier)
    {
        $this->response->setHeader('Content-Type', 'application/json');

        if (!isset($this->assetSources[$assetSourceIdentifier])) {
            $this->response->setStatus(404);
            return '';
        }

        $assetProxyRepository = $this->assetSources[$assetSourceIdentifier]->getAssetProxyRepository();
        try {
            $assetProxy = $assetProxyRepository->getAssetProxy($assetIdentifier);
        } catch (AssetNotFoundException $e) {
            $this->response->setStatus(404);
            return '';
        } catch (AssetSourceConnectionException $e) {
            $this->response->setStatus(500, 'Connection to asset source failed');
            return '';
        }

        if (!$assetProxy instanceof HasRemoteOriginal) {
            $this->response->setStatus(400, 'Cannot import asset which does not have a remote original');
            return '';
        }

        $importedAsset = $this->importedAssetRepository->findOneByAssetSourceIdentifierAndRemoteAssetIdentifier($assetSourceIdentifier, $assetIdentifier);
        if (!$importedAsset instanceof ImportedAsset) {
            try {
                $assetResource = $this->resourceManager->importResource($assetProxy->getOriginalUri());
                $assetResource->setFilename($assetProxy->getFilename());
            } catch (Exception $e) {
                $this->response->setStatus(500, 'Failed importing the asset from the original source.');
                return '';
            }

            /** @var Asset $asset */
            $assetModelClassName = $this->assetModelMappingStrategy->map($assetResource);
            $asset = new $assetModelClassName($assetResource);
            $asset->setAssetSourceIdentifier($assetSourceIdentifier);
            if ($asset instanceof MediaAssetSourceAware) {
            }
            $this->assetRepository->add($asset);

            $localAssetIdentifier = $this->persistenceManager->getIdentifierByObject($asset);

            $importedAsset = new ImportedAsset(
                $assetSourceIdentifier,
                $assetIdentifier,
                $localAssetIdentifier,
                new \DateTimeImmutable()
            );
            $this->importedAssetRepository->add($importedAsset);
        }

        $assetProxy = new \stdClass();
        $assetProxy->localAssetIdentifier = $importedAsset->getLocalAssetIdentifier();

        return json_encode($assetProxy);
    }
}
