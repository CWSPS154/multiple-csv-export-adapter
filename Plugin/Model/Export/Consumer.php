<?php
/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\MultipleCsvExportAdapter\Plugin\Model\Export;

use CWSPS154\MultipleCsvExportAdapter\Api\Data\ExportExtendInfoInterface;
use CWSPS154\MultipleCsvExportAdapter\Model\Export\Adapter\MultipleCsv;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Notification\NotifierInterface;
use Magento\ImportExport\Api\ExportManagementInterface;
use Magento\ImportExport\Model\Export\Consumer as Subject;
use Psr\Log\LoggerInterface;

class Consumer
{
    /**
     * @var NotifierInterface
     */
    private NotifierInterface $notifier;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ExportManagementInterface
     */
    private ExportManagementInterface $exportManager;

    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * @var ResolverInterface
     */
    private ResolverInterface $localeResolver;

    /**
     * Consumer constructor.
     * @param LoggerInterface $logger
     * @param ExportManagementInterface $exportManager
     * @param Filesystem $filesystem
     * @param NotifierInterface $notifier
     * @param ResolverInterface $localeResolver
     */
    public function __construct(
        LoggerInterface $logger,
        ExportManagementInterface $exportManager,
        Filesystem $filesystem,
        NotifierInterface $notifier,
        ResolverInterface $localeResolver
    ) {
        $this->logger = $logger;
        $this->exportManager = $exportManager;
        $this->filesystem = $filesystem;
        $this->notifier = $notifier;
        $this->localeResolver = $localeResolver;
    }

    /**
     * Around plugin for export consumer to handle MultipleCsv adapter
     *
     * @param Subject $subject
     * @param callable $proceed
     * @param ExportExtendInfoInterface $exportInfo
     * @return void
     */
    public function aroundProcess(Subject $subject, callable $proceed, ExportExtendInfoInterface $exportInfo): void
    {
        if ($exportInfo->getFileFormat() === MultipleCsv::TYPE) {
            $currentLocale = $this->localeResolver->getLocale();
            if ($exportInfo->getLocale()) {
                $this->localeResolver->setLocale($exportInfo->getLocale());
            }

            try {
                $multipleFileData = (array)$this->exportManager->export($exportInfo);
                $i = 1;
                $fileName = $exportInfo->getFileName();
                foreach ($multipleFileData as $data) {
                    sleep(3);
                    $newFileName = str_replace('.csv', '_part_'.$i++.'.csv', $fileName);
                    $directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_IMPORT_EXPORT);
                    $directory->writeFile('export/' . $newFileName, $data);
                }

                $this->notifier->addMajor(
                    __('Your export file is ready'),
                    __('You can pick up your file at export main page')
                );
            } catch (FileSystemException $exception) {
                $this->notifier->addCritical(
                    __('Error during export process occurred'),
                    __('Error during export process occurred. Please check logs for detail')
                );
                $this->logger->critical('Something went wrong while export process. ' . $exception->getMessage());
            } finally {
                $this->localeResolver->setLocale($currentLocale);
            }
        } else {
            $proceed($exportInfo);
        }
    }
}
