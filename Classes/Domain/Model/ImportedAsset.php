<?php
namespace Flownative\Media\Browser\Domain\Model;

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

use Neos\Flow\Annotations\Entity;
use Neos\Flow\Annotations\Identity;
use Neos\Media\Domain\Model\Asset;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 */
final class ImportedAsset
{
    /**
     * @Identity()
     * @var string
     */
    protected $assetSourceIdentifier;

    /**
     * @Identity()
     * @var string
     */
    protected $remoteAssetIdentifier;

    /**
     * @ORM\OneToOne(orphanRemoval = true, cascade={"all"})
     * @var Asset
     */
    protected $localAsset;

    /**
     * @var \DateTimeImmutable
     */
    protected $importedAt;

    /**
     * @param string $assetSourceIdentifier
     * @param string $remoteAssetIdentifier
     * @param Asset $localAsset
     * @param \DateTimeImmutable $importedAt
     */
    public function __construct(
        string $assetSourceIdentifier,
        string $remoteAssetIdentifier,
        Asset $localAsset,
        \DateTimeImmutable $importedAt
    ) {
        $this->assetSourceIdentifier = $assetSourceIdentifier;
        $this->remoteAssetIdentifier = $remoteAssetIdentifier;
        $this->localAsset = $localAsset;
        $this->importedAt = $importedAt;
    }

    /**
     * @return string
     */
    public function getAssetSourceIdentifier(): string
    {
        return $this->assetSourceIdentifier;
    }

    /**
     * @param string $assetSourceIdentifier
     */
    public function setAssetSourceIdentifier(string $assetSourceIdentifier): void
    {
        $this->assetSourceIdentifier = $assetSourceIdentifier;
    }

    /**
     * @return string
     */
    public function getRemoteAssetIdentifier(): string
    {
        return $this->remoteAssetIdentifier;
    }

    /**
     * @param string $remoteAssetIdentifier
     */
    public function setRemoteAssetIdentifier(string $remoteAssetIdentifier): void
    {
        $this->remoteAssetIdentifier = $remoteAssetIdentifier;
    }

    /**
     * @return Asset
     */
    public function getLocalAsset(): Asset
    {
        return $this->localAsset;
    }

    /**
     * @param Asset $localAsset
     */
    public function setLocalAsset(Asset $localAsset): void
    {
        $this->localAsset = $localAsset;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getImportedAt(): \DateTimeImmutable
    {
        return $this->importedAt;
    }

    /**
     * @param \DateTimeImmutable $importedAt
     */
    public function setImportedAt(\DateTimeImmutable $importedAt): void
    {
        $this->importedAt = $importedAt;
    }
}
