<div style="margin-top:10px;">
<h2>Facebook Event Plugin</h2>
<table>
<form method="post" action="">
	<tr>
		<td><label for="merhaba">Facebook Application Token:</label></td>
		<td><input type="text" id="txt_facebook_event_token" name="txt_facebook_event_token" value=""/></td>
	</tr>
	<tr>
		<td><label for="merhaba">Latitude:</label></td>
		<td><input type="text" id="txt_facebook_event_latitude" name="txt_facebook_event_latitude" value=""/></td>
	</tr>
	<tr>
		<td><label for="merhaba">Longitude:</label></td>
		<td><input type="text" id="txt_facebook_event_longitude" name="txt_facebook_event_longitude" value=""/></td>
	</tr>
	<tr>
		<td><label for="merhaba">Distance:</label></td>
		<td><input type="text" id="txt_facebook_event_distance" name="txt_facebook_event_distance" value=""/></td>
	</tr>
	<tr>
		<td/>
		<td align="right"><input type="submit" id="submit" name="submit" value="<?php _e('Save'); ?>"></td>
	</tr>
	</form>
	<tr>
		<form method="post" action="">
			<td>Start Integration</td>
			<td align="right"><input type="submit" id="submit" name="start_facebook_integartion" value="<?php _e('Start'); ?>"></td>
		</form>
	</tr>
</table>

</div>