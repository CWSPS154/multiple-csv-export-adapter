<?php
/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\MultipleCsvExportAdapter\Api\Data;

use Magento\ImportExport\Api\Data\LocalizedExportInfoInterface;

interface ExportExtendInfoInterface extends LocalizedExportInfoInterface
{
    /**
     * Set row count per file
     *
     * @param int $rowCountPerFile
     * @return void
     */
    public function setRowCountPerFile(int $rowCountPerFile): void;

    /**
     * Get row count per file
     *
     * @return int
     */
    public function getRowCountPerFile(): int;
}
