<?php
class Intraface_Filehandler_Controller_Index extends k_Controller
{
    public function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        if (!empty($this->GET['delete']) AND is_numeric($this->GET['delete'])) {
            $filemanager = new Ilib_Filehandler_Manager($kernel, $this->GET['delete']);
            if (!$filemanager->delete()) {
                throw new Exception($this->__('could not delete file'));
            }
        } elseif (!empty($this->GET['undelete']) AND is_numeric($this->GET['undelete'])) {
            $filemanager = new Ilib_Filehandler_Manager($kernel, $this->GET['undelete']);
            if (!$filemanager->undelete()) {
                throw new Exception($this->__('could not undelete file'));
            }
        } else {
            $filemanager = new Ilib_Filehandler_Manager($kernel);
        }

        if(isset($this->GET['search'])) {

            if(isset($this->GET['text']) && $this->GET['text'] != '') {
                $filemanager->getDBQuery()->setFilter('text', $this->GET['text']);
            }

            if(isset($this->GET['filtration']) && intval($this->GET['filtration']) != 0) {
                $filemanager->getDBQuery()->setFilter('filtration', $this->GET['filtration']);

                switch($this->GET['filtration']) {
                    case 1:
                        $filemanager->getDBQuery()->setFilter('uploaded_from_date', date('d-m-Y').' 00:00');
                        break;
                    case 2:
                        $filemanager->getDBQuery()->setFilter('uploaded_from_date', date('d-m-Y', time()-60*60*24).' 00:00');
                        $filemanager->getDBQuery()->setFilter('uploaded_to_date', date('d-m-Y', time()-60*60*24).' 23:59');
                        break;
                    case 3:
                        $filemanager->getDBQuery()->setFilter('uploaded_from_date', date('d-m-Y', time()-60*60*24*7).' 00:00');
                        break;
                    case 4:
                        $filemanager->getDBQuery()->setFilter('edited_from_date', date('d-m-Y').' 00:00');
                        break;
                    case 5:
                        $filemanager->getDBQuery()->setFilter('edited_from_date', date('d-m-Y', time()-60*60*24).' 00:00');
                        $filemanager->getDBQuery()->setFilter('edited_to_date', date('d-m-Y', time()-60*60*24).' 23:59');
                        break;
                    case 6:
                        $filemanager->getDBQuery()->setFilter('accessibility', 'public');
                        break;
                    case 7:
                        $filemanager->getDBQuery()->setFilter('accessibility', 'intranet');
                        break;
                    default:
                        // Probably 0, so nothing happens
                        break;
                }
            }

            if(isset($this->GET['keyword']) && is_array($this->GET['keyword']) && count($this->GET['keyword']) > 0) {
                $filemanager->getDBQuery()->setKeyword($this->GET['keyword']);
            }
        } elseif(isset($this->GET['character'])) {
            $filemanager->getDBQuery()->useCharacter();
        } else {
            $filemanager->getDBQuery()->setSorting('file_handler.date_created DESC');
        }

        $filemanager->getDBQuery()->defineCharacter('character', 'file_handler.file_name');
        $filemanager->getDBQuery()->usePaging('paging', $kernel->setting->get('user', 'rows_pr_page'));
        $filemanager->getDBQuery()->storeResult('use_stored', 'filemanager', 'toplevel');
        $filemanager->getDBQuery()->setUri($this->url());

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