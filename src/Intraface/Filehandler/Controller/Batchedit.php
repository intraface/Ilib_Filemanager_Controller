<?php
class Intraface_Filehandler_Controller_Batchedit extends k_Controller
{
    function POST()
    {

        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        foreach ($this->POST['description'] AS $key=>$value) {
            $filemanager = new FileManager($kernel, $key);
            if ($filemanager->update(array(
                'description' => $this->POST['description'][$key],
                'accessibility' => $this->POST['accessibility'][$key]
                ))) {

                $appender = $filemanager->getKeywordAppender();
                $string_appender = new Intraface_Keyword_StringAppender($filemanager->getKeywords(), $appender);
                $string_appender->addKeywordsByString($this->POST['keywords'][$key]);
            }
            $filemanager->error->view();
        }

        throw new k_http_Redirect($this->context->url(), array('use_stored' => 'true'));
    }

    function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');


        if (empty($this->GET['use_stored'])) {
            trigger_error($translation->get('you cannot batch edit files with no save results'), E_USER_ERROR);
        }

        $filemanager = new FileManager($kernel);
        $filemanager->createDBQuery();
        $filemanager->dbquery->storeResult('use_stored', 'filemanager', 'toplevel');

        $files = $filemanager->getList();

        $this->document->title = $translation->get('files');

        $data = array('filemanager' => $filemanager, 'files' => $files, 'kernel' => $kernel);

        return $this->render(dirname(__FILE__) . '/../templates/batchedit.tpl.php', $data);
    }

}