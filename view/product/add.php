<div class="wrap">
	<h1 class="wp-heading-inline">Create Product</h1>
	<div class="wp-header-end"></div>

	<form id="add-product-form">
		<table class="form-table" action="" method="post">
			<tr>
				<th scope="row"><label for="name">Product Name</label></th>
				<td><input type="text" name="name" id="name" class="regular-text" required></td>
			</tr>
			<tr>
				<th scope="row"><label for="price">Price</label></th>
				<td><input type="number" name="regular_price" id="regular_price" class="regular-text" required></td>
			</tr>
			<tr>
				<th scope="row"><label for="stock">Stock</label></th>
				<td>
					<select name="stock_status" id="stock_status">
						<option value="instock">In Stock</option>
						<option value="outofstock">Out of Stock</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="description">Description</label></th>
				<td><textarea name="description" id="description" class="regular-text"></textarea></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php wp_nonce_field( 'yourpropfirm_add_product', '_wpnonce' ); ?>
					<button class="button button-primary" type="submit">Save</button>
				</td>
			</tr>
		</table>
	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$('#add-product-form').on('submit', function (e) {
			e.preventDefault();

			var data = $(this).serialize();

			$.post({
				url: yourpropfirm.ajax_url,
				data: data + '&action=yourpropfirm_add_product',
				success: function (response) {
					if (response.success) {
						alert('Product added successfully');
						window.location.href = '<?php echo admin_url( 'admin.php?page=yourpropfirm' ); ?>';
					} else {
						alert(response.data);
					}
				}
			});
		});
	});
</script>