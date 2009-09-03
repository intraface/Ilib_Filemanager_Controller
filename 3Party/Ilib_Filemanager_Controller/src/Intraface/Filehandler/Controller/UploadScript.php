<?php
class Intraface_Filehandler_Controller_UploadScript extends k_Controller
{
    function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module("filemanager");
        $translation = $kernel->getTranslation('filemanager');

        $data = array('kernel' => $kernel);

        throw new k_http_Response(200, $this->render(dirname(__FILE__) . '/../templates/uploadscript.tpl.php', $data));
    }

    function POST()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module("filemanager");
        $translation = $kernel->getTranslation('filemanager');

        $data = array('kernel' => $kernel, 'filemanager' => new Ilib_Filehandler($kernel));

        throw new k_http_Response(200, $this->render(dirname(__FILE__) . '/../templates/uploadscript-post.tpl.php', $data));
    }
}