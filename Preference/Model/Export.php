<?php
/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\MultipleCsvExportAdapter\Preference\Model;

use CWSPS154\MultipleCsvExportAdapter\Model\Export\Adapter\MultipleCsv;
use Magento\Framework\Exception\LocalizedException;
use Magento\ImportExport\Model\Export as CoreExport;

class Export extends CoreExport
{
    /**
     * Export data.
     * Updated to handle MultipleCsv adapter
     *
     * @return array|string
     * @throws LocalizedException
     */
    public function export(): array|string
    {
        if (isset($this->_data[self::FILTER_ELEMENT_GROUP])) {
            $this->addLogComment(__('Begin export of %1', $this->getEntity()));
            if ($this->getData('file_format') === MultipleCsv::TYPE) {
                $this->_getWriter()->setRowCountPerFile($this->getData('row_count_per_file'));
            }
            $result = $this->_getEntityAdapter()->setWriter($this->_getWriter())->export();
            $countRows = 0;
            if (is_string($result)) {
                $countRows = substr_count($result, "\n");
            } elseif (is_array($result)) {
                $countRows = count($result);
            }
            if ($countRows === 0) {
                throw new LocalizedException(__('There is no data for the export.'));
            }
            if ($result) {
                $message = is_array($result) ? __('Exported %1 pages.', $countRows) : __('Exported %1 rows.', $countRows);
                $this->addLogComment([$message, __('The export is finished.')]);
            }
            return $result;
        } else {
            throw new LocalizedException(__('Please provide filter data.'));
        }
    }
}
