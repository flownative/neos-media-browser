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

use Flownative\Media\Browser\Domain\Repository\ImportedAssetRepository;
use Neos\Flow\Annotations\Inject;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Media\Domain\Model\AssetInterface;

class ImportedAssetManager
{
    /**
     * @Inject()
     * @var  PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @Inject()
     * @var ImportedAssetRepository
     */
    protected $importedAssetRepository;

    /**
     * @param AssetInterface $asset
     * @throws \Neos\Flow\Persistence\Exception\IllegalObjectTypeException
     */
    public function registerRemovedAsset(AssetInterface $asset)
    {
        $assetIdentifier = $this->persistenceManager->getIdentifierByObject($asset);
        $importedAsset = $this->importedAssetRepository->findOneByLocalAssetIdentifier($assetIdentifier);
        if ($importedAsset !== null) {
            $this->importedAssetRepository->remove($importedAsset);
        }
    }
}
