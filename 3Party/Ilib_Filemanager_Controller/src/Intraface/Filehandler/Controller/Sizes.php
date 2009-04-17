<?php
class Intraface_Filehandler_Controller_Sizes extends k_Component
{
    function getKernel()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $shared_filehandler = $kernel->useShared('filehandler');
        return $kernel;
    }

    function getFilehandler()
    {
    	return new Ilib_Filehandler($this->getKernel());
    }

    function GET()
    {

        if (!empty($this->GET['delete_instance_type_key'])) {
            $instance_manager = new Ilib_Filehandler_InstanceManager($this->getKernel(), (int)$this->GET['delete_instance_type_key']);
            $instance_manager->delete();
        }

        $instance_manager = new Ilib_Filehandler_InstanceManager($this->getKernel());

        $this->document->title = $this->__('Filehandler settings');

        // $filehandler->createInstance();
        // $instances = $filehandler->instance->getTypes();

        $instances = $instance_manager->getList();

        $data = array('instance_manager' => $instance_manager, 'instances' => $instances);

        return $this->render(dirname(__FILE__) . '/../templates/sizes.tpl.php', $data);
    }

    function POST()
    {
    	if ($this->POST['all_files']) {
            $manager = new Ilib_Filehandler_Manager($this->getKernel());
            $manager->deleteAllInstances();
    	}

        throw new k_http_Redirect($this->url());
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
