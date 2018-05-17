$(document).ready(function() {
    var orderId = $('.js-var').data('order-id'),
        isReadyEl = $('#is-ready-order'),
        isReady = isReadyEl.prop('checked'),
        isDisabled = isReadyEl.prop('disabled'),
        stageOrderEl = $('#form-completed-order').find('#stage_order_name');

    var initUploader = function() {
        $('#uploader').pluploadQueue({
            runtimes: 'html5, flash, silverlight, html4',
            url: '/symfony_system/web/app_dev.php/_uploader/gallery/upload',
            max_file_count: 20,
            max_file_size: '10mb',
            chunks: {
                size: '1mb',
                send_chunk_number: false // set this to true, to send chunk and total chunk numbers instead of offset and total bytes
            },
            rename: true,
            dragdrop: true,
            filters: [
                {title: 'Image files', extensions: 'jpg, gif, png'},
                {title: 'Zip files', extensions: 'zip'}
            ],
            multipart_params: {
                'orderId': orderId,
                //'stageOrderId': stageOrderSelectedId
            },
            views: {
                list: true,
                thumbs: true, // Show thumbs
                active: 'thumbs'
            },
            resize: {
                width: 320,
                height: 240,
                quality: 90,
                crop: true // crop to exact dimensions
            },
            flash_swf_url: 'http://rawgithub.com/moxiecode/moxie/master/bin/flash/Moxie.cdn.swf',
            silverlight_xap_url: 'http://rawgithub.com/moxiecode/moxie/master/bin/silverlight/Moxie.cdn.xap',
            init: {
                FilesAdded: function (up, files) {
                    //console.log(isReady);
                    //console.log(isDisabled);
                    //up.start();
                    //console.log(stageOrderSelectedId);

                    if (isReady === false && isDisabled === false) {
                        isReadyEl.prop('checked', true);
                    }
                },
                FilesRemoved: function (up, files) {
                    if (up.total.queued === 0 && isDisabled === false) {
                        isReadyEl.prop('checked', false);
                    }
                },
                BeforeUpload: function (up, file) {
                   // console.log(up.settings.multipart_params);
                    var stageOrderSelectedId = stageOrderEl.find('option:selected').val();
                    //console.log(stageOrderSelectedId);

                    up.settings.multipart_params.isReady = isReadyEl.prop('checked');
                    up.settings.multipart_params.stageOrderId = stageOrderSelectedId;
                    console.log(up.settings.multipart_params);
                },
                FileUploaded: function (up, file, info) {
                    //console.log(up.settings);

                    var parsedData = $.parseJSON(info.response);

                    if (parsedData.success === true) {
                        var fileOrderData = parsedData[0].fileOrder,
                            stageOrderData = parsedData[0].stageOrder,
                            orderData = parsedData[0].order,
                            orderFileTableEl = $('#order-file-table'),
                            orderFileTableTbodyEl = orderFileTableEl.find('tbody'),
                            cntFiles = orderFileTableTbodyEl.find('tr'),
                            orderStageTableEl = $('#order-stage-table'),
                            stageOrderTrEl = orderStageTableEl.find('tr#' + stageOrderData.id),
                            stageOrderSelectorEl = $('#stage_order_name'),
                            dynamicBlockEl = $('.dynamic-block-order-info');

                        //console.log(parsedData[0]);

                        stageOrderTrEl.find('td:last').text(stageOrderData.status);
                        stageOrderSelectorEl.find('[value="' + stageOrderData.id + '"]').remove();

                        dynamicBlockEl.empty().append(orderData.data);

                        orderFileTableTbodyEl.append('<tr>' +
                            '<td>' + ++cntFiles.length + '</td>' +
                            '<td>' +
                            '<div class="file-icon ' + fileOrderData.extension + '"></div>' +
                            '<div><a href="' + fileOrderData.url + '">' + fileOrderData.name + '</a></div>' +
                            '</td>' +
                            '<td>' + fileOrderData.size + '</td>' +
                            '<td>' + fileOrderData.dateUpload + '</td>' +
                            '</tr>');

                        if (isDisabled === false) {
                            isReadyEl.prop('disabled', true);
                        }
                    }
                },
                UploadComplete: function (up, files) {
                    // destroy the uploader and init a new one
                    up.refresh();
                    initUploader();
                },
                Error: function (up, err) {
                    up.stop();
                    console.log(err);
                }
            }
        });
    };

    initUploader();
});
