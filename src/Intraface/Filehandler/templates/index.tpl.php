<h1><?php e(__('file manager')); ?></h1>

<ul class="options">
    <li><a href="<?php e(url('upload')); ?>" onclick="location.href='<?php e(url('uploadmultiple')); ?>'; return false;"><?php e(__('upload file')); ?></a></li>
    <!-- <li><a href="upload_multiple.php">Upload billeder</a></li> -->
    <?php if (count($files) > 0): ?>
    <li><a href="<?php e(url('batchedit', array('use_stored' => 'true'))); ?>"><?php e(__('batch edit files')); ?></a></li>
    <?php endif; ?>
    <!--<li><a href="import.php"><?php e(__('import files')); ?></a></li>-->
</ul>


<?php if (!empty($this->GET['delete']) AND is_numeric($this->GET['delete'])): ?>
    <p class="message"><?php e(__('file has been deleted')); ?>. <a href="<?php e(url('./', array('undelete' => (int)$this->GET['delete']))); ?>">Fortryd</a></p>
<?php endif; ?>


<?php if (count($files) == 0): ?>
    <p><?php e(__('no files uploaded')); ?></p>
<?php else: ?>


<form method="get" action="<?php e(url('./')); ?>">
    <fieldset>
        <legend><?php e(__('search')); ?></legend>
        <label><?php e(__('search text')); ?>:
            <input type="text" name="text" value="<?php e($filemanager->dbquery->getFilter("text")); ?>" />
        </label>
        <label><?php e(__('search filter')); ?>:
        <select name="filtration">
            <option value="0"><?php e(__('all', 'filehandler')); ?></option>
            <option value="1"<?php if ($filemanager->dbquery->getFilter("filtration") == 1) e(' selected="selected"');?>><?php e(__('uploaded today', 'filehandler')); ?></option>
            <option value="2"<?php if ($filemanager->dbquery->getFilter("filtration") == 2) e(' selected="selected"');?>><?php e(__('uploaded yesterday', 'filehandler')); ?></option>
            <option value="3"<?php if ($filemanager->dbquery->getFilter("filtration") == 3) e(' selected="selected"');?>><?php e(__('uploaded this week', 'filehandler')); ?></option>
            <option value="4"<?php if ($filemanager->dbquery->getFilter("filtration") == 4) e(' selected="selected"');?>><?php e(__('edited today', 'filehandler')); ?></option>
            <option value="5"<?php if ($filemanager->dbquery->getFilter("filtration") == 5) e(' selected="selected"');?>><?php e(__('edited yesterday', 'filehandler')); ?></option>
            <option value="6"<?php if ($filemanager->dbquery->getFilter("filtration") == 6) e(' selected="selected"');?>><?php e(__('public accessible', 'filemanager')); ?></option>
            <option value="7"<?php if ($filemanager->dbquery->getFilter("filtration") == 7) e(' selected="selected"');?>><?php e(__('only accessible from intranet', 'filemanager')); ?></option>

        </select>
        </label>
        <span>
        <input type="submit" name="search" value="<?php e(__('search')); ?>" />
        </span>

        <?php

        $selected_keywords = $filemanager->dbquery->getKeyword();

    $keyword = $filemanager->getKeywordAppender();
    $keywords = $keyword->getUsedKeywords();

    if(count($keywords) > 0) {
        echo '<div>'. e(__('keywords', 'keyword')) . ': <ul style="display: inline;">';
        foreach ($keywords AS $value) {
             if(in_array($value['id'], $selected_keywords) === true) {
                    $checked = 'checked="checked"';
                }
                else {
                    $checked = "";
                }
                echo '<li style="display: inline; margin-left: 20px;"><label for="keyword_'.$value['id'].'"><input type="checkbox" name="keyword[]" value="'.$value['id'].'" id="keyword_'.$value['id'].'" '.$checked.' />&nbsp;'.$value['keyword'].'</label></li>';
        }
        echo '</ul></div>';
    }
    ?>

    </fieldset>
</form>


<?php echo $filemanager->dbquery->display('character'); ?>



<table class="stripe">
    <caption><?php e(__('files')); ?></caption>
    <thead>
        <tr>
            <th></th>
            <th><?php e(__('file name')); ?></th>
            <th><?php e(__('file type')); ?></th>
            <th><?php e(__('file accessibility')); ?></th>
            <th><?php e(__('file size')); ?></th>
            <th><?php e(__('file date')); ?></th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php
        for($i = 0, $max = count($files); $i < $max; $i++) {
            ?>
            <tr>
                <td style="height: 67px;"><a href="<?php e($files[$i]['file_uri']); ?>"><img src="<?php e($files[$i]["icon_uri"]); ?>" style="height: <?php e($files[$i]["icon_height"]); ?>px; width: <?php e($files[$i]["icon_width"]); ?>px;" /></a></td>
                <td><a href="<?php e(url($files[$i]["id"])); ?>"><?php e($files[$i]["file_name"]); ?></a>
                    <br /><i><?php e(substr(strip_tags($files[$i]["description"]), 0, 100)); if(strlen(strip_tags($files[$i]["description"])) > 100) print('...'); ?></i>
                </td>
                <td style="white-space: nowrap;"><?php e($files[$i]["file_type"]['description']); ?></td>
                <td style="white-space: nowrap;"><?php e(__($files[$i]["accessibility"])); ?></td>
                <td style="white-space: nowrap;"><?php e($files[$i]["dk_file_size"]); ?></td>
                <td style="white-space: nowrap;"><?php e($files[$i]["dk_date_created"]); ?></td>
                <td style="width: 120px;" class="options">
                    <a class="edit" href="<?php e(url($files[$i]['id'] . '/edit')); ?>"><?php e(__('edit', 'common')); ?></a>
                    <a class="delete" href="<?php e(url($files[$i]['id'] . '/delete')); ?>"><?php e(__('delete', 'common')); ?></a></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<?php echo $filemanager->dbquery->display('paging'); ?>

<?php endif; ?>