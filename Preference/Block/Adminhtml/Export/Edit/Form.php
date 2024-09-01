<?php
/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */

declare(strict_types=1);

namespace CWSPS154\MultipleCsvExportAdapter\Preference\Block\Adminhtml\Export\Edit;

use Magento\Framework\Exception\LocalizedException;
use Magento\ImportExport\Block\Adminhtml\Export\Edit\Form as CoreForm;

class Form extends CoreForm
{
    /**
     * Prepare form before rendering HTML.
     * Adding rows_count_per_file field
     *
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getUrl('adminhtml/*/getFilter'),
                    'method' => 'post',
                ],
            ]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Export Settings')]);
        $fieldset->addField(
            'entity',
            'select',
            [
                'name' => 'entity',
                'title' => __('Entity Type'),
                'label' => __('Entity Type'),
                'required' => false,
                'onchange' => 'varienExport.getFilter();',
                'values' => $this->_entityFactory->create()->toOptionArray()
            ]
        );
        $fieldset->addField(
            'file_format',
            'select',
            [
                'name' => 'file_format',
                'title' => __('Export File Format'),
                'label' => __('Export File Format'),
                'required' => false,
                'values' => $this->_formatFactory->create()->toOptionArray(),
                'onchange' => 'toggleRowsCountField(this.value);'
            ]
        );
        $fieldset->addField(
            'rows_count_per_file',
            'text',
            [
                'name' => 'rows_count_per_file',
                'title' => __('Rows Count Per File'),
                'label' => __('Rows Count Per File'),
                'required' => false,
                'value' => 100000,
                'class' => 'rows_count_per_file',
                'note' => 'Enter a value greater than or equal to 1000',
            ]
        );
        $fieldset->addField(
            \Magento\ImportExport\Model\Export::FIELDS_ENCLOSURE,
            'checkbox',
            [
                'name' => \Magento\ImportExport\Model\Export::FIELDS_ENCLOSURE,
                'label' => __('Fields Enclosure'),
                'title' => __('Fields Enclosure'),
                'value' => 1,
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return $this;
    }
}
