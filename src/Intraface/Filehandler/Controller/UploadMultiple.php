<?php
class Intraface_Filehandler_Controller_UploadMultiple extends k_Controller
{
    function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');

        $redirect = Ilib_Redirect::receive($kernel->getSessionId(), $this->registry->get('database:mdb2'));

        $filemanager = new Ilib_Filehandler($kernel);

        $this->document->title = $this->__('Upload file');

        $data = array('filemanager' => $filemanager, 'redirect' => $redirect);

        return $this->render(dirname(__FILE__) . '/../templates/uploadmultiple.tpl.php', $data);
    }

    function POST()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        $options = array('extra_db_condition' => 'intranet_id = '.intval($kernel->intranet->get('id')));
        $redirect = Ilib_Redirect::factory($kernel->getSessionId(), MDB2::factory(DB_DSN), 'receive', $options);

        if (!empty($_POST['addfile'])) {
            foreach ($_POST['addfile'] as $key => $value) {
                $filemanager = new Ilib_Filehandler($kernel, $value);
                $appender = $filemanager->getKeywordAppender();
                $string_appender = new Intraface_Keyword_StringAppender($filemanager->getKeyword(), $appender);
                $string_appender->addKeywordsByString($_POST['keywords']);

                $filemanager->update(array('accessibility' => $_POST['accessibility']));

                if ($filemanager->moveFromTemporary()) {
                    // $msg = 'Filerne er uploadet. <a href="'.$redirect->getRedirect('/modules/filemanager/').'">Åbn filarkivet</a>.';
                }
            }
        }
        $location = $redirect->getRedirect($this->context->url('../'));
        throw new k_http_Redirect($location);
    }
}