<?php
class Intraface_Filehandler_Controller_Sizes extends k_Controller
{
    function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $translation = $kernel->getTranslation('filehandler');
        $shared_filehandler = $kernel->useShared('filehandler');

        if(!empty($this->GET['delete_instance_type_key'])) {
            $instance_manager = new Ilib_Filehandler_InstanceManager($kernel, (int)$this->GET['delete_instance_type_key']);
            $instance_manager->delete();
        }

        $filehandler = new Ilib_Filehandler($kernel);
        $instance_manager = new Ilib_Filehandler_InstanceManager($kernel);

        $this->document->title = $translation->get('filehandler settings');

        $data = array('instance_manager' => $instance_manager);

        return $this->render(dirname(__FILE__) . '/../templates/sizes.tpl.php', $data);
    }

    function forward($name)
    {
        if ($name == 'add') {
            $next = new Intraface_Filehandler_Controller_Sizes_Edit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'edit') {
            $next = new Intraface_Filehandler_Controller_Sizes_Edit($this, $name);
            return $next->handleRequest();
        }
    }

}
