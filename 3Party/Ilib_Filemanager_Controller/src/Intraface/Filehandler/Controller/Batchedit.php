<?php
class Intraface_Filehandler_Controller_Batchedit extends k_Controller
{
    function POST()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $gateway = $this->registry->get('intraface:filehandler:gateway');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        foreach ($this->POST['description'] as $key => $value) {
            $filemanager = $gateway->getFromId($key);
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
        $gateway = $this->registry->get('intraface:filehandler:gateway');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        if (empty($this->GET['use_stored'])) {
            trigger_error($this->__('you cannot batch edit files with no save results'), E_USER_ERROR);
        }

        $gateway->getDBQuery()->storeResult('use_stored', 'filemanager', 'toplevel');

        $files = $gateway->getList();

        $this->document->title = $this->__('files');

        $data = array('gateway' => $gateway, 'files' => $files, 'kernel' => $kernel);

        return $this->render(dirname(__FILE__) . '/../templates/batchedit.tpl.php', $data);
    }

}