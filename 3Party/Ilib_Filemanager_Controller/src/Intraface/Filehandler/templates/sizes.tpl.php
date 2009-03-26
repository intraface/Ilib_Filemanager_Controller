<h1><?php e(__('filehandler settings')); ?></h1>

<ul class="options">
    <li><a href="<?php e(url('../')); ?>"><?php e(__('go back')); ?></a></li>
</ul>

<form action="<?php e($this->url(null)); ?>" method="post">
    <input type="submit" name="all_files" value="<?php e(__('Delete all instances of all files')); ?>" />
</form>

<?php $instance_manager->error->view(); ?>

<?php
// $filehandler->createInstance();
// $instances = $filehandler->instance->getTypes();

$instances = $instance_manager->getList();
if(count($instances) > 0): ?>
    <table class="stripe">
        <caption><?php e(__('instance types')); ?></caption>
        <thead>
            <tr>
                <th><?php e(__('name')); ?></th>
                <th><?php e(__('maximum width')); ?></th>
                <th><?php e(__('maximum height')); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($instances AS $instance): ?>
                <tr>
                    <td><?php e($instance['name']); ?></td>
                    <td><?php e($instance['max_width']); ?></td>
                    <td><?php e($instance['max_height']); ?></td>
                    <td>
                        <a class="edit" href="<?php e(url('edit', array('type_key' => intval($instance['type_key'])))); ?>"><?php e(__('edit')); ?></a>
                      <?php if($instance['origin'] == 'overwritten') { ?>
                          <a class="delete" href="<?php e(url('./', array('delete_instance_type_key' => intval($instance['type_key'])))); ?>"><?php e(__('reset to standard')); ?></a>
                      <?php } elseif($instance['origin'] == 'custom') { ?>
                          <a class="delete" href="<?php e(url('./', array('delete_instance_type_key' => intval($instance['type_key'])))); ?>"><?php e(__('delete', 'common')); ?></a>
                      <?php }?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <ul class="options">
        <li><a href="<?php e(url('add')); ?>"><?php e(__('add new instance type')); ?></a></li>
    </ul>
<?php endif; ?>
