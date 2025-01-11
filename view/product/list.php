<div class="wrap">
	<h1 class="wp-heading-inline">Products</h1>
	<a href="<?php echo admin_url( 'admin.php?page=yourpropfirm-add-product' ); ?>" class="page-title-action">Add
		New</a>
	<div class="wp-header-end"></div>
	<table id="datatable" class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Price</th>
				<th>Stock</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Price</th>
				<th>Stock</th>
			</tr>
		</tfoot>
	</table>
</div>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$('#datatable').DataTable({
			ajax: {
				url: yourpropfirm.ajax_url,
				data: {
					action: 'yourpropfirm_get_products',
					_wpnonce: yourpropfirm.nonce
				},
			},
			columnDefs: [
				{ targets: 0, data: 'id', width: '64px' },
				{ targets: 1, data: 'name' },
				{ targets: 2, data: 'price', width: '280px' },
				{ targets: 3, data: 'stock_status', width: '180px' },
			],
		});
	});
</script>