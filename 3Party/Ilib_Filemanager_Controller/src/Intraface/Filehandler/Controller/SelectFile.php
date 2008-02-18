<?php
class Intraface_Filehandler_Controller_SelectFile extends k_Controller
{
    function POST()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module_filemanager = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        if(isset($this->POST['ajax'])) {

            if(!isset($this->POST['redirect_id'])) {
                print('0');
            }

            $options = array('extra_db_condition' => 'intranet_id = '.intval($kernel->intranet->get('id')));
            $redirect = new Ilib_Redirect($kernel->getSessionId(), MDB2::facotory(DB_DSN), intval($this->POST['redirect_id']), $options);
            if(isset($this->POST['add_file_id'])) {
                $filemanager = new Filemanager($kernel, intval($this->POST['add_file_id']));
                if($filemanager->get('id') != 0) {
                    $redirect->setParameter("file_handler_id", $filemanager->get('id'));
                    print('1');
                    exit;
                }
            }
            if(isset($this->POST['remove_file_id'])) {
                $redirect->removeParameter('file_handler_id', (int)$this->POST['remove_file_id']);
                print('1');
                exit;
            }
            print('0');
            exit;
        }


        $options = array('extra_db_condition' => 'intranet_id = '.intval($kernel->intranet->get('id')));

        $receive_redirect = Ilib_Redirect::factory($kernel->getSessionId(), MDB2::singleton(DB_DSN), 'receive', $options);

        $multiple_choice = false;

        /*
        if($receive_redirect->isMultipleParameter('file_handler_id')) {
            $multiple_choice = true;
        } else {
            $multiple_choice = false;
        }
        */

        if(isset($this->POST['return'])) {
            // Return is when AJAX is active, and then the checked files is already saved and should not be saved again.
            throw new k_http_Redirect($receive_redirect->getRedirect($this->url()));
            exit;
        }

        $filemanager = new FileManager($kernel); // has to be loaded here, while it should be able to set an error just below.
        $filemanager->createDBQuery();

        if(isset($this->POST['submit_close']) || isset($this->POST['submit'])) {
            settype($this->POST['selected'], 'array');
            $selected = $this->POST['selected'];

            $number_of_files = 0;
            foreach($selected AS $id) {
                $tmp_f = new Filemanager($kernel, (int)$id);
                if($tmp_f->get('id') != 0) {
                    $receive_redirect->setParameter("file_handler_id", $tmp_f->get('id'));
                    $number_of_files++;
                }
            }

            if($number_of_files == 0) {
                $filemanager->error->set("you have to choose a file");
            } elseif($multiple_choice == false || isset($this->POST['submit_close'])) {
                throw new k_http_Redirect($receive_redirect->getRedirect($this->url()));
            }
        }

        /*
        if($multiple_choice) {
            $selected_files = $receive_redirect->getParameter('file_handler_id');
        } else {
            if(isset($this->GET['selected_file_id'])) {
                $selected_files[] = (int)$this->GET['selected_file_id'];
            } else {
                $selected_files = array();
            }
        }
        */

        $filemanager->dbquery->defineCharacter('character', 'file_handler.file_name');
        $filemanager->dbquery->usePaging("paging", $kernel->setting->get('user', 'rows_pr_page'));
        $filemanager->dbquery->storeResult("use_stored", "filemanager", "sublevel");

