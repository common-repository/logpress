<?php
if(!defined("ABSPATH")) die();
?>

<h1>LogPress - Latest Page Access</h1>

<table class="wp-list-table widefat striped">
	<thead>
		<tr>
			<th>Date Time</th>
			<th>IP</th>
			<th>Page</th>
		</tr>
	</thead>
	<tbody>
<?php
global $wpdb;
$query_sql="SELECT log_on, log_ip, log_page FROM `{$this->name}` ORDER BY log_id DESC LIMIT 1000;";
$results = $wpdb->get_results($query_sql);
foreach($results as $log)
{
?>
	<tr>
		<td><?php echo $log->log_on; ?></td>
		<td><a href="http://www.traceip.net/?query=<?php echo $log->log_ip; ?>"><?php echo $log->log_ip; ?></a></td>
		<td><?php echo $log->log_page; ?></td>
	</tr>
<?php
}
?>
</tbody>
</table>