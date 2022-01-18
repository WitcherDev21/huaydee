function select_option(id, option) {
	if (option === 'three_top' || option === 'three_tod' || option === 'three_top2' || option === 'three_und2') {
		var number_length = 3;
	} else if (option === 'two_top' || option === 'two_under') {
		var number_length = 2;
	} else {
		var  number_length = 1;
	}
	console.log(number_length);
	$('#number_'+id).attr('maxlength', number_length);
	$('#number_'+id).attr('minlength', number_length);
}

var id = 1;
var select_eq = 2;
function append_add_numbersets() {
	var model_add_numbersets = $('#model_add_numbersets').prop('outerHTML');
	id++;
	model_add_numbersets = model_add_numbersets.replace('model_add_numbersets','numbersets_list_'+id);
	model_add_numbersets = model_add_numbersets.replace('d-none','');
	model_add_numbersets = model_add_numbersets.replace(/{id}/g,id);
	model_add_numbersets = model_add_numbersets.replace('{option[]}','option[]');
	model_add_numbersets = model_add_numbersets.replace('{number[]}','number[]');
	model_add_numbersets = model_add_numbersets.replace('{required}','required');
	$('#add_numbersets_list').append(model_add_numbersets);
	$('#option_'+id).selectpicker('refresh');
	select_eq++;
}

$(function () {
	$("#add_numbersets_list").on("change", function(){
		if(select_eq==2){
			var lastValue = $('select option:selected').eq(select_eq).val();
		} else {
			var lastValue = $('select option:selected').last().val();
		}
		var oldval = $('#model_add_numbersets option:selected').val();
		$('#model_add_numbersets option[value='+oldval+']').removeAttr("selected");
		$('#model_add_numbersets option[value='+lastValue+']').attr('selected','selected');
		console.log(lastValue);
		if (lastValue === 'three_top' || lastValue === 'three_tod' || lastValue === 'three_top2' || lastValue === 'three_und2') {
			var maxmin = 3;
		} else if (lastValue === 'two_top' || lastValue === 'two_under') {
			var maxmin = 2;
		} else {
			var  maxmin = 1;
		}
		console.log(maxmin);
		$("#model_add_numbersets input").attr('minlength', maxmin);
		$("#model_add_numbersets input").attr('maxlength', maxmin);
		//select_eq++;
		//console.info(lastValue);
	});
});

function delete_add_numbersets(id) {
	if (id<0 || id>($('#add_numbersets_list li').length+1)) { return; }
	$('#numbersets_list_'+id).remove();
}

var modalConfirm = function(callback){
	$(".deleteconfirm").on("click", function(){
		$(".modal_confirm_delete").modal('show');
	});
	$(".btnconfirmdelete").on("click", function(){
		callback(true);
		$(".modal_confirm_delete").modal('hide');
	});
};

modalConfirm(function(confirm){
	if(confirm){
		var s = $(".deleteconfirm").data("id");
		delete_my_setnumber(s);
		//console.info(s);
	}
});

function delete_my_setnumber(id) {
		$.ajax({
			url: deleteUrl,
			cache: false,
			type: 'post',
			data: {
				id: id
			},
			success: function (data) {
				if (data.result=='success') {
					parent.location.href = indexUrl;
				} else {
					toastr.error(technical_crash,"Error");
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
}
