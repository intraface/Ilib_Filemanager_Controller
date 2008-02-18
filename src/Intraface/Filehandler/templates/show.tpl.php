<div id="colOne">

    <h1><?php e(__('file')); ?></h1>

    <?php $filemanager->error->view(); ?>

    <ul class="options">
        <li><a href="<?php e(url('edit')); ?>"><?php e(__('edit', 'common')); ?></a></li>
        <li><a href="<?php e($filemanager->get('file_uri')); ?>"><?php e(__('get file')); ?></a></li>
        <li><a href="<?php e(url('../', array('use_stored' => 'true'))); ?>"><?php e(__('close', 'common')); ?></a></li>
    </ul>

    <table>
        <caption><?php e(__('information')); ?></caption>
        <tbody>
        <tr>
            <th><?php e(__('file name')); ?></th>
            <td><?php echo safeToHtml($filemanager->get('file_name')); ?></td>
        </tr>
        <tr>
            <th><?php e(__('created', 'common')); ?></th>
            <td><?php print($filemanager->get("dk_date_created")); ?></td>
        </tr>
        <tr>
            <th><?php e(__('file size')); ?></th>
            <td><?php print($filemanager->get("dk_file_size")); ?></td>
        </tr>
        <tr>
            <th><?php e(__('file type')); ?></th>
            <?php
            $file_type = $filemanager->get("file_type");
            ?>
            <td><?php e($file_type['description']); ?></td>
        </tr>
        <tr>
            <th><?php e(__('accessibility')); ?></th>
            <td><?php e($filemanager->get("accessibility")); ?></td>
        </tr>
        <?php
        if($filemanager->get('is_image') == 1) {
            ?>
            <tr>
                <th><?php e(__('image width')); ?></th>
                <td><?php e($filemanager->get('width')); ?>px</td>
            </tr>
            <tr>
                <th><?php e(__('image height')); ?></th>
                <td><?php e($filemanager->get('height')); ?>px</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <h3><?php e(__('file description')); ?></h3>

    <?php
    if($filemanager->get('description') == '') {
        ?>
        <p><a href="edit.php?id=<?php print($filemanager->get('id')); ?>"><?php e(__('add description')); ?></a></p>
        <?php
    }
    else {
        print(nl2br($filemanager->get('description')));
    }
    ?>

    <?php
    if($file_type['image'] == 1) {
        $filemanager->createInstance();
        $instances = $filemanager->instance->getList();

        ?>
        <h3><?php e(__('file sizes')); ?></h3>

        <table class="stribe">
            <thead>
                <th><?php e(__('identifier', 'common')); ?></th>
                <th><?php e(__('image width')); ?></th>
                <th><?php e(__('image height')); ?></th>
                <th><?php e(__('file size')); ?></th>
                <th></th>
            </thead>
            <tbody>
                <?php
                foreach($instances AS $instance) {
                    if($instance['name'] == 'manual') CONTINUE;
                    ?>
                    <tr>
                        <td><a href="<?php e($instance['file_uri']); ?>"><?php e(__($instance['name'], 'filehandler')); ?></a></td>
                        <td><?php e($instance['width']); ?>px</td>
                        <td><?php e($instance['height']); ?>px</td>
                        <td>
                            <?php
                            if(is_numeric($instance['file_size'])) {
                                print(number_format($instance['file_size']/1000, 2, ",", ".")." Kb");
                            }
                            else {
                                print('-');
                            }
                            ?>
                        </td>
                        <td><a href="<?php e(url('crop', array('instance_type' => $instance['name']))); ?>"><?php e(__('custom cropping')); ?></a>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php if($kernel->user->hasModuleAccess('administration')): ?>
            <?php
            $shared_filehandler = $kernel->useShared('filehandler');
            ?>
            <ul class="options">
                <li><a href="<?php e(url('../sizes')); ?>"><?php e(__('manage your files sizes')); ?></a></li>
            </ul>
        <?php endif; ?>
        <?php
    }
    ?>


</div>


<div id="colTwo">

    <?php
    if($file_type['image'] == 1) {
        $filemanager->createInstance('system-small');
        ?>
        <div class="box" style="text-align: center;">
            <img src="<?php e($filemanager->instance->get('file_uri')); ?>" alt="" />
        </div>
        <?php
    }
    ?>


    <div id="keywords" class="box">
      <h2><?php e(__('keywords', 'keyword')); ?></h2>
       <ul class="options">
            <li><a href="<?php e(url('/keyword/connect', array('filemanager_id' => $filemanager->get('id')))); ?>"><?php e(__('add keywords', 'keyword')); ?></a></li>
        </ul>

    <?php
        $keyword = $filemanager->getKeywordAppender();
        $keywords = $keyword->getConnectedKeywords();
        if (is_array($keywords) AND count($keywords) > 0) {
            echo '<ul>';
            foreach ($keywords AS $k) {
                echo '<li>' . htmlentities($k['keyword']) . '</li>';
            }
            echo '</ul>';
        }
    ?>
  </div>

</div>
