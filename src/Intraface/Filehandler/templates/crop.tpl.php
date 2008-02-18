<h1><?php e(__('crop image').' '.$translation->get('file')); ?></h1>

<ul class="options" style="clear:both;">
    <?php if($type['resize_type'] != 'strict' && $unlock_ratio == 1): ?>
        <li><a href="<?php e($this->url(null, array('instance_type' => $filemanager->instance->get('type'), 'unlock_ratio' => 1))); ?>"><?php e(__('unlock image ratio')); ?></a></li>
    <?php elseif($type['resize_type'] != 'strict'): ?>
        <li><a href="<?php e($this->url(null, array('instance_type' => $filemanager->instance->get('type'), 'unlock_ratio' => 0))); ?>"><?php e(__('lock image ratio')); ?></a></li>
    <?php endif; ?>

</ul>

<?php $filemanager->error->view(); ?>

<fieldset>
    <legend><?php e(__('cropping')); ?></legend>
    <form method="POST" action="<?php e(url('./')); ?>">
    <input type="hidden" name="id" value="<?php e(intval($filemanager->get('id'))); ?>" />
    <input type="hidden" name="instance_type" value="<?php e($filemanager->instance->get('type')); ?>" />


    <div><?php e(__('crop')); ?>:
        <label for="width"><?php e(__('width')); ?></label>
        <input type="text" name="width" id="width" value="" size="4" />

        <label for="height"><?php e(__('height')); ?></label>
        <input type="text" name="height" id="height" value="" size="4" />


        <?php e(__('from top left corner')); ?>

        <label for="x"><?php e(__('x')); ?></label>
        <input type="text" name="x" id="x" value="" size="4" />

        <label for="y"><?php e(__('y')); ?></label>
        <input type="text" name="y" id="y" value="" size="4" />

        <input type="submit" name="crop" id="submit" value="<?php e(__('crop and resize image')); ?>" />
    </div>
    <div><?php e(__('your original image has the following dimensions (width x height)')); ?>: <?php echo intval($img_width); ?> x <?php echo intval($img_height); ?></div>
    </form>
</fieldset>


<img id="image" src="<?php echo $editor_img_uri; ?>" width="<?php echo intval($editor_img_width); ?>" height="<?php echo intval($editor_img_height); ?>" />
