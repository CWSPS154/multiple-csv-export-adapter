<?php
/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\MultipleCsvExportAdapter\Preference\Model\Export\Entity;

use CWSPS154\MultipleCsvExportAdapter\Api\Data\ExportExtendInfoInterface;
use Magento\ImportExport\Model\Export\Entity\ExportInfo;

class ExportExtendInfo extends ExportInfo implements ExportExtendInfoInterface
{
    /**
     * @var int
     */
    private int $rowCountPerFile;

    /**
     * @inheritDoc
     */
    public function setRowCountPerFile(int $rowCountPerFile): void
    {
        $this->rowCountPerFile = $rowCountPerFile;
    }

    /**
     * @inheritDoc
     */
    public function getRowCountPerFile(): int
    {
        return $this->rowCountPerFile;
    }
}
