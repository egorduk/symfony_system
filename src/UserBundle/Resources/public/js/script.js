$(document).ready(function() {
    var orderId = $('.js-var').data('order-id');

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
                'orderId': orderId
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
                    console.log(files);
                    //up.start();
                },
                FileUploaded: function (up, file, info) {
                    console.log(file);

                    var parsedData = $.parseJSON(info.response);

                    if (parsedData.success === true) {
                        var orderFileTableEl = $('#order-table-files'),
                            orderFileTableTbodyEl = orderFileTableEl.find('tbody'),
                            cntFiles = orderFileTableTbodyEl.find('tr');

                        file = parsedData[0];

                        orderFileTableTbodyEl.append('<tr>' +
                            '<td>' + ++cntFiles.length + '</td>' +
                            '<td>' +
                            '<div class="file-icon ' + file.extension + '"></div>' +
                            '<div><a href="' + file.url + '">' + file.name + '</a></div>' +
                            '</td>' +
                            '<td>' + file.size + '</td>' +
                            '<td>' + file.dateUpload + '</td>' +
                            '</tr>');
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
