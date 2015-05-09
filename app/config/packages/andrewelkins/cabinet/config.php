<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Views
    |--------------------------------------------------------------------------
    |
    | The default views that the file uploader will use.
    |
    */

    'upload_form' => 'cabinet::upload',
    'upload_list' => 'cabinet::upload_list',


    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    |
    | Model the Uploader will use.
    | Default : Upload
    |
    */

    'upload_model' => 'Upload',


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    |
    | Table the Uploader will use.
    | Default : uploads
    |
    */

    'upload_table' => 'uploads',

    /*
    |--------------------------------------------------------------------------
    | Upload Folder
    |--------------------------------------------------------------------------
    |
    | Folder the Uploader will use.
    | This will need to writable by the web server.
    | Default : public/packages/andrew13/cabinet/uploads/
    | Recommendation: public/uploads/
    |
    */

    'upload_folder' => 'public/uploads/',
    'upload_folder_public_path' => 'public/uploads/',
    'upload_folder_permission_value' => 0777, // Default 0777 - Other likely values 0775, 0755


    /*
    |--------------------------------------------------------------------------
    | Upload Files
    |--------------------------------------------------------------------------
    |
    | Configuration items for uploaded files.
    |
    */

    'upload_file_types' => array(
                                 'image/png',
                                 'image/gif',
                                 'image/jpg',
                                 'image/jpeg',
                                 'application/msword',
                                 'application/vnd.ms-excel',
                                 'application/vnd.ms-project',
                                 'application/pdf',
                                 'application/vnd.ms-powerpoint',
                                 'image/tiff',
                                 'image/bmp',
                                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                 'application/wps-office.xlsx',
                                 'application/wps-office.doc',
                                 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                 'application/vnd.oasis.opendocument.text',
                                 'application/vnd.oasis.opendocument.spreadsheet',
                                 'application/vnd.oasis.opendocument.presentation',
                                 'application/vnd.oasis.opendocument.graphics',
                                 'application/zip',
                                 ),

    'upload_file_extensions' => array('png','gif','jpg','jpeg', 'doc',
    'xls', 'mpp', 'pdf', 'ppt', 'tiff', 'bmp', 'docx', 'xlsx',
    'pptx', 'odt', 'ods', 'odp', 'odg', 'zip'), // Case insensitive

    // Max upload size - In BYTES. 1GB = 1073741824 bytes, 10 MB = 10485760, 1 MB = 1048576
    'max_upload_file_size' => 10485760, // Converter - http://www.beesky.com/newsite/bit_byte.htm

    // [True] will change all uploaded file names to an obfuscated name. (Example_Image.jpg becomes Example_Image_p4n8wfnt8nwh5gc7ynwn8gtu4se8u.jpg)
    // [False] attempts to leaves the filename as is.
    'obfuscate_filenames' => false, // True/False

    /*
    |--------------------------------------------------------------------------
    | Image
    |--------------------------------------------------------------------------
    |
    | Configuration items for image files.
    |
    */

    // Enabled image manipulation for uploaded images.
    'image_manipulation' => 'true',

    // Must be in the upload list as well.
    // Must also be supported by invention. http://intervention.olivervogel.net/image/formats/image
    'image_file_types' => array('image/png','image/gif','image/jpg','image/jpeg'),
    'image_file_extensions' => array('png','gif','jpg','jpeg'), // Case insensitive

    // Image resizing params. http://intervention.olivervogel.net/image/methods/resize
    'image_resize' => array(
        array(300, 200), // 300x200 image
        array(300, 200, true), // 300x200 image ratio constrained aspect ratio
        array(null, 400, true), // auto width x 400 height constrained aspect ratio
    ),

);