        $files = $filemanager->getList();

    }

    function GET()
    {
        $kernel = $this->registry->get('intraface:kernel');
        $module_filemanager = $kernel->module('filemanager');
        $translation = $kernel->getTranslation('filemanager');

        $options = array('extra_db_condition' => 'intranet_id = '.intval($kernel->intranet->get('id')));
        $receive_redirect = Ilib_Redirect::factory($kernel->getSessionId(), MDB2::singleton(DB_DSN), 'receive', $options);
        if($receive_redirect->isMultipleParameter('file_handler_id')) {
            $multiple_choice = true;
        } else {
            $multiple_choice = false;
        }

        $filemanager = new FileManager($kernel); // has to be loaded here, while it should be able to set an error just below.
        $filemanager->createDBQuery();

        if(isset($this->GET['upload'])) {
            $options = array('extra_db_condition' => 'intranet_id = '.intval($kernel->intranet->get('id')));
            $upload_redirect = Ilib_Redirect::factory($kernel->getSessionId(), MDB2::singleton(DB_DSN), 'go', $options);

            if($this->GET['upload'] == 'multiple') {
                $url = $upload_redirect->setDestination($module_filemanager->getPath().'upload_multiple.php', $module_filemanager->getPath().'select_file.php?redirect_id='.$receive_redirect->get('id').'&filtration=1');
            } else {
                $url = $upload_redirect->setDestination($module_filemanager->getPath().'upload.php', $module_filemanager->getPath().'select_file.php?redirect_id='.$receive_redirect->get('id').'&filtration=1');
            }
            header("Location: ".$url);
        }

        if($multiple_choice) {
            $selected_files = $receive_redirect->getParameter('file_handler_id');
        } else {
            if(isset($this->GET['selected_file_id'])) {
                $selected_files[] = (int)$this->GET['selected_file_id'];
            } else {
                $selected_files = array();
            }
        }

        if(isset($this->GET['images'])) {
            $filemanager->dbquery->setFilter('images', 1);
        }

        if(isset($this->GET["text"]) && $this->GET["text"] != "") {
            $filemanager->dbquery->setFilter("text", $this->GET["text"]);
        }

        if(isset($this->GET["filtration"]) && intval($this->GET["filtration"]) != 0) {
            // Kun for at filtration igen vises i søgeboksen
            $filemanager->dbquery->setFilter("filtration", $this->GET["filtration"]);
            switch($this->GET["filtration"]) {
                case 1:
                    $filemanager->dbquery->setFilter("uploaded_from_date", date("d-m-Y")." 00:00");
                    break;
                case 2:
                    $filemanager->dbquery->setFilter("uploaded_from_date", date("d-m-Y", time()-60*60*24)." 00:00");
                    $filemanager->dbquery->setFilter("uploaded_to_date", date("d-m-Y", time()-60*60*24)." 23:59");
                    break;
                case 3:
                    $filemanager->dbquery->setFilter("uploaded_from_date", date("d-m-Y", time()-60*60*24*7)." 00:00");
                    break;
                case 4:
                    $filemanager->dbquery->setFilter("edited_from_date", date("d-m-Y")." 00:00");
                    break;
                case 5:
                    $filemanager->dbquery->setFilter("edited_from_date", date("d-m-Y", time()-60*60*24)." 00:00");
                    $filemanager->dbquery->setFilter("edited_to_date", date("d-m-Y", time()-60*60*24)." 23:59");
                    break;
                default:
                    // Probaly 0, so nothing happens
            }
        }

        if(isset($this->GET['keyword']) && is_array($this->GET['keyword']) && count($this->GET['keyword']) > 0) {
            $filemanager->dbquery->setKeyword($this->GET['keyword']);
        }

        if(isset($this->GET['character'])) {
            $filemanager->dbquery->useCharacter();
        }

        if(!isset($this->GET['search'])) {
            $filemanager->dbquery->setSorting('file_handler.date_created DESC');
        }


        $filemanager->dbquery->defineCharacter('character', 'file_handler.file_name');
        $filemanager->dbquery->usePaging("paging", $kernel->setting->get('user', 'rows_pr_page'));
        $filemanager->dbquery->storeResult("use_stored", "filemanager", "sublevel");
        $filemanager->dbquery->setUri($this->url());

        $files = $filemanager->getList();

        $this->document->scripts[] = $this->url('/scripts/select_file.js');

        $this->document->scripts[] = $this->url('/yui/connection/connection-min.js');

        $this->document->title = $this->__('files');

        $data = array('filemanager' => $filemanager,
                      'receive_redirect' => $receive_redirect,
                      'files' => $files,
                      'selected_files' =>  $selected_files);

        return $this->render(dirname(__FILE__) . '/../templates/selectfile.tpl.php', $data);
    }
}
