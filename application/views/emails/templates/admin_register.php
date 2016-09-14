New Registration Received,
<p>
<table>
	<tr>
		<td>Name</td>
		<td>:</td>
		<td><?php echo $user_first_name . ' ' . $user_last_name; ?></td>
	</tr>
	<?php if ($user_type === '1') { ?>
		<tr>
			<td>Business Legal Name</td>
			<td>:</td>
			<td><?php echo $business_legal_name; ?></td>
		</tr>
	<?php } ?>
	<tr>
		<td>User ID</td>
		<td>:</td>
		<td><?php echo $user_login; ?></td>
	</tr>
	<tr>
		<td>Email Address</td>
		<td>:</td>
		<td><?php echo $user_email; ?></td>
	</tr>
	<tr>
		<td>Contact</td>
		<td>:</td>
		<td><?php echo $user_primary_contact; ?></td>
	</tr>
</table>
</p>