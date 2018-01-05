<?php if (count($error) > 0) { ?>
<div class="messageStackError larger">
<?php 
for ($i = 0; $i < count($error); $i ++) {
	echo $error[$i]."<br/>";
}
?>
</div>
<div class="clearboth"></div>
<?php } ?>