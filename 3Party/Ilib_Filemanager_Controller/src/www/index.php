<?php
require_once 'config.local.php';
if (!defined('DB_DSN')) {
    define('DB_DSN', 'mysql://' . DB_USER . ':' . DB_PASSWORD . '@' . DB_HOST . '/' . DB_NAME);
}

set_include_path(PATH_INCLUDE);

require_once 'k.php';

class vih_ClassLoader extends k_ClassLoader
{
    static function autoload($classname)
    {
        $filename = str_replace('_', '/', $classname).'.php';
        if (self::SearchIncludePath($filename)) {
            require_once($filename);
        }
    }
}
spl_autoload_register(Array('vih_ClassLoader', 'autoload'));

// intraface
session_start();

require_once 'Intraface/common.php';

$options= array("debug" => 2);
$db = MDB2::singleton(DB_DSN, $options);
if (PEAR::isError($db)) {
    die($db->getMessage());
}
$db->setOption("portability", MDB2_PORTABILITY_NONE);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);


// This is a default error-handler, which simply converts errors to exceptions
// Konstrukt doesn't need this setup, but it's a pretty sane choice.
// If this makes no sense to you, just let it be. It basically means that old-style errors are
// converted into exceptions instead. This allows a simpler error-handling.
error_reporting(E_ALL);
function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() && $severity > E_USER_NOTICE && $severity < E_STRICT) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}
set_error_handler('exceptions_error_handler');

// This is a default exceptions-handler. For debugging, it's practical to get a readable
// trace dumped out at the top level, rather than just a blank screen.
// If you use something like Xdebug, you may want to skip this part, since it already gives
// a similar output.
// For production, you should replace this handler with something, which logs the error,
// and doesn't dump a trace. Failing to do so could be a security risk.
function debug_exception_handler($ex) {
  if (php_sapi_name() == 'cli') {
    echo "Error (code:".$ex->getCode().") :".$ex->getMessage()."\n at line ".$ex->getLine()." in file ".$ex->getFile()."\n";
    echo $ex->getTraceAsString()."\n";
  } else {
    echo "<p style='font-family:helvetica,sans-serif'>\n";
    echo "<b>Error :</b>".$ex->getMessage()."<br />\n";
    echo "<b>Code :</b>".$ex->getCode()."<br />\n";
    echo "<b>File :</b>".$ex->getFile()."<br />\n";
    echo "<b>Line :</b>".$ex->getLine()."</p>\n";
    echo "<div style='font-family:garamond'>".nl2br(htmlspecialchars($ex->getTraceAsString()))."</div>\n";
  }
  exit -1;
}

/////////////////////////////////////////////////////////////////////////////////////

class This_Filehandler_Root extends k_Dispatcher
{
    public $map = array('filemanager' => 'Intraface_Filehandler_Controller_Index',
                        'keyword'     => 'Intraface_Keyword_Controller_Index');
    public $debug = true;

    function __construct()
    {
        parent::__construct();
        $this->document->template = 'document.tpl.php';
        $this->document->title = 'Filemanager';
    }

    function execute()
    {
        return $this->forward('filemanager');
    }
}

$application = new This_Filehandler_Root();

$application->registry->registerConstructor('database:db_sql', create_function(
  '$className, $args, $registry',
  'return new DB_Sql();'
));

// I don't know if this i right?
$GLOBALS['intraface.kernel'] = $kernel;

$application->registry->registerConstructor('intraface:kernel', create_function(
  '$className, $args, $registry',
  'return $GLOBALS["intraface.kernel"];'
));

$application->registry->registerConstructor('database:mdb2', create_function(
  '$className, $args, $registry',
  '$options= array("debug" => 0);
   $db = MDB2::factory(DB_DSN, $options);
   if (PEAR::isError($db)) {
        die($db->getMessage());
   }
   $db->setOption("portability", MDB2_PORTABILITY_NONE);
   $db->setFetchMode(MDB2_FETCHMODE_ASSOC);
   $db->exec("SET time_zone=\"-01:00\"");
   return $db;
'
));

$application->dispatch();
