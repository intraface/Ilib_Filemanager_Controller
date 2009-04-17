<?php
class Intraface_Filehandler_Controller_Edit extends k_Controller
{
    function GET()
    {
        $gateway = $this->registry->get('intraface:filehandler:gateway');

        $filemanager = $gateway->getFromId(intval($this->context->name));
        $values = $filemanager->get();
        $this->document->title = $this->__('edit file');

        $data = array('filemanager' => $filemanager,
                      'values' => $values);

        return $this->render(dirname(__FILE__) . '/../templates/edit.tpl.php', $data);
    }

    function POST()
    {
        $gateway = $this->registry->get('intraface:filehandler:gateway');

        $filemanager = $gateway->getFromId(intval($this->context->name));

        $filemanager->createUpload();
        $filemanager->upload->setSetting('max_file_size', '1000000');
        if ($filemanager->upload->isUploadFile('replace_file')) { //
            $upload_result = $filemanager->upload->upload('replace_file');
        } else {
            $upload_result = true;
        }

        if ($filemanager->update($this->POST->getArrayCopy()) && $upload_result) {
            throw new k_http_Redirect($this->context->url());
        }

        $data = array('filemanager' => $filemanager,
                      'values' => $this->POST->getArrayCopy());

        return $this->render(dirname(__FILE__) . '/../edit.tpl.php', $data);

    }
}