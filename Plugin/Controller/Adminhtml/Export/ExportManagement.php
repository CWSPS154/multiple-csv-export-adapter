<?php
/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\MultipleCsvExportAdapter\Plugin\Controller\Adminhtml\Export;

use Closure;
use CWSPS154\MultipleCsvExportAdapter\Model\Export\Adapter\MultipleCsv;
use Magento\Framework\EntityManager\HydratorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\ImportExport\Api\Data\ExportInfoInterface;
use Magento\ImportExport\Api\ExportManagementInterface;
use Magento\ImportExport\Model\ExportFactory;

class ExportManagement
{

    /**
     * @var ExportFactory
     */
    private ExportFactory $exportModelFactory;

    /**
     * @var HydratorInterface
     */
    private HydratorInterface $hydrator;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * ExportManagement constructor.
     * @param ExportFactory $exportModelFactory
     * @param HydratorInterface $hydrator
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ExportFactory $exportModelFactory,
        HydratorInterface $hydrator,
        SerializerInterface $serializer
    ) {
        $this->exportModelFactory = $exportModelFactory;
        $this->hydrator = $hydrator;
        $this->serializer = $serializer;
    }

    /**
     * Around plugin for export to set row count per file
     *
     * @param ExportManagementInterface $subject
     * @param Closure $proceed
     * @param ExportInfoInterface $exportInfo
     * @return string|array
     * @throws LocalizedException
     */
    public function aroundExport(ExportManagementInterface $subject, Closure $proceed, ExportInfoInterface $exportInfo): array|string
    {
        $arrData = $this->hydrator->extract($exportInfo);
        $arrData['export_filter'] = $this->serializer->unserialize($arrData['export_filter']);
        /** @var \Magento\ImportExport\Model\Export $exportModel */
        $exportModel = $this->exportModelFactory->create();
        $exportModel->setData($arrData);
        if ($exportInfo->getFileFormat() === MultipleCsv::TYPE) {
            $exportModel->setRowCountPerFile($exportInfo->getRowCountPerFile());
        }
        return $exportModel->export();
    }
}
