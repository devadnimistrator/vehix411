<tfoot>
	<tr>
		<td colspan="<?= $column_count?>"  height=1 ></td>
	</tr>
	<tr bgcolor=#ffffff>
		<td height="27" align=right colspan="<?= $column_count?>">
		<?php
			if (!isset($data_message)) $data_message = 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> data)';
			
			if ($list_split->number_of_rows > 0) :		//if 3
				$page_naviation = $list_split->display_count($data_message) . ' ' . $list_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info')));
			elseif ( $list_split->number_of_rows == 0 ):
				if (isset($empty_message)) 
					$page_naviation = $empty_message;
				else
					$page_naviation = "Empty data";
			endif;
		?>
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
				<tr>
					<td align="left" class="smallText" style="padding-left: 10px;">
						<?php echo $page_naviation; ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</tfoot>