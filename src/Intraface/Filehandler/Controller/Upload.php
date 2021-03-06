<?php
class Intraface_Filehandler_Controller_Upload extends k_Controller
{
    function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');

        $redirect = Ilib_Redirect::receive($kernel->getSessionId(), $this->registry->get('database:mdb2'));

        $filemanager = new Ilib_Filehandler($kernel);

        $this->document->title = $this->__('Upload file');

        $data = array('filemanager' => $filemanager, 'redirect' => $redirect);

        return $this->render(dirname(__FILE__) . '/../templates/upload.tpl.php', $data);
    }

    function POST()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        $redirect = Ilib_Redirect::receive($kernel->getSessionId(), $this->registry->get('database:mdb2'));

        $filemanager = new Ilib_Filehandler($kernel);

        $filemanager->getUploader()->setSetting('file_accessibility', $this->POST['accessibility']);
        $filemanager->getUploader()->setSetting('max_file_size', '10000000');
        $filemanager->getUploader()->setSetting('add_keyword', $this->POST['keyword']);
        if($id = $filemanager->getUploader()->upload('userfile')) {
            $location = $redirect->getRedirect($this->context->url($id));
            throw new k_http_Redirect($location);
        }

        return '<h1>'.$this->__('Errors occured').'</h1>' . $filemanager->error->view() . '<p><a href="'.$this->url(null).'">'.$this->__('Go back').'</a></p>';
    }
}