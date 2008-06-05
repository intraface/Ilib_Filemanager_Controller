<?php
class Intraface_Filehandler_Controller_Delete extends k_Controller
{
    function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        $filemanager = new FileManager($kernel, $this->context->name);
        if (!$filemanager->delete()) {
            trigger_error($this->__('could not delete file'), E_USER_ERROR);
        }
        throw new k_http_Redirect($this->context->url('../'));
    }

}
