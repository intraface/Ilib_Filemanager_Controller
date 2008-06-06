<?php
require_once 'config.local.php';

set_include_path(PATH_INCLUDE);

require_once 'k.php';
require_once 'Ilib/ClassLoader.php';

if (!defined('DB_DSN')) {
    define('DB_DSN', 'mysql://' . DB_USER . ':' . DB_PASSWORD . '@' . DB_HOST . '/' . DB_NAME);
}

class FakeUser {


    public function get($key) {

        $values = array('id' => 1);

        return $values[$key];
    }

    public function hasModuleAccess() {
        return true;
    }

}

class FakeIntranet
{
    public $values;

    public function __construct()
    {
        $this->values = array(
            'id' => 0,
            'public_key' => 'file');
    }

    public function get($key)
    {
        // if id should be other than 0 you need to add options to the use of Ilib classes with 'intranet_id = ?'
        return $this->values[$key];
    }
}

class FakeKernel {

    private $translation;
    public $setting;
    public $intranet;
    public $user;
    public $sesion_id;

    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
        $this->session_id = session_id();
    }

    /**
     * We should actually return an object, but lets see how this works
     */
    public function module($module) {
    }

    public function useShared($shared)
    {
    }

    public function getTranslation($page_id)
    {
        /*
        $dbinfo = array(
            'hostspec' => DB_HOST,
            'database' => DB_NAME,
            'phptype'  => 'mysql',
            'username' => DB_USER,
            'password' => DB_PASSWORD
        );

        if (!defined('LANGUAGE_TABLE_PREFIX')) {
            define('LANGUAGE_TABLE_PREFIX', 'core_translation_');
        }

        $params = array(
            'langs_avail_table' => LANGUAGE_TABLE_PREFIX.'langs',
            'strings_default_table' => LANGUAGE_TABLE_PREFIX.'i18n'
        );

        require_once 'Translation2.php';

        $translation = Translation2::factory('MDB2', $dbinfo, $params);
        //always check for errors. In this examples, error checking is omitted
        //to make the example concise.
        if (PEAR::isError($translation)) {
            trigger_error('Could not start Translation ' . $translation->getMessage(), E_USER_ERROR);
        }

        // set primary language
        $set_language = $translation->setLang('dk');
        if (PEAR::isError($set_language)) {
            trigger_error($set_language->getMessage(), E_USER_ERROR);
        }

        // set the group of strings you want to fetch from
        // $translation->setPageID($page_id);

        // add a Lang decorator to provide a fallback language
        $translation = $translation->getDecorator('Lang');
        $translation->setOption('fallbackLang', 'uk');
        // $translation = $translation->getDecorator('LogMissingTranslation');
        // require_once("ErrorHandler/Observer/File.php");
        // $translation->setOption('logger', array(new ErrorHandler_Observer_File(ERROR_LOG), 'update'));
        $translation = $translation->getDecorator('DefaultText');

        return $translation;
        */
    }


    public function getSessionId() {
        return $this->session_id;
    }
}



class FakeSetting {

    /**
     * @var settings
     */
    private $settings = array(
        'user' => array('rows_pr_page' => 20)
    );

    public function get($level, $key) {
        return $this->settings[$level][$key];
    }


}

class This_Filehandler_Root extends k_Dispatcher
{
    public $map = array('file'        => 'Intraface_Filehandler_Controller_Viewer',
                        'filemanager' => 'Intraface_Filehandler_Controller_Index',
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
  '$kernel = new FakeKernel;
   $kernel->setting = new FakeSetting;
   $kernel->intranet = new FakeIntranet;
   $kernel->user = new FakeUser;
   return $kernel;'
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
