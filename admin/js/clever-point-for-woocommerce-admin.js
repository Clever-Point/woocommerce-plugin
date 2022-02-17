(function( $ ) {
	'use strict';
	$(document).ready(function(){
		$('#clever_point_create_voucher').on("click",function (e) {
			var $this=$(this);
			e.preventDefault();
			$this.addClass('disabled').addClass('is-active');
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : ajax_object.ajax_url,
				data : { action: "clever_point_create_voucher", order_id : $this.data('order'),comments: $('textarea[name="clever_point_comments"]').val(),cod: $('input[name="clever_point_cod"]').val(),weight: $('input[name="clever_point_weight"]').val(),parcels: $('input[name="clever_point_parcels"]').val()},
				success: function(response) {
					$this.removeClass('disabled').removeClass('is-active');
					if(response === "success") {
						alert($this.data('success'));
						location.reload();
					}
					else {
						alert(response);
						console.log(response);
					}
				}
			})
		});

		$('.clever_point_cancel_voucher').on("click",function (e) {
			e.preventDefault();
			var $this=$(this);
			$this.addClass('disabled').addClass('is-active');
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : ajax_object.ajax_url,
				data : {action: "clever_point_cancel_voucher", order_id : $this.data('order')},
				success: function(response) {
					$this.removeClass('disabled').removeClass('is-active');
					if(response.success) {
						alert($this.data('success'));
						location.reload();
					}
					else {
						console.log(response);
					}
				}
			})
		});

		$('#clever_point_print_voucher').on("click",function (e) {
			e.preventDefault();
			var $this=$(this);
			$this.addClass('disabled').addClass('is-active');
			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : ajax_object.ajax_url,
				data : {action: "clever_point_print_voucher", print_type: jQuery('#clever_point_print_voucher_type').val(), order_id :$this.data('order')},
				success: function(response) {
					console.log(response);
					$this.removeClass('disabled').removeClass('is-active');
					if (response.url)
						window.open(response.url, "_blank");
					else
						console.log(response);
				}
			})
		});
	});

})( jQuery );
