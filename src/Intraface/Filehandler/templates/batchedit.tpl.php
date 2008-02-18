<h1><?php e(__('files')); ?></h1>

<ul class="options">
    <li><a href="index.php?use_stored=true"><?php e(__('regret', 'common')); ?></a></li>
</ul>



<form action="<?php e(url('./')); ?>" method="post">
<?php

for($i = 0, $max = count($files); $i < $max; $i++) {
    $this_filemanager = new FileManager($kernel, $files[$i]['id']);
    if ($this_filemanager->get('is_picture')) {

    }
    $keyword_object = $this_filemanager->getKeywordAppender();
    $files[$i]['keywords'] = $keyword_object->getConnectedKeywordsAsString();
    ?>
    <table class="stripe">
    <caption>Fil</caption>
        <tbody>
            <tr>
                <td rowspan="5" style="width: 280px;">
                    <?php if ($this_filemanager->get('is_picture')): ?>
                        <?php $this_filemanager->createInstance('small');?>
                        <img src="<?php e($this_filemanager->instance->get('file_uri')); ?>" alt="" />
                    <?php else: ?>
                        <img src="<?php e($this_filemanager->get('icon_uri')); ?>" alt="" />
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th><?php e(__('file')); ?></th>
                <td><?php e($files[$i]['file_name']); ?></td>
            </tr>
            <tr>
                <th><?php e(__('file description')); ?></th>
                <td><textarea style="width: 400px; height; 100px;" name="description[<?php e($files[$i]['id']); ?>]"><?php e($files[$i]['description']); ?></textarea></td>
            </tr>
            <tr>
                <th><?php e(__('keywords', 'keyword')); ?></th>
                <td><input type="text" name="keywords[<?php e($files[$i]['id']); ?>]" value="<?php e($files[$i]['keywords']); ?>" /></td>
            </tr>
            <tr>
                <th><?php e(__('file accessibility')); ?></th>
                <td><input type="radio" id="accessibility[<?php e($files[$i]['id']); ?>]_public" name="accessibility[<?php e($files[$i]['id']); ?>]" value="public" <?php if(isset($files[$i]['accessibility']) && $files[$i]['accessibility'] == 'public') e('checked="checked"'); ?> /><label for="accessibility[<?php e($files[$i]['id']); ?>]_public"><?php e(__('public')); ?></label> &nbsp; &nbsp; <input type="radio" id="accessibility[<?php e($files[$i]['id']); ?>]_intranet" name="accessibility[<?php e($files[$i]['id']); ?>]" value="intranet" <?php if(isset($files[$i]['accessibility']) && $files[$i]['accessibility'] == 'intranet') e('checked="checked"'); ?> /><label for="accessibility[<?php e($files[$i]['id']); ?>]_intranet"><?php e(__('intranet')); ?></label></td>
            </tr>
        </tbody>
    </table>
    <?php
}
?>
<p>
<input type="submit" value="<?php e(__('save', 'common')); ?>" />
<a href="<?php e(url('../', array('use_stored' => 'true'))); ?>"><?php e(__('regret', 'common')); ?></a>
</p>
</form>
