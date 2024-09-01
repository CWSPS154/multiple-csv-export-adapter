<?php
/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

namespace CWSPS154\MultipleCsvExportAdapter\Model\Export\Adapter;

use Exception;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\ValidatorException;
use Magento\ImportExport\Model\Export\Adapter\Csv;

class MultipleCsv extends Csv
{
    public const TYPE = 'multiple_csv';

    /**
     * @var int
     */
    protected static int $rowCount = 1;
    /**
     * @var int
     */
    protected static int $fileCount = 1;

    /**
     * @var array
     */
    protected array $destinations = [];

    /**
     * Row count per file
     *
     * @var int
     */
    public int $rowCountPerFile = 0;

    /**
     * Get contents of export file
     *
     * @return array
     * @throws FileSystemException
     * @throws ValidatorException
     */
    public function getContents(): array
    {
        $contents = [];
        foreach ($this->destinations as $destination) {
            $contents[] = $this->_directoryHandle->readFile($destination);
        }
        return $contents;
    }

    /**
     * @param array $rowData
     * @return MultipleCsv|$this
     * @throws FileSystemException
     * @throws LocalizedException
     * @throws ValidatorException
     * @throws Exception
     */
    public function writeRow(array $rowData): MultipleCsv|static
    {
        $this->destinations[self::$fileCount] = $this->_destination;
        if ($this->getRowCountPerFile() == self::$rowCount) {
            unset($this->_destination);
            $this->setNewDestination();
            $this->_init();
            self::$rowCount = 1;
            self::$fileCount++;
            parent::writeRow(array_combine(array_keys($rowData), array_keys($rowData)));
        }
        parent::writeRow($rowData);
        self::$rowCount++;
        return $this;
    }

    /**
     * @return void
     * @throws FileSystemException
     * @throws LocalizedException
     * @throws ValidatorException
     */
    protected function setNewDestination(): void
    {
        $destination = uniqid('importexport_');
        $this->_directoryHandle->touch($destination);

        if (!$this->_directoryHandle->isWritable()) {
            throw new LocalizedException(__('The destination directory is not writable.'));
        }
        if ($this->_directoryHandle->isFile($destination) && !$this->_directoryHandle->isWritable($destination)) {
            throw new LocalizedException(__('Destination file is not writable'));
        }
        $this->_destination = $destination;
    }

    /**
     * Clean cached values
     *
     * @return void
     * @throws FileSystemException
     * @throws ValidatorException
     */
    public function destruct()
    {
        if (is_object($this->_fileHandler)) {
            $this->_fileHandler->close();
            $this->resolveDestinations();
        }
    }

    /**
     * Remove temporary destination
     *
     * @return void
     * @throws FileSystemException
     * @throws ValidatorException
     */
    private function resolveDestinations(): void
    {
        foreach ($this->destinations as $destination) {
            if (!str_contains($destination, '/')) {
                $this->_directoryHandle->delete($destination);
            }
        }
        if (!str_contains($this->_destination, '/')) {
            $this->_directoryHandle->delete($this->_destination);
        }
    }


    /**
     * Set the row count per file
     *
     * @param int $rowCountPerPage
     * @return $this
     */
    public function setRowCountPerFile(int $rowCountPerPage): static
    {
        $this->rowCountPerFile = $rowCountPerPage;
        return $this;
    }

    /**
     * Get the row count per file
     *
     * @return int
     */
    public function getRowCountPerFile(): int
    {
        return $this->rowCountPerFile;
    }
}
