<?php
/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\MultipleCsvExportAdapter\Plugin\Model\Export\Entity;

use CWSPS154\MultipleCsvExportAdapter\Api\Data\ExportExtendInfoInterface;
use Magento\ImportExport\Model\Export\Entity\ExportInfoFactory as Subject;

class ExportInfoFactory
{
    /**
     * @var int
     */
    protected int $rowCountPerPage;

    /**
     * Before plugin for create to abstract rows_count_per_file from fileFormat
     *
     * @param Subject $subject
     * @param $fileFormat
     * @param $entity
     * @param $exportFilter
     * @param array $skipAttr
     * @param string|null $locale
     * @return array
     */
    public function beforeCreate(
        Subject $subject,
                $fileFormat,
                $entity,
                $exportFilter,
        array   $skipAttr = [],
        ?string $locale = null
    ): array
    {
        $this->rowCountPerPage = (int)$fileFormat['rows_count_per_file'];
        return [$fileFormat['file_format'], $entity, $exportFilter, $skipAttr, $locale];
    }

    /**
     * After plugin for Create to set rows_count_per_file value to ExportExtendInfoInterface object
     *
     * @param Subject $subject
     * @param ExportExtendInfoInterface $exportInfo
     * @return ExportExtendInfoInterface
     */
    public function afterCreate(Subject $subject, ExportExtendInfoInterface $exportInfo): ExportExtendInfoInterface
    {
        $exportInfo->setRowCountPerFile($this->rowCountPerPage);
        return $exportInfo;
    }
}
