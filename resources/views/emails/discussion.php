Hi <?php echo ($user->first_name) ?>,<br />
There has been activity on the follow course(s) discussion board(s) for your review: <br />
<ul>
<?php foreach ($courses as $key => $row) { ?>
<li><a href="<?=url('course/'.$row['course']->slug)?>">Course Id #<?=$row['course']->id?> <?=$row['course']->title?></a> -- <?=$row['comments']?> comment(s)</li>
<?php } ?>
</ul>
<br />
You can opt out of receiving discussion board emails within your profile on <a href="<?= url()?>"><?= url()?></a>.<br />
<br />
Trauma Analytics Team
