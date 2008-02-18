<?php
class Intraface_FileHandler_Controller_Index extends k_Controller
{
    public function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        if (!empty($this->GET['delete']) AND is_numeric($this->GET['delete'])) {
            $filemanager = new FileManager($kernel, $this->GET['delete']);
            if (!$filemanager->delete()) {
                throw new Exception($this->__('could not delete file'));
            }
        } elseif (!empty($this->GET['undelete']) AND is_numeric($this->GET['undelete'])) {
            $filemanager = new FileManager($kernel, $this->GET['undelete']);
            if (!$filemanager->undelete()) {
                throw new Exception($this->__('could not undelete file'));
            }
        } else {
            $filemanager = new FileManager($kernel);
        }

        $filemanager->createDBQuery();

        if(isset($this->GET['search'])) {

            if(isset($this->GET['text']) && $this->GET['text'] != '') {
                $filemanager->dbquery->setFilter('text', $this->GET['text']);
            }

            if(isset($this->GET['filtration']) && intval($this->GET['filtration']) != 0) {
                $filemanager->dbquery->setFilter('filtration', $this->GET['filtration']);

                switch($this->GET['filtration']) {
                    case 1:
                        $filemanager->dbquery->setFilter('uploaded_from_date', date('d-m-Y').' 00:00');
                        break;
                    case 2:
                        $filemanager->dbquery->setFilter('uploaded_from_date', date('d-m-Y', time()-60*60*24).' 00:00');
                        $filemanager->dbquery->setFilter('uploaded_to_date', date('d-m-Y', time()-60*60*24).' 23:59');
                        break;
                    case 3:
                        $filemanager->dbquery->setFilter('uploaded_from_date', date('d-m-Y', time()-60*60*24*7).' 00:00');
                        break;
                    case 4:
                        $filemanager->dbquery->setFilter('edited_from_date', date('d-m-Y').' 00:00');
                        break;
                    case 5:
                        $filemanager->dbquery->setFilter('edited_from_date', date('d-m-Y', time()-60*60*24).' 00:00');
                        $filemanager->dbquery->setFilter('edited_to_date', date('d-m-Y', time()-60*60*24).' 23:59');
                        break;
                    case 6:
                        $filemanager->dbquery->setFilter('accessibility', 'public');
                        break;
                    case 7:
                        $filemanager->dbquery->setFilter('accessibility', 'intranet');
                        break;
                    default:
                        // Probably 0, so nothing happens
                        break;
                }
            }

            if(isset($this->GET['keyword']) && is_array($this->GET['keyword']) && count($this->GET['keyword']) > 0) {
                $filemanager->dbquery->setKeyword($this->GET['keyword']);
            }
        } elseif(isset($this->GET['character'])) {
            $filemanager->dbquery->useCharacter();
        } else {
            $filemanager->dbquery->setSorting('file_handler.date_created DESC');
        }

        $filemanager->dbquery->defineCharacter('character', 'file_handler.file_name');
        $filemanager->dbquery->usePaging('paging', $kernel->setting->get('user', 'rows_pr_page'));
        $filemanager->dbquery->storeResult('use_stored', 'filemanager', 'toplevel');
        $filemanager->dbquery->setUri($this->url());

        $files = $filemanager->getList();

        $data = array('files' => $files,
                      'filemanager' => $filemanager);

        return $this->render(dirname(__FILE__) . '/../templates/index.tpl.php', $data);
    }

    protected function forward($name)
    {
        if ($name == 'batchedit') {
            $next = new Intraface_Filehandler_Controller_Batchedit($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'uploadmultiple' OR $name == 'upload') {
            $next = new Intraface_Filehandler_Controller_Upload($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'sizes') {
            $next = new Intraface_Filehandler_Controller_Sizes($this, $name);
            return $next->handleRequest();
        } elseif ($name == 'selectfile') {
            $next = new Intraface_Filehandler_Controller_SelectFile($this, $name);
            return $next->handleRequest();
        }
        $next = new Intraface_Filehandler_Controller_Show($this, $name);
        return $next->handleRequest();
    }
}