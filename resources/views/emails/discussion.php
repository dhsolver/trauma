Hi <?php echo ($user->first_name) ?>,<br />
Here are discussions you missed since your last read.<br />
<ul>
<?php foreach ($courses as $key => $row) { ?>
<li><a href="<?=url('course/'.$row['course']->slug)?>">Course Id #<?=$row['course']->id?> <?=$row['course']->title?></a> -- <?=$row['comments']?> comment(s)</li>
<?php } ?>
</ul>
<br />
You can opt out of receiving discussion board emails within your profile on <a href="https://www.traumaanalytics.com">www.traumaanalytics.com</a>.<br />
<br />
Trauma Analytics Team
