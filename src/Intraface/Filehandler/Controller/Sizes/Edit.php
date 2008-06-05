<?php
class Intraface_Filehandler_Controller_Sizes_Edit extends k_Controller
{
    function POST()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $shared_filehandler = $kernel->useShared('filehandler');
        $translation = $kernel->getTranslation('filehandler');
        $shared_filehandler->includeFile('InstanceManager.php');

        $instance_manager = new InstanceManager($kernel, (int)$this->POST['type_key']);

        if($instance_manager->save($this->POST->getArrayCopy())) {
            throw new k_http_Redirect($this->context->url());
        }

        throw new Exception('An error occured when trying to save');
    }

    function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $shared_filehandler = $kernel->useShared('filehandler');
        $translation = $kernel->getTranslation('filehandler');
        $shared_filehandler->includeFile('InstanceManager.php');

        if (!empty($this->GET['type_key'])) {
            $instance_manager = new InstanceManager($kernel, (int)$this->GET['type_key']);
            $value = $instance_manager->get();
        } else {
            $instance_manager = new InstanceManager($kernel);
            $value = $instance_manager->get();
        }

        $this->document->title = $this->__('edit instance type');

        $data = array('instance_manager' => $instance_manager, 'value' => $value);

        return $this->render(dirname(__FILE__) . '/../../templates/sizes-edit.tpl.php', $data);
    }

}
