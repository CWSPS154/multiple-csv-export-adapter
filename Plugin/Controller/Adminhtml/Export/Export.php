<?php
/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\MultipleCsvExportAdapter\Plugin\Controller\Adminhtml\Export;

use Magento\ImportExport\Controller\Adminhtml\Export\Export as Subject;

class Export
{
    /**
     * Set row count per file parameter in file format
     *
     * @param Subject $subject
     * @param array $params
     * @return array
     */
    public function afterGetRequestParameters(Subject $subject, array $params): array
    {
        $fileFormat = $params['file_format'];
        $rowsCountPerFile = $params['rows_count_per_file'];
        $params['file_format'] = [
            'file_format' => $fileFormat,
            'rows_count_per_file' => $rowsCountPerFile
        ];
        return $params;
    }
}
