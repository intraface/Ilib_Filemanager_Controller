<?php
class Intraface_Filehandler_Controller_Show extends k_Controller
{
    function getKernel()
    {
    	return $this->context->getKernel();
    }

    function GET()
    {
        $filemanager = $this->getObject();

        if ($filemanager->getId() == 0) {
            throw new k_http_Response(404);
        }

        $this->document->title = $this->__('file') . ': ' . $filemanager->get('file_name');

        $data = array('filemanager' => $filemanager,
                      'kernel'      => $this->getKernel());

        return $this->render(dirname(__FILE__) . '/../templates/show.tpl.php', $data);
    }

    function getObject()
    {
    	$gateway = $this->registry->get('intraface:filehandler:gateway');
        return $gateway->getFromId($this->name);
    }

    function map($name)
    {
        if ($name == 'edit') {
            return 'Intraface_Filehandler_Controller_Edit';
        } elseif ($name == 'crop') {
            return 'Intraface_Filehandler_Controller_Crop';
        } elseif ($name == 'undelete') {
            return 'Intraface_Filehandler_Controller_Undelete';
        } elseif ($name == 'delete') {
            return 'Intraface_Filehandler_Controller_Delete';
        } elseif ($name == 'keyword') {
            return 'Intraface_Keyword_Controller_Index';
        }
    }
}