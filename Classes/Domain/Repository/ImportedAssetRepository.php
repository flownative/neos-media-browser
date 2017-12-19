<?php
namespace Flownative\Media\Browser\Domain\Repository;

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

use Neos\Flow\Persistence\Repository;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
final class ImportedAssetRepository extends Repository
{
    /**
     * @param string $assetSourceIdentifier
     * @param string $remoteAssetIdentifier
     * @return object
     */
    public function findOneByAssetSourceIdentifierAndRemoteAssetIdentifier(string $assetSourceIdentifier, string $remoteAssetIdentifier)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('assetSourceIdentifier', $assetSourceIdentifier),
                $query->equals('remoteAssetIdentifier', $remoteAssetIdentifier)
            )
        );
        return $query->execute()->getFirst();
    }
}