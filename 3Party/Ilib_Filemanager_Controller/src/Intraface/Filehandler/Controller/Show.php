<?php
class Intraface_Filehandler_Controller_Show extends k_Controller
{
    function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        $filemanager = new Ilib_Filehandler_Manager($kernel, $this->name);

        $this->document->title = $this->__('file') . ': ' . $filemanager->get('file_name');

        $data = array('filemanager' => $filemanager,
                      'kernel'      => $kernel);

        return $this->render(dirname(__FILE__) . '/../templates/show.tpl.php', $data);
    }

    function forward($name)
    {
        if ($name == 'edit') {
            $next = new Intraface_Filehandler_Controller_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'crop') {
            $next = new Intraface_Filehandler_Controller_Crop($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'undelete') {
            $next = new Intraface_Filehandler_Controller_Undelete($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'delete') {
            $next = new Intraface_Filehandler_Controller_Delete($this, $name);
            return $next->handleRequest();
        }
    }
}