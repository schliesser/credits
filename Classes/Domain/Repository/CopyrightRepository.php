<?php

declare(strict_types=1);

namespace Schliesser\Credits\Domain\Repository;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CopyrightRepository
{
    public function __construct(
        protected readonly PageRepository $pageRepository,
        protected readonly FileRepository $fileRepository,
        protected readonly ConnectionPool $connectionPool
    ) {}

    /**
     * @return array<int, array{file: File, pages: non-empty-array<int, int>}>
     */
    public function findBySite(Site $site, string $mimeTypeFilter = 'image/%', int $depth = 100): array
    {
        // first fetch all pages in a specific site
        $pageIds = $this->pageRepository->getDescendantPageIdsRecursive($site->getRootPageId(), $depth);
        $pageIds[] = $site->getRootPageId();

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('sys_file_reference');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));
        $query = $queryBuilder
            ->select('r.*')
            ->from('sys_file_reference', 'r')
            ->leftJoin('r', 'sys_file', 'f', 'r.uid_local = f.uid')
            ->leftJoin('f', 'sys_file_metadata', 'm', 'f.uid = m.file')
            ->where(
                $queryBuilder->expr()->in('r.pid', $queryBuilder->createNamedParameter($pageIds, Connection::PARAM_INT_ARRAY)),
                $queryBuilder->expr()->isNotNull('m.copyright')
            )
            ->orderBy('m.title');

        if (!empty($mimeTypeFilter)) {
            $query->andWhere(
                $queryBuilder->expr()->like('f.mime_type', $queryBuilder->createNamedParameter($mimeTypeFilter, Connection::PARAM_STR)),
            );
        }

        try {
            $fileReferences = $query->executeQuery()->fetchAllAssociative();
        } catch (Exception) {
            return [];
        }

        $result = [];
        foreach ($fileReferences as $fileReference) {
            // Filter corrupt file_references
            if (!is_int($fileReference['uid_local'] ?? null) || !is_int($fileReference['pid'] ?? null)) {
                continue;
            }

            $fileObject = $this->fileRepository->findByUid($fileReference['uid_local']);
            if (array_key_exists($fileObject->getUid(), $result)) {
                $result[$fileObject->getUid()]['pages'][$fileReference['pid']] = $fileReference['pid'];
            } else {
                $result[$fileObject->getUid()] = [
                    'file' => $fileObject,
                    'pages' => [$fileReference['pid'] => $fileReference['pid']],
                ];
            }
        }
        return $result;
    }
}
