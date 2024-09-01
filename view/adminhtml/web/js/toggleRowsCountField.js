/*
 * Copyright CWSPS154. All rights reserved.
 * @auth CWSPS154
 * @link  https://github.com/CWSPS154
 */
require(['jquery'], function ($) {
    window.toggleRowsCountField = function (fileFormat) {
        if (fileFormat === 'multiple_csv') {
            $('.rows_count_per_file').closest('.field').show();
        } else {
            $('.rows_count_per_file').closest('.field').hide();
        }
    };
    $(document).ready(function () {
        let fileFormat = $('#file_format').val();
        toggleRowsCountField(fileFormat);
    });
});
