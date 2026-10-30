//show hide payment fields
function showPayment() {
	var s = $('#order_status');
	if(isValid(s)) {
		if(s.val() > 0) {
			$('#payment_fields').show();
		} else {
			$('#payment_fields').hide();
		}
	}
}

//enable disable delivery details fields
function enableDeliveryDetails() {
	var p = $('#self_pickup');
	if(isValid(p)) {
		if(p.prop('checked')) {
			$("#delivery_details *").attr("disabled", true);
			$("#delivery_details *").prop('required',false); 
			
		} else {
			$("#delivery_details *").attr("disabled", false);
			$("#delivery_details *").prop('required',true);
			$('#delivery_addr2').prop('required', false);
		}
	}
}

function addImg() {
    var fieldWrapper = $("<div class=\"fieldwrapper\" />");
    
		var fName = $('#products').html();
    var removeButton = $('<button class="btn btn-danger btn-just-icon remove_product" type="button" style="display:inline;padding:10px;"><i class="fas fa-minus"></i></button>');
    removeButton.click(function() {
			$(this).parent().remove();
			countTotal(); //recalculate display total
    });
    fieldWrapper.append(fName);
    fieldWrapper.append(removeButton);
    $("#key").append(fieldWrapper);
}

//count current total based on selected products
function countTotal() {
	var price = qty = curr_id = total = 0; 	
  $('#product_list').find('div').each(function() {
		curr_id = $(this).find('select').val();
		qty = $(this).find('input').val();
		if(isValid(curr_id) && parseInt(curr_id) > 0) {
			if(isValid(qty) && parseInt(qty) > 0) {
				price = $("#product_hidden" + curr_id).val();
				total += (price*qty);
			}
		}
			
		$('#total_sales_display').val(total);
	});
}

function reloadOldVal() {
	if(product_ids.length > 0) {
		//reload selected products if have old val
		for(var i=0;i<product_ids.length;i++) {
			var fieldWrapper = $("<div class=\"fieldwrapper\" />");
			var list = $('#products').clone();
			
			var op = "select option[value=" + product_ids[i] + "]";
			list.find(op).attr('selected', 'selected');
			list.find('input').attr('value', product_qtys[i]);
			var removeButton = $('<button class="btn btn-danger btn-just-icon remove_product" type="button" style="display:inline;padding:10px;"><i class="fas fa-minus"></i></button>');
			 removeButton.click(function() {
				$(this).parent().remove();
				countTotal(); //recalculate display total
			});
			fieldWrapper.append(list.html());
			fieldWrapper.append(removeButton);
			$("#key").append(fieldWrapper);
		}
		countTotal();
		
	}	else {
		addProduct();
	}
}


$(document).ready(function() {
	reloadOldVal();
	
	//addProduct();
	showPayment();
	$('#order_status').on('change', function() {
		showPayment();
	});
	
	enableDeliveryDetails();
	$('#self_pickup').on('change', function() {
		enableDeliveryDetails();
	});	
	
  $("#product_list").on('change keyup mouseup', "select,input", function() {
		countTotal();
  });

});