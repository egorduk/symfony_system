$(document).ready(function() {
    //var initUploader = function() {
        var uploader = new plupload.Uploader({
            runtimes: 'html5, flash, silverlight, html4',
            url: '/symfony_system/web/app_dev.php/_uploader/gallery/upload',
            max_file_count: 1,
            browse_button : 'pickFile',
            container: document.getElementById('uploader'),
            chunks: {
                size: '1mb',
                send_chunk_number: false
            },
            rename: true,
            multi_selection: false,
            unique_names : true,
            filters : {
                max_file_size : '3mb',
                mime_types: [
                    {title : 'Image files', extensions : 'jpg, gif, png'}
                ]
            },
            resize: {
                width: 320,
                height: 240,
                quality: 90,
                crop: true
            },
            flash_swf_url: 'http://rawgithub.com/moxiecode/moxie/master/bin/flash/Moxie.cdn.swf',
            silverlight_xap_url: 'http://rawgithub.com/moxiecode/moxie/master/bin/silverlight/Moxie.cdn.xap',
           /* init: {
                BeforeUpload: function (up, file) {
                    up.settings.multipart_params.avatarId = $('#profile_form_avatar').find('option:selected').val();
                },
                FileUploaded: function (up, file, info) {
                    var parsedData = $.parseJSON(info.response);

                    if (parsedData.success === true) {
                        var responseData = parsedData[0].data,
                            orderFileTableEl = $('#order-file-table');
                    }
                },
                UploadComplete: function (up, files) {
                    up.refresh();
                    initUploader();
                },
            }*/
            init: {
                PostInit: function() {
                    document.getElementById('fileList').innerHTML = '';
                    document.getElementById('uploadFile').onclick = function() {
                        uploader.start();
                        return false;
                    };
                },
                FilesAdded: function(up, files) {
                    plupload.each(files, function(file) {
                        //if (up.total.queued == 1) {
                            document.getElementById('fileList').innerHTML = '';
                            document.getElementById('fileList').innerHTML = '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                        //}/* else {
                            up.removeFile(files);
                        //}*/
                    });
                },
                UploadProgress: function(up, file) {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                },
                Error: function(up, err) {
                    up.stop();
                    console.log(err);
                }
            }
        });

    uploader.init();

    /*$('#delete-file').on('click', function () {
        uploader.files
    });*/

    //initUploader();
});
