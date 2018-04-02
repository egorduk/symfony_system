$(document).ready(function() {
    /*$('#fileupload').plupload({
        runtimes: 'html5',
        browse_button : 'pickfiles',
        url: "{{ oneup_uploader_endpoint('gallery') }}"
    });*/

    /*var uploader = new plupload.Uploader({
        runtimes : 'html5, flash, silverlight, html4',
        browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
        url: '/symfony_system/web/app_dev.php/_uploader/gallery/upload',
        filters : {
            max_file_size : '10mb',
            max_file_count: 20,
            chunk_size: '1mb',
            mime_types: [
                {title : 'Image files', extensions : 'jpg, gif, png'},
                {title : 'Zip files', extensions : 'zip'}
            ]
        },
        /*resize : {
            width: 200,
            height: 200,
            quality: 90,
            crop: true // crop to exact dimensions
        },*/

    //});

    /*uploader.init();

    uploader.bind('FilesAdded', function(up, files) {
        var html = '';

        plupload.each(files, function(file) {
            //console.log(file);
            html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') ' + file.type + ' <b></b></li>';
        });
        document.getElementById('filelist').innerHTML += html;
    });

    uploader.bind('UploadProgress', function(up, file) {
        document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
    });

    uploader.bind('Error', function(up, err) {
        document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
    });

    document.getElementById('start-upload').onclick = function() {
        uploader.start();
    };*/

    /*$("#uploader").plupload({
        // General settings
        runtimes: 'html5',

        // Fake server response here
        // url : '../upload.php',
        url: "/symfony_system/web/app_dev.php/_uploader/gallery/upload",

        // Maximum file size
        max_file_size: '1000mb',

        // User can upload no more then 20 files in one go (sets multiple_queues to false)
        max_file_count: 20,

        chunk_size: '1mb',

        // Resize images on clientside if we can
        resize : {
            width: 200,
            height: 200,
            quality: 90,
            crop: true // crop to exact dimensions
        },

        // Specify what files to browse for
        filters: [
            { title: "Image files", extensions: "jpg,gif,png" },
            { title: "Zip files", extensions:  "zip,avi" }
        ],

        // Rename files by clicking on their titles
        rename: true,

        // Sort files
        sortable: true,

        // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
        dragdrop: true,

        // Views to activate
        views: {
            list: true,
            thumbs: true, // Show thumbs
            active: 'thumbs'
        },

        // Flash settings
        flash_swf_url : 'http://rawgithub.com/moxiecode/moxie/master/bin/flash/Moxie.cdn.swf',

        // Silverlight settings
        silverlight_xap_url : 'http://rawgithub.com/moxiecode/moxie/master/bin/silverlight/Moxie.cdn.xap'
    });


    // Handle the case when form was submitted before uploading has finished
    $('#form').submit(function(e) {
        // Files in queue upload them first
        if ($('#uploader').plupload('getFiles').length > 0) {

            // When all files are uploaded submit form
            $('#uploader').on('complete', function() {
                $('#form')[0].submit();
            });

            $('#uploader').plupload('start');
        } else {
            alert("You must have at least one file in the queue.");
        }
        return false; // Keep the form from submitting
    });*/

    var orderId = $('.js-var').data('order-id');
    console.log(orderId);

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
            resize: {width: 320, height: 240, quality: 90},
            flash_swf_url: 'http://rawgithub.com/moxiecode/moxie/master/bin/flash/Moxie.cdn.swf',
            silverlight_xap_url: 'http://rawgithub.com/moxiecode/moxie/master/bin/silverlight/Moxie.cdn.xap',
            init: {
                FilesAdded: function (up, files) {
                    console.log(files);
                    // Called when files are added to queue
                    //up.start();
                },
                FileUploaded: function (up, file, info) {
                    // Called when a file has finished uploading
                    console.log('[FileUploaded] File:', file, 'Info:', info);
                    info.responseText = info.response;
                },
                UploadComplete: function (up, files) {
                    // destroy the uploader and init a new one
                    up.refresh();
                    initUploader();
                    //$(".plupload_buttons").css("display", "inline");
                    //$(".plupload_upload_status").css("display", "inline");
                },
                Error: function (up, err) {
                    // Called when an error has occured
                    up.stop();
                    console.log(err);
                }
            }
        });
    }

    initUploader();
});
