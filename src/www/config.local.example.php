<?php
define('PATH_ROOT', 'c:\Users\Lars Olesen\workspace\vih\\');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'vih');
define('DB_DSN', 'mysql://root:@localhost/vih');
define('PATH_WWW', 'http://localhost/vih/intranet/src/www/');
define('PATH_UPLOAD', PATH_ROOT . 'upload\\');
define('PATH_UPLOAD_ORIGINAL', PATH_ROOT . 'devel\original\\');
define('PATH_UPLOAD_INSTANCE', PATH_ROOT . 'devel\instance\\');
define('PATH_INCLUDE', dirname(__FILE__) . '/../../../Ilib_Filemanager/src/' . PATH_SEPARATOR .
        dirname(__FILE__) . '/../../../Ilib_Error/src/' . PATH_SEPARATOR .
        dirname(__FILE__) . '/../../../Ilib_Redirect/src/' . PATH_SEPARATOR .
        dirname(__FILE__) . '/../../../Ilib_Keyword/src/' . PATH_SEPARATOR .
        dirname(__FILE__) . '/../../../Ilib_Keyword_Controller/src/' . PATH_SEPARATOR .
        dirname(__FILE__) . '/../' . PATH_SEPARATOR . ini_get('include_path'));
define('FILE_VIEWER', '/vih/hojskole/src/vih.dk/file.php');
define('PATH_UPLOAD_TEMPORARY', 'tempdir/');
