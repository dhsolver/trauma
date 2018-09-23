Hi <?php echo ($user->first_name) ?>,<br />
Here are discussions you missed since your last read.<br />
<ul>
<?php foreach ($courses as $key => $row) { ?>
<li><a href="<?=url('course/'.$row['course']->slug)?>">Course Id #<?=$row['course']->id?> <?=$row['course']->title?></a> -- <?=$row['comments']?> comment(s)</li>
<?php } ?>
</ul>
<br />
Good luck with your learning.<br />
<br />
Trauma Analytics Team
