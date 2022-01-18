var max_num = 0;
var cur_num = 0;
var last_add_num = 0;
var get_mypoy = false;
var get_mysetnumber = false;

var curr_option = [];

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

$(document).ready(function () {
    
    $(".yeekee__number").hide();
    $(".yeekee__lists-number").hide();
    $('[data-id=numpage_1]').addClass("active");

    poyList = ({
        'bet_id': playId,
        'poy_list': [],
    });

    window.localStorage.setItem('poy', JSON.stringify(poyList));

		var poy = JSON.parse(window.localStorage.getItem('poy'));
		$.each(poy.poy_list, function (index, value) {
			if (value.last_add_num > last_add_num) {
				last_add_num = value.last_add_num;
			}
		});

		$(".btn-cancle-last-add-num").on("click", function(){
			var poy = JSON.parse(window.localStorage.getItem('poy'));
			if (poy.poy_list.length == 0 || last_add_num == 0) return;
      delete_last_add_num();
    });

		$(".btn-reset").on("click", function(){
			var poy = JSON.parse(window.localStorage.getItem('poy'));
			if (poy.poy_list.length == 0) return;
        $("#modal_confirm_reset").modal('show');
    });

		$('.mypoy').click(function() {
			if (!get_mypoy) {
				get_my_poy();
				get_mypoy = true;
			}
		});
        $('.triggerPoy').click(function() {
      // var poy = JSON.parse(window.localStorage.getItem('poy'));
      // if (poy.poy_list.length > 0) return;


			if (!get_mysetnumber) {
				get_my_set_number();
				get_mysetnumber = true;
			}

       $('#poy').toggleClass('open');
        if($('#poy').hasClass('open')){
            $('#footer-member').addClass('d-none');
        }
      $('#content').toggleClass('blur');
        var fixbot1 = $('#poy .fixbot').height();
        var wh = $(window).height();
        var fixbott1 = wh-fixbot1-50;
        $('#poy .content-scroll').css('height',fixbott1);
       return false;
    });
    $('.triggerPrice').click(function() {
      var poy = JSON.parse(window.localStorage.getItem('poy'));
      if (poy.poy_list.length == 0) return;
        $('#price').toggleClass('open');
        if($('#price').hasClass('open')){
            $('#footer-member').addClass('d-none');
        }
       $('#content').toggleClass('blur');
        var fixbot11 = $('#price .fixbot').height();
        var wh = $(window).height();
        var fixbott11 = wh-fixbot11-50;
        $('#price .content-scroll').css('height',fixbott11);
        return false;
     });

		 $('.triggerSendpoy').click(function() {

       pre_send_poy();
             $('.triggerSendpoy').prop('disabled', true);
      });



    $('.betagain').click(function() {
      $('#printpoy').removeClass('open');
      $('#content').removeClass('blur');
    });

     $("#capturePoy").click(function() {
        $(".content").css('height','100%');
        html2canvas($("#capture"), {
            onrendered: function(canvas) {
                var image = canvas.toDataURL("image/png");
                download(image, "fifalotto_poy.png", "image/png");
                $(".content").removeAttr('style');
            }
        });
    });

		$('.btn-tanghuay,.option2btn').on('click',function(){
        var btid = $(this).attr('id');
        var labelid = '#'+btid+'_label';
        if($(labelid).hasClass("d-none")){
            $(labelid).removeClass("d-none");
        }else{
            $(labelid).addClass("d-none");
        }
    });

		if (ying_only) {
			//alert('ying_only');
			$('[data-id=numpage_1]').addClass("d-none");
			$('[data-id=numpage_2]').addClass("d-none");
			$('#numpage_2').removeClass("d-none");
			$('#numpage_1').addClass("d-none");
			$('#show_poy_list').removeClass("d-flex");
			$(".box__play").hide();
			$(".yeekee__number").show();
			$(".yeekee__lists-number").show();
			$(".cart-item-lists").hide();
		}

        $('#teng_bon_3').click();

		$(".btn-panghuay").click(function (e) {
				//console.info($(this).prop('id')+' active= '+$(this).hasClass("active"));
				$('.btn-tanghuay#'+$(this).prop('id')).click();
		});
		$(".panghuay_number").on("click", function(){
			if ($(this).hasClass("active")) {
				delete_the_num($(this).data("id").toString());
			} else {
				var check_option_number = false;

				//$('.panghuay_number[data-id="'+$(this).data("id")+'"]').addClass('active');
				for (var i = 0; i <= curr_option.length - 1; i++) {
		        if (curr_option[i].slice(-1)*1 === $(this).data("id").toString().length) {
								check_option_number = true;
		            break;
		        }
		    }
				if (check_option_number) {
						prepare_poy($(this).data("id").toString());
				} else {
					return false;
				}
			}
			define_poy($(this).data("id").toString());
    });
		$(".panghuay_option_2").on("click", function(){
			//console.log($(this).data("option"),$(this).data("id").toString());
			if ($('.btn-panghuay#teng_bon_2').hasClass("active")===false && $('.btn-tanghuay#teng_lang_2').hasClass("active")===false) {
				//console.log('teng_bon_2 teng_lang_2 == false');
				return false;
			}
			if ($('#'+$(this).data('option')+'_label').hasClass('d-none')) {
				$('.bet_two.option2btn#'+$(this).data('option')).click();
			}
			if ($(this).hasClass("active")) {
				//console.log('delete_num_option2',$(this).data('option'),$(this).data("id").toString());
				delete_num_option2($(this).data('option'),$(this).data("id").toString());
			} else {
				prepare_poy($(this).data("id").toString());
			}
			if ($('#'+$(this).data('option')+'_label').hasClass('d-none')==false) {
				$('.bet_two.option2btn#'+$(this).data('option')).click();
			}
			define_poy();
    });

		$('#search-number').on('input paste', function () {
		    $("#numlist-tabContent1 div.column").removeClass("d-inline");
		    $("#numlist-tabContent1 div.column").hide();
		    $("#numlist-tabContent1 label:contains('" + $(this).val() + "')").parent().show();
		    $("#numlist-tabContent1 label:contains('" + $(this).val() + "')").parent().addClass("d-inline");
		});

		$('#search-number2').on('input paste', function () {
		    $("#numlist-tabContent2 div.column").removeClass("d-inline");
		    $("#numlist-tabContent2 div.column").hide();
		    $("#numlist-tabContent2 label:contains('" + $(this).val() + "')").parent().show();
		    $("#numlist-tabContent2 label:contains('" + $(this).val() + "')").parent().addClass("d-inline");
		});

		$('#search-number3').on('input paste', function () {
		    $("#numlist-tabContent3 div.column").hide();
		    $("#numlist-tabContent3 label:contains('" + $(this).val() + "')").parent().show();
		});

        $('#teng_bon_3').click();
        $('#teng_bon_3').click();

});


function define_poy(num=null) {

    var bld = JSON.parse(bet_list_detail);
    //console.log('bld',bld);
    $('.bet_name').html(bld.bet_name);
    $('.bet_round').html(bld.bet_round);

		var poy = JSON.parse(window.localStorage.getItem('poy'));

    if (!poy || poy.bet_id != bld.bet_id) {
        reset_poy();
    } else {
			$.each(poy.poy_list, function (index, value) {
				if (poy.poy_list[index].number != num) {
					if ($('.panghuay_number[data-id="'+poy.poy_list[index].number+'"]').hasClass('active')) {
						//console.log('already active',poy.poy_list[index].number);
					} else {
						//console.log('set active -> ',poy.poy_list[index].number);
						$('.panghuay_number[data-id="'+poy.poy_list[index].number+'"]').addClass('active');
					}
				}
			});
		}
}

function reset_poy() {
    var bld = JSON.parse(bet_list_detail);
    window.localStorage.setItem('poy', JSON.stringify({
        'bet_id': bld.bet_id,
        'poy_list': [],
    }));
	last_add_num = 0;
    show_bet_num();
	$('.panghuay_number').removeClass('active');
}

var gen_2_ble = ['00', '11', '22', '33', '44', '55', '66', '77', '88', '99'];
var gen_2_low = ["00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35", "36", "37", "38", "39", "40", "41", "42", "43", "44", "45", "46", "47", "48", "49"];
var gen_2_high = ["50", "51", "52", "53", "54", "55", "56", "57", "58", "59", "60", "61", "62", "63", "64", "65", "66", "67", "68", "69", "70", "71", "72", "73", "74", "75", "76", "77", "78", "79", "80", "81", "82", "83", "84", "85", "86", "87", "88", "89", "90", "91", "92", "93", "94", "95", "96", "97", "98", "99"];
var gen_2_even = ["00", "02", "04", "06", "08", "10", "12", "14", "16", "18", "20", "22", "24", "26", "28", "30", "32", "34", "36", "38", "40", "42", "44", "46", "48", "50", "52", "54", "56", "58", "60", "62", "64", "66", "68", "70", "72", "74", "76", "78", "80", "82", "84", "86", "88", "90", "92", "94", "96", "98"];
var gen_2_odd = ["01", "03", "05", "07", "09", "11", "13", "15", "17", "19", "21", "23", "25", "27", "29", "31", "33", "35", "37", "39", "41", "43", "45", "47", "49", "51", "53", "55", "57", "59", "61", "63", "65", "67", "69", "71", "73", "75", "77", "79", "81", "83", "85", "87", "89", "91", "93", "95", "97", "99"];

var main_box_button = '.box__play .setting__number button';

$(main_box_button).click(function () {
    if ($(this).prop('id') == "option_2_ble" || $(this).prop('id') == "option_2_low" || $(this).prop('id') == "option_2_high" || $(this).prop('id') == "option_2_even" || $(this).prop('id') == "option_2_odd") {
        var num = window[$(this).prop('id').replace('option', 'gen')];
        if (num) {
            add_poy_list(num, true);
        }
        return;
    }

	  if ($(this).hasClass("active")) {
        $(this).removeClass("active");
				$('.btn-panghuay#'+$(this).prop('id')).removeClass("active");
        $("#content_" + $(this).data('id')).hide();
        remove_option($(this).prop('id'));
        showhide_keyboard();
        two_option();

    } else {
        $(this).addClass("active");
				$('.btn-panghuay#'+$(this).prop('id')).addClass("active");
        check_conflict($(this).prop('id'));
        showhide_keyboard();
        two_option();
        $("#content_" + $(this).data('id')).show();

    }
});


function two_option() {

    var showhide = true;
    if ($.inArray("teng_bon_3", curr_option) >= 0 ||
        $.inArray("tode_3", curr_option) >= 0 ||
        $.inArray("teng_lang_nha_3", curr_option) >= 0 ||
        $.inArray("teng_lang_3", curr_option) >= 0
    ) {
        showhide = false;
    } else if (!$('#teng_bon_2').hasClass("active") && !$('#teng_lang_2').hasClass("active")) {
        showhide = false;
    }

    if (!showhide) {
        $('.box__show-number .lists .number').eq(1).removeClass("bet_two_ative");
        $('.box__show-number .lists .number').eq(2).removeClass("bet_two_ative");
        $(".box__two-option").hide();
				if ($.inArray( "option_2_19" , curr_option ) >= 0) { remove_option("option_2_19"); }
        if ($.inArray( "option_2_roodnha" , curr_option ) >= 0) { remove_option("option_2_roodnha"); }
				if ($.inArray( "option_2_roodlung" , curr_option ) >= 0) { remove_option("option_2_roodlung"); }
    } else {
        $('.box__show-number .lists .number').eq(1).addClass("bet_two_ative");
        $('.box__show-number .lists .number').eq(2).addClass("bet_two_ative");
        $(".box__two-option").show();
    }
}


function showhide_keyboard() {
    var invsible_keyboard_option = ["shuffle_3", "shuffle_2"];

    var showhide = true;
    if (curr_option.length == 0) {
        showhide = false;
    } else if (curr_option.length == 2 && $.inArray("shuffle_3", curr_option) >= 0 && $.inArray("shuffle_2", curr_option) >= 0) {
        showhide = false;
    } else if (curr_option.length == 1 && ($.inArray("shuffle_3", curr_option) >= 0 || $.inArray("shuffle_2", curr_option) >= 0)) {
        showhide = false;
    }

		$("#oversamtua").addClass("overlay-disable");
		$("#ninetybtn").addClass("overlay-disable");
		$("#roodfrontbtn").addClass("overlay-disable");
		$("#roodbackbtn").addClass("overlay-disable");
		$("#twonumberbtn").addClass("overlay-disable");
		$("#runnumberbtn").addClass("overlay-disable");

    if (showhide) {
        $("#content_lottery").show();
        $(".group__keyboard").show();

        if ($.inArray("teng_bon_1", curr_option) >= 0 || $.inArray("teng_lang_1", curr_option) >= 0 || $.inArray("option_2_19", curr_option) >= 0 || $.inArray("option_2_roodnha", curr_option) >= 0 || $.inArray("option_2_roodlung", curr_option) >= 0) {
            set_show_num(1);
						if ($.inArray("teng_bon_1", curr_option) >= 0 || $.inArray("teng_lang_1", curr_option) >= 0) {
							$('#pills-run-tab').click();
							$("#runnumberbtn").removeClass("overlay-disable");
						} else {
							$('#pills-2-tab').click();
							$("#ninetybtn").removeClass("overlay-disable");
							$("#roodfrontbtn").removeClass("overlay-disable");
							$("#roodbackbtn").removeClass("overlay-disable");
							$("#twonumberbtn").removeClass("overlay-disable");
						}
        } else if ($.inArray("teng_bon_3", curr_option) >= 0 || $.inArray("tode_3", curr_option) >= 0 || $.inArray("teng_lang_nha_3", curr_option) >= 0 || $.inArray("teng_lang_3", curr_option) >= 0) {
            set_show_num(3);
						$('#pills-3-tab').click();
						$("#oversamtua").removeClass("overlay-disable");
        } else {
            set_show_num(2);
						$('#pills-2-tab').click();
						$("#ninetybtn").removeClass("overlay-disable");
						$("#roodfrontbtn").removeClass("overlay-disable");
						$("#roodbackbtn").removeClass("overlay-disable");
						$("#twonumberbtn").removeClass("overlay-disable");

        }
    } else {
        $("#content_lottery").hide();
        $(".group__keyboard").hide();
    }

}

function set_show_num(num) {
    $('#bet_num').html('');
    for (var i = 1; i <= num; i++) {
        $('#bet_num').append('<label class="number"> </label> ');
    }
    max_num = num;
	cur_num = 0;
}

var conflict_option = {
    "teng_bon_3": ["teng_bon_1", "teng_lang_1", "teng_bon_2", "teng_lang_2", "shuffle_2", "option_2_19", "option_2_roodnha", "option_2_roodlung"],
    "tode_3": ["teng_bon_1", "teng_lang_1", "shuffle_3", "teng_bon_2", "teng_lang_2", "shuffle_2", "option_2_19", "option_2_roodnha", "option_2_roodlung"],
    "teng_lang_nha_3": ["teng_bon_1", "teng_lang_1", "teng_bon_2", "teng_lang_2", "shuffle_2", "option_2_19", "option_2_roodnha", "option_2_roodlung"],
    "teng_lang_3": ["teng_bon_1", "teng_lang_1", "teng_bon_2", "teng_lang_2", "shuffle_2", "option_2_19", "option_2_roodnha", "option_2_roodlung"],
    "teng_bon_2": ["teng_bon_1", "teng_lang_1", "teng_bon_3", "tode_3", "teng_lang_nha_3", "teng_lang_3", "shuffle_3"],
    "teng_lang_2": ["teng_bon_1", "teng_lang_1", "teng_bon_3", "tode_3", "teng_lang_nha_3", "teng_lang_3", "shuffle_3"],
    "shuffle_3": ["teng_bon_1", "teng_lang_1", "tode_3", "teng_bon_2", "teng_lang_2", "shuffle_2", "option_2_19", "option_2_roodnha", "option_2_roodlung"],
    "shuffle_2": ["teng_bon_1", "teng_lang_1", "tode_3", "teng_bon_3", "tode_3", "teng_lang_nha_3", "teng_lang_3", "shuffle_3", "option_2_19", "option_2_roodnha", "option_2_roodlung"],
    "teng_bon_1": ["teng_bon_3", "tode_3", "teng_lang_nha_3", "teng_lang_3", "teng_bon_2", "teng_lang_2", "shuffle_3", "shuffle_2", "option_2_19", "option_2_roodnha", "option_2_roodlung"],
    "teng_lang_1": ["teng_bon_3", "tode_3", "teng_lang_nha_3", "teng_lang_3", "teng_bon_2", "teng_lang_2", "shuffle_3", "shuffle_2", "option_2_19", "option_2_roodnha", "option_2_roodlung"],
    "option_2_19": ["shuffle_2", "option_2_roodnha", "option_2_roodlung"],
    "option_2_ble": [],
    "option_2_roodnha": ["shuffle_2", "option_2_19", "option_2_roodlung"],
    "option_2_roodlung": ["shuffle_2", "option_2_19", "option_2_roodnha"],
    "option_2_low": [],
    "option_2_high": [],
    "option_2_even": [],
    "option_2_odd": [],
};


function check_conflict(option) {

    if (conflict_option[option].length > 0) {
        for (var i = 0; i <= conflict_option[option].length - 1; i++) {
					  if ($.inArray( conflict_option[option][i] , curr_option ) >= 0) {
	            remove_option(conflict_option[option][i]);
	            $('#' + conflict_option[option][i]).removeClass("active");
							$('.btn-panghuay#' + conflict_option[option][i]).removeClass("active");

			        var labelid2 = '#'+conflict_option[option][i]+'_label';
							$(labelid2).addClass("d-none");

							$('#content_'+conflict_option[option][i]).addClass("d-none");
						}
        }
    }
		$('#content_'+option).removeClass("d-none");
    add_option(option);
}

function add_option(option) {
    if (!curr_option) {
        curr_option = [];
    }
    curr_option.push(option);
}

function remove_option(option) {
    for (var i = 0; i <= curr_option.length - 1; i++) {
        if (curr_option[i] === option) {
            curr_option.splice(i, 1);
            break;
        }
    }
}

$(document).keyup(async function(e) {
    if($('#price').hasClass('pagemodal-wrapper open')) return true;
    if($('#printpoy').hasClass('pagemodal-wrapper open')) return true;
    if($('#sendpoy').hasClass('pagemodal-wrapper open')) return true;
    if($('#numpage_1').hasClass('d-none')) return true;
    if($('#nav-panghuay-tab').hasClass('active')) return true;


    let chr;
    if (e.keyCode >= 48 && e.keyCode <= 57) {
        let keyCode = e.keyCode;
        let chrCode = keyCode - 48 * Math.floor(keyCode / 48);
        chr = String.fromCharCode((96 <= keyCode) ? chrCode: keyCode);
    }
    if((e.keyCode >= 96 && e.keyCode <= 105) || (e.keyCode === 8)){
        chr = e.key;
    }
    //console.info(chr);
    if(chr){
        if (cur_num <= 0) {
            cur_num = 0;
        }
        if (chr === "Backspace" && cur_num <= (max_num-1)) {
            $('.box__show-number .lists .number').eq(cur_num).html('');
            if (cur_num <= 0) {
                $('.box__show-number .lists .number').eq(0).html('<span></span>');
            } else {
                $('.box__show-number .lists .number').eq(cur_num - 1).html('<span></span>');
            }
            cur_num--
        } else {
            var reg = new RegExp('^\\d+$');
            if(!reg.test(chr)) return true;
            $('.box__show-number .lists .number').eq(cur_num).html(chr);
            $('.box__show-number .lists .number').eq(cur_num + 1).html('<span></span>');
            cur_num++
        }
        if (cur_num == max_num && max_num > 0) {
            await sleep(10);
            cur_num = 0;
            var num = $('.box__show-number .lists .number').eq(0).html();
            var num2 = '';
            var num3 = '';
            if (max_num > 1) num2 = $('.box__show-number .lists .number').eq(1).html();
            if (max_num > 2) num3 = $('.box__show-number .lists .number').eq(2).html();
            prepare_poy(num + num2 + num3);
            airasia(num,num2,num3);

						$('.panghuay_number[data-id="'+num + num2 + num3+'"]').addClass('active');

            $('.box__show-number .lists .number').html('');
        } else {
            return;
        }
    }
});


$('.box__show-number .box__keyboard button').click(async function () {
    if (cur_num <= 0) {
        cur_num = 0;
    }
    if ($(this).data('id') == "delete" && cur_num <= (max_num-1)) {
        $('.box__show-number .lists .number').eq(cur_num).html('');
        if (cur_num <= 0) {
            $('.box__show-number .lists .number').eq(0).html('<span></span>');
        } else {
            $('.box__show-number .lists .number').eq(cur_num - 1).html('<span></span>');
        }
        cur_num--
    } else {
        var reg = new RegExp('^\\d+$');
        if(!reg.test($(this).data('id'))) return true;
        $('.box__show-number .lists .number').eq(cur_num).html($(this).data('id'));
        $('.box__show-number .lists .number').eq(cur_num + 1).html('<span></span>');
        cur_num++
    }
    if (cur_num == max_num && max_num > 0) {
        await sleep(10);
        cur_num = 0;
        //Type BET
        var typebet = $('.box__play').data('target');
        var num = $('.box__show-number .lists .number').eq(0).html();
        var num2 = '';
        var num3 = '';
        if (max_num > 1) num2 = $('.box__show-number .lists .number').eq(1).html();
        if (max_num > 2) num3 = $('.box__show-number .lists .number').eq(2).html();
        //รับค่าส่งตามใจชอบ
        //console.info(typebet);
        prepare_poy(num + num2 + num3);
        // airasia(num,num2,num3);

				$('.panghuay_number[data-id="'+num + num2 + num3+'"]').addClass('active');

        $('.box__show-number .lists .number').html('');
    } else {
        return;
    }
});


async function airasia(num, num2, num3) {
    $('#bet_num').append('<div class="animate__number active"><span>' + num + '</span><span>' + num2 + '</span><span>' + num3 + '</span></div>');
    await sleep(500);
    $('.animate__number').remove();
    //console.info('test');
}

function prepare_poy(num) {
    if (num == '<span></span>') {
        return;
    }
    var num_count = 0;
    var add_num = [];

    for (var i = 0; i <= curr_option.length - 1; i++) {
        if (curr_option[i] === "shuffle_2") {
            if (($.inArray("teng_bon_2", curr_option) >= 0 || $.inArray("teng_lang_2", curr_option) >= 0) && $.inArray("option_2_19", curr_option) < 0 && $.inArray("option_2_roodnha", curr_option) < 0 && $.inArray("option_2_roodlung", curr_option) < 0) {
                add_num = $.merge(add_num, shuffle_num(num, 2));
            }
        } else if (curr_option[i] === "shuffle_3") {
            if (max_num == 3) {
                add_num = $.merge(add_num, shuffle_num(num, 3));
            }
        } else if (curr_option[i] === "option_2_19") {
            add_num = $.merge(add_num, gen_19(num));
        } else if (curr_option[i] === "option_2_roodnha") {
            add_num = $.merge(add_num, rood_num(num, 'nha'));
        } else if (curr_option[i] === "option_2_roodlung") {
            add_num = $.merge(add_num, rood_num(num, 'lung'));
        } else {
            if (curr_option[i] === "teng_bon_1" || curr_option[i] === "teng_lang_1") {
                // 1
                add_num = $.merge(add_num, [num]);
            } else if (curr_option[i] === "teng_bon_2" || curr_option[i] === "teng_lang_2") {
                // 2
                if ($.inArray("shuffle_2", curr_option) < 0 && $.inArray("option_2_19", curr_option) < 0 && $.inArray("option_2_roodnha", curr_option) < 0 && $.inArray("option_2_roodlung", curr_option) < 0) {
                    if (max_num == 3) {
                        add_num = $.merge(add_num, [num.substr(1, 2)]);
                    } else {
                        add_num = $.merge(add_num, [num]);
                    }
                }
            } else if ($.inArray("shuffle_3", curr_option) < 0) {
                // 3
                add_num = $.merge(add_num, [num]);
            }
        }
    }
    add_num.sort();
    $.unique(add_num);
    add_poy_list(add_num);
}

function add_poy_list(add_num, option_2 = false) {
    if (add_num.length <= 0) {
        return;
    }
    last_add_num = last_add_num + 1;

    var poy = JSON.parse(window.localStorage.getItem('poy'));

    var total_add_poy_list = 0;
    var bld = JSON.parse(bet_list_detail);
    var add_poy_list = [];
    for (var i = 0; i <= curr_option.length - 1; i++) {
        if (curr_option[i] === "teng_bon_1" || curr_option[i] === "teng_lang_1" || curr_option[i] === "teng_bon_2" || curr_option[i] === "teng_lang_2" || curr_option[i] === "teng_bon_3" || curr_option[i] === "teng_lang_3" || curr_option[i] === "teng_lang_nha_3" || curr_option[i] === "tode_3") {
            if (option_2) {
                if (curr_option[i] != "teng_bon_2" && curr_option[i] != "teng_lang_2") {
                    continue;
                }
            }

            var t = [];
            var num_list = [];
            var num_count = 0;

            t = curr_option[i].split("_");
            num_count = t[t.length - 1];

            num_list = $.grep(add_num, function (v) {
                return v.length === num_count * 1;
            });

            $.each(num_list, function (index, value) {
                var is_duplicate = false;

                var multiply = bld[curr_option[i]];
                //check change multiply.
                $.each(bld.limit, function (k, v) {
                    if (v.option === curr_option[i] && v.number === value) {
                        multiply = v.multiply;
                        return false;
                    }
                });

                var bet_min_max = $.grep(bld.bet_min_max, function (v) {
                    //console.log(curr_option[i],' --- bet_min ',v.bet_min);
                    return v.bet_option === curr_option[i];
                });

                add_poy_list.push({
                    'option': curr_option[i],
                    'number': value,
                    'price': parseInt(bet_min_max[0].bet_min),
                    'multiply': multiply,
                    'is_duplicate': is_duplicate,
                    'last_add_num': last_add_num,
                });

                total_add_poy_list++;
            });
        }
    }

    set_poy_list(add_poy_list);

    //console.log('add_poy_list',poy);

    // ทำ notify ตรงนี้นะ
    notify('เพิ่ม ' + total_add_poy_list + ' ' + 'รายการ', "success");
    show_bet_num();
}

function set_poy_list(add_poy_list) {
    var poy = JSON.parse(window.localStorage.getItem('poy'));
    $.each(add_poy_list, function (index, v) {
        //check+update duplicate
        $.each(poy.poy_list, function (index2, value2) {
            if (value2.option == v.option && value2.number == v.number) {
                add_poy_list[index].is_duplicate = true;
                poy.poy_list[index2].is_duplicate = true;
            }
        });

        poy.poy_list.push(v);
    });
    poy.poy_list.sort(function (a, b) {
        if (a.number < b.number) {
            return -1;
        } else if (a.number > b.number) {
            return 1;
        } else {
            return 0;
        }
    });
    poy.poy_list.sort(function (a, b) {
        if (a.option < b.option) {
            return -1;
        } else if (a.option > b.option) {
            return 1;
        } else {
            return 0;
        }
    });
    window.localStorage.setItem('poy', JSON.stringify(poy));
}

function pad(str, max) {
    str = str.toString();
    return str.length < max ? pad("0" + str, max) : str;
}

function gen_19(num) {
    if (max_num != 1) return;
    var result = [];
    for (var i = 0; i <= 9; i++) {
        result.push(num + i.toString());
        result.push(i.toString() + num);
    }
    result.sort();
    $.unique(result);
    return result;
}

function rood_num(num, nhalung) {
    if (max_num != 1) return;
    var result = [];
    for (var i = 0; i <= 9; i++) {
        if (nhalung == 'nha') {
            result.push(num + i.toString());
        } else {
            result.push(i.toString() + num);
        }
    }
    return result;
}

function shuffle_num(num, num_23) {
    if (max_num < 2) return;
    var shuffle_num = [];

    shuffle_num[1] = num.substr(0, 1);
    shuffle_num[2] = num.substr(1, 1);
    shuffle_num[3] = '';
    if (max_num > 2) shuffle_num[3] = num.substr(2, 1);
    if (num_23 == 2 && max_num == 3) {
        shuffle_num[1] = '';
    }
    var result = [];
    result.push(shuffle_num[1] + shuffle_num[2] + shuffle_num[3]);
    result.push(shuffle_num[1] + shuffle_num[3] + shuffle_num[2]);
    result.push(shuffle_num[2] + shuffle_num[1] + shuffle_num[3]);
    result.push(shuffle_num[2] + shuffle_num[3] + shuffle_num[1]);
    result.push(shuffle_num[3] + shuffle_num[1] + shuffle_num[2]);
    result.push(shuffle_num[3] + shuffle_num[2] + shuffle_num[1]);
    result.sort();
    $.unique(result);
    //console.log(result);
    return result;
}

var bet_title_list = {
    'teng_bon_1': 'วิ่งบน',
    'teng_lang_1': 'วิ่งล่าง',
    'teng_bon_2': 'สองตัวบน',
    'teng_lang_2': 'สองตัวล่าง',
    'teng_bon_3': 'สามตัวบน',
    'tode_3': 'สามตัวโต๊ด',
    'teng_lang_nha_3': 'สามตัวหน้า',
    'teng_lang_3': 'สามตัวล่าง',
};

function show_bet_num() {
    var poy = JSON.parse(window.localStorage.getItem('poy'));
    //console.log('show_bet_num',poy);
    if (poy.poy_list.length <= 0) {
        $('.bet_num_count').html('');
        $('#total_poy_list').html('');
        $('#show_bet_num').html('');
        $('#poy_list').html('');
        $('#pl_confirm').html('');
        $('.box__button-number-sets').removeClass('visible');
        $('.box__button-show-lists').addClass('visible');
        return;
    }
    $('.box__button-show-lists').removeClass('visible');
    $('.box__button-number-sets').addClass('visible');
    $('.bet_num_count').html(poy.poy_list.length + ' รายการ');
    $('#total_poy_list').html('รายการแทงทั้งหมด' + ' ' + poy.poy_list.length + ' ' + 'รายการ');
    $('#show_bet_num').html('');
    var num = [];

    $('#poy_list').html('');
    $('#pl_confirm').html('');
    var check_bet_title = '';
    var bet_cnt = 1;
    var total_poy_list = 0;
    var total_price = 0;

    var model_append_poy_option = $('#model_poy_option').prop('outerHTML');
    var model_append_pl = $('#model_poy_list').prop('outerHTML');
    var model_pre_confirm = $('#model_pre_confirm').prop('outerHTML');
    var model_pre_confirm_list = $('#model_pre_confirm_list').prop('innerHTML');

    var append_poy_option = '';

    $.each(poy.poy_list, function (index, value) {
        var op = poy.poy_list[index].option;
        var bet_title = bet_title_list[op];
        var number = value.number;
        var multiply = value.multiply;
        var is_duplicate_class = '';
        num.push(number);
        var bld = JSON.parse(bet_list_detail);
        var price = value.price;
        if (value.is_duplicate) {
            is_duplicate_class = 'number-is-duplicate';
        }

        //ส่่วนของหน้าใส่ราคา
        if (check_bet_title != bet_title) {
            append_poy_option = model_append_poy_option;
            check_bet_title = bet_title;
            append_poy_option = append_poy_option.replace('model_poy_option', 'poy_option_' + op);
            append_poy_option = append_poy_option.replace('{bet-option-name}', bet_title);
            bet_cnt = 1;
            $('#poy_list').append(append_poy_option); //Table หลัก
            $('#poy_option_' + op).removeClass('d-none');
            $('#poy_option_' + op + " #model_poy_list").remove();

            //ส่วนการยืนยัน
            var append_pl_confirm = model_pre_confirm;
            append_pl_confirm = append_pl_confirm.replace('{pl_confirm_op}', 'pl_confim_' + op);
            append_pl_confirm = append_pl_confirm.replace('{bet-name}', bet_title);
            append_pl_confirm = append_pl_confirm.replace('d-none', '');
            $('#pl_confirm').append(append_pl_confirm);

            $('#show_bet_num').append(bet_title + ' ');
        }
        $('#show_bet_num').append('<li>' + number + '</li> ');

        var append_pl = model_append_pl;
        append_pl = append_pl.replace(/model_poy_list/g, 'pl_' + index);
        append_pl = append_pl.replace(/{poy-list-id}/g, index);
        append_pl = append_pl.replace(/{is-duplicate-class}/g, is_duplicate_class);
        append_pl = append_pl.replace('{bet_cnt}', bet_cnt);
        append_pl = append_pl.replace('{pl_number}', number);
        append_pl = append_pl.replace('{pl-price}', price);
        append_pl = append_pl.replace('{bet_multiply}', multiply);
        append_pl = append_pl.replace('{pl-win}', (multiply * price));
        $('#poy_option_' + op).append(append_pl); //tr
        //console.log(append_poy_option);

        $('#pl_price_' + index).change(function () {
            change_price(index);
        });
        $('#del_pl_' + index).click(function () {
            //alert('delete_poy_list');
            delete_poy_list(index, number);
        });

        //ส่วนการยืนยัน
        var append_pl_confirm_pl_list = model_pre_confirm_list;
        // console.info(bet_title)
        var textPrice = price;
        if (bld['discount'][op] > 0) {
            discountTotal = price * bld['discount'][op] / 100;
            discountTotal = price - discountTotal;
            textPrice = price + ' <span style="color: red">(ส่วนลด: ' + bld['discount'][op] + ' %) ราคาจ่ายจริง ' + discountTotal.toFixed(2) + '</span>';
        }
        append_pl_confirm_pl_list = append_pl_confirm_pl_list.replace('{bet-cnt}', (index + 1));
        append_pl_confirm_pl_list = append_pl_confirm_pl_list.replace('{bet-number}', number);
        append_pl_confirm_pl_list = append_pl_confirm_pl_list.replace(/{bet-price}/g, price);
        append_pl_confirm_pl_list = append_pl_confirm_pl_list.replace("฿ 0", textPrice);
        //console.log(append_pl_confirm_pl_list);
        $('#pl_confim_' + op).append(append_pl_confirm_pl_list);

        if (value.multiply == 0) {
            $("#pl_" + index).addClass('multiply-close');
        } else {
            var bld = JSON.parse(bet_list_detail);
            if (value.multiply !== bld[op]) {
                $("#pl_" + index).addClass('multiply-change');
            }
        }

        total_price += price * 1;
        bet_cnt++;
        total_poy_list++;
    });
    $('#pl_success').html($('#pl_confirm').html());
    //window['pl_success'].innerHTML=window['pl_confirm'].innerHTML;

    //$('#pl_confirm').append('<textarea name="note" id="note" rows="2" class="form-control" placeholder="📝บันทึกช่วยจำ"></textarea><hr class="mb-0">');

    $('#total_poy_list').html(total_poy_list + ' รายการ');
    //$('#total_price').html(total_price+' ฿');
    $('.thb.total_price').html(total_price.toFixed(2));

    //left side view
    // const markup = num.map(item => `<li>${item}</li>`).join(' ')
    // $('#show_bet_num').append(markup);


    //$('#pl_confirm').append('<div class="arrow-up"><i class="fal fa-arrow-up"></i></div>');
}



function delete_last_add_num() {
	var poy = JSON.parse(window.localStorage.getItem('poy'));
	if (poy.poy_list.length == 0) return;
	var i = [];
	$.each(poy.poy_list, function (index, value) {
		if (poy.poy_list[index].last_add_num == last_add_num) {
			i.push(index);
		}
	});
	i.sort(function(a, b){return b-a});
	$.each(i, function (index, value) {
		$('.panghuay_number[data-id="'+poy.poy_list[value].number+'"]').removeClass('active');
		poy.poy_list.splice(value, 1);
	});
	last_add_num = last_add_num-1;
	window.localStorage.setItem('poy', JSON.stringify(poy));
	show_bet_num();
}

function delete_the_num(num) {
	var poy = JSON.parse(window.localStorage.getItem('poy'));
	if (poy.poy_list.length == 0) return;
	var i = [];
	var del_num = [];
	if ($('#shuffle_2').hasClass('active')) {
			del_num = $.merge(del_num, shuffle_num(num, 2));
	} else if ($('#shuffle_3').hasClass('active')) {
			del_num = $.merge(del_num, shuffle_num(num, 3));
	} else {
			del_num = $.merge(del_num,[num]);
	}

	$.each(poy.poy_list, function (index, value) {
		if ($.inArray(poy.poy_list[index].number, del_num)>=0 && poy.poy_list[index].option) {
			//console.log(poy.poy_list[index].number+' == '+num+' -> '+index);
			//$('.panghuay_number[data-id="'+num+'"]').removeClass('active');
			i.push(index);
		}
	});

	i.sort(function(a, b){return b-a});
	$.each(i, function (index, value) {
		poy.poy_list.splice(value, 1);
	});

	$.each(del_num, function (index, value) {
		if (value!=num) {
			$('.panghuay_number[data-id="'+value+'"]').removeClass('active');
		}
	});
	window.localStorage.setItem('poy', JSON.stringify(poy));
	show_bet_num();
}

function delete_num_option2(option,num) {
	//console.log('delete_num_option2',option,num);
	var poy = JSON.parse(window.localStorage.getItem('poy'));
	if (poy.poy_list.length == 0) return;
	var i = [];
	var del_num = [];

	if (option === "option_2_19") {
			del_num = $.merge(del_num, gen_19(num));
	} else if (option === "option_2_roodnha") {
			del_num = $.merge(del_num, rood_num(num, 'nha'));
	} else if (option === "option_2_roodlung") {
			del_num = $.merge(del_num, rood_num(num, 'lung'));
	}
	//console.log(del_num);
	bon_lang_2 = ['teng_bon_2','teng_lang_2'];
	$.each(bon_lang_2, function (opti, curr_opt) {
		if (curr_opt!=option) {
			var run_del_num = [];
			run_del_num = $.merge(run_del_num, del_num);
			$.each(poy.poy_list, function (index, value) {
				if ($.inArray(poy.poy_list[index].number, run_del_num)>=0 && poy.poy_list[index].option==curr_opt) {
					//console.log('inarray',poy.poy_list[index].option,$.inArray(poy.poy_list[index].number, run_del_num));
					run_del_num.splice( $.inArray(poy.poy_list[index].number, run_del_num), 1 );
					i.push(index);
				}
			});
		}
	});
	i.sort(function(a, b){return b-a});
	$.each(i, function (index, value) {
		poy.poy_list.splice(value, 1);
	});
	$.each(del_num, function (index, value) {
		$('.panghuay_number[data-id="'+value+'"]').removeClass('active');
	});

	window.localStorage.setItem('poy', JSON.stringify(poy));
	show_bet_num();
}



function delete_poy_list(index, number) {
    var poy = JSON.parse(window.localStorage.getItem('poy'));
    if (poy.poy_list.length == 0) return;
    var changenumber_delete = $('.textdelelete').text();
    var modalConfirm_del_one = function (callback) {
        $("#modal_confirm_del_one").modal('show');
        $('.textdelelete').html(changenumber_delete.replace('{number_delete}', number));

        $("#btnconfirm_del_one").on("click", function () {
            $("#modal_confirm_del_one").modal('hide');
            $('.textdelelete').html('คุณแน่ใจนะว่าต้องการลบเลขนี้ {number_delete} ใช่หรือไม่?');
            callback(true);
        });
        $("#btnconfirm_del_one_close").on("click", function () {
            $('.textdelelete').html('คุณแน่ใจนะว่าต้องการลบเลขนี้ {number_delete} ใช่หรือไม่?');
            callback(false);
        });
    };
    modalConfirm_del_one(function (confirm) {
        if (confirm) {
            poy.poy_list.splice(index, 1);
            window.localStorage.setItem('poy', JSON.stringify(poy));
            show_bet_num();
        } else {
            return false;
        }
    });
}


function change_price(index) {
    if ($('#pl_price_' + index).val() <= 0) {
        $('#pl_price_' + index).val(1);
    }
    var new_price = $('#pl_price_' + index).val();
    //console.log('change_price ' + index + ' => ' + new_price);
    var poy = JSON.parse(window.localStorage.getItem('poy'));
    if (poy.poy_list.length == 0) return;


    var bld = JSON.parse(bet_list_detail);
    var minmax = $.grep(bld['bet_min_max'], function (b) {
        return b.bet_option === poy.poy_list[index].option;
    });
    //console.log(minmax);
    if (new_price < parseInt(minmax[0]['bet_min'])) {
        notify('ราคาต่ำสุดที่ใส่ได้คือ' + ' ' + minmax[0]['bet_min'], "error");
        poy.poy_list[index].price = parseInt(minmax[0]['bet_min']);
    } else if (new_price > parseInt(minmax[0]['bet_max'])) {
        notify('ราคาสูงสุดที่ใส่ได้คือ' + ' ' + minmax[0]['bet_max'], "error");
        poy.poy_list[index].price = parseInt(minmax[0]['bet_max']);
    } else {
        poy.poy_list[index].price = new_price;
    }

    /*
    $('#pld_' + poy.poy_list[index].option + '_' + (index + 1) + ' > .pl_list_price').html(new_price);
    var total_price = 0;
    $.each(poy.poy_list, function (k, value) {
        total_price += value.price * 1;
    });
    $('#total_price').html(total_price);
    $('#pl_' + index + ' > .input-group-append > #win_span > #pl_win').html(poy.poy_list[index].multiply * new_price);
    */
    window.localStorage.setItem('poy', JSON.stringify(poy));
    show_bet_num();
}


var view_dup = false;
$('#view_duplicate').click(function () {
    if (view_dup) {
        $('.number-is-duplicate').removeClass('number-is-duplicate-active');
    } else {
        $('.number-is-duplicate').addClass('number-is-duplicate-active');
    }
    view_dup = !view_dup;
});


var modalConfirm_duplicate = function (callback) {
    $("#delete_duplicate").on("click", function () {
        $("#modal_confirm_delete_duplicate").modal('show');
    });
    $("#btnconfirmdelete_duplicate").on("click", function () {
        callback(true);
        $("#modal_confirm_delete_duplicate").modal('hide');
    });
};

modalConfirm_duplicate(function (confirm) {
    if (confirm) {
        delete_duplicate();
    }
});

var modalConfirm_reset = function (callback) {
    $("#reset_poy").on("click", function () {
        $("#modal_confirm_reset").modal('show');
    });
    $("#btnconfirm_reset").on("click", function () {
        callback(true);
        $("#modal_confirm_reset").modal('hide');
    });
};

modalConfirm_reset(function(confirm){
    if(confirm){
        reset_poy_confirm();
    }
});

function delete_duplicate() {
    var poy = JSON.parse(window.localStorage.getItem('poy'));
    if (poy.poy_list.length == 0) return;
    //if (confirm('Sure to delete duplicate ?') == false) return;

    var poy_list_newdata = [];
    var nodup = [];
    $.each(poy.poy_list, function (index, value) {
        if (value.is_duplicate && $.inArray(value.option + '-' + value.number, nodup) < 0) {
            nodup.push(value.option + '-' + value.number);
            value.is_duplicate = false;
            poy_list_newdata.push(value);
        } else if (!value.is_duplicate) {
            poy_list_newdata.push(value);
        }
    });
    poy.poy_list = poy_list_newdata;
    window.localStorage.setItem('poy', JSON.stringify(poy));
    show_bet_num();
};

function reset_poy_confirm() {
    reset_poy();
    $('#price').removeClass('open');
    $('#content').removeClass('blur');
    //$.fancybox.close($('#popupCart'));
}

$('#set_all_price').on("input", change_all_price);
$('.box__chip-lists .price').click(function () {
    $($(this)).addClass("active");
    setTimeout(function () {
        $('.box__chip-lists .price').removeClass("active");
    }, 400);
    $('#set_all_price').val($(this).data('id'));
    change_all_price($(this).data('id'));
});

function change_all_price(e = null) {
    var poy = JSON.parse(window.localStorage.getItem('poy'));
    if (poy.poy_list.length == 0) return;

    if (isNaN(e)) {
        if ($('#set_all_price').val() <= 0) return;
        var new_price = $('#set_all_price').val();
    } else {
        var new_price = e;
    }
    //var total_price = 0;
    var bld = JSON.parse(bet_list_detail);
    $.each(poy.poy_list, function (index, value) {

        var minmax = $.grep(bld['bet_min_max'], function (b) {
            return b.bet_option === poy.poy_list[index].option;
        });
        //console.log((new_price,'>=',minmax));
        if (new_price >= minmax[0]['bet_min']) {
            poy.poy_list[index].price = new_price;
        } else {
            poy.poy_list[index].price = parseInt(minmax[0]['bet_min']);
        }
        /*
        $('#pl_' + index + ' > #pl_price').val(new_price);
        $('#pl_' + index + ' > .input-group-append > #win_span > #pl_win').html(poy.poy_list[index].multiply * new_price);
        $('#pld_' + value.option + '_' + (index + 1) + ' > .pl_list_price').html(new_price);
        total_price += poy.poy_list[index].price * 1;
        */
    });
    //$('#total_price').html(total_price);
    window.localStorage.setItem('poy', JSON.stringify(poy));
    show_bet_num();
}

$('.btn-cancel.cancel_poy').click(function () {
    //$.fancybox.close($('#popupConfirm'));
});

$('.successbet').click(function () {
    $('.successbet').prop('disabled', true);
    send_poy();

});

function pre_send_poy() {
    //ส่งโพยไปที่
    //show('loading',true);
    var poy = JSON.parse(window.localStorage.getItem('poy'));
    if (poy.poy_list.length == 0) return;
    poy.note = $('#note').val();
    window.localStorage.setItem('poy', JSON.stringify(poy));
    //console.log('send_poy',poy);
    $.ajax({
        url: preSendPoyUrl,
        cache: false,
        type: 'post',
        data: {
            poy: JSON.stringify(poy)
        },
        success: function (data) {
            //console.log(data);
            //show('loading',false);
            // var d = JSON.parse(data);
            var d = data;

            // if (d.result != 'SUCCESS') {
            //     var poy = JSON.parse(window.localStorage.getItem('poy'));
            //     console.log(poy.poy_list);
            //     $.each(d.highlight, function (index, value) {
            //         $.each(poy.poy_list, function (index2, value2) {
            //             if (value2.option == value.option && value2.number == value.number) {
            //                 if (value.multiply == 0) {
            //                     $("#pl_" + index2).addClass('multiply-close');
            //                 } else {
            //                     $("#pl_" + index2).addClass('multiply-change');
            //                 }
            //                 $("#pl_" + index2 + "_multiply").html(parseInt(value.multiply));
            //                 $("#pl_" + index2 + "_win").val(parseFloat($('#pl_price_' + index2).val() * value.multiply));
            //
            //                 if (!isNaN(value.multiply)) {
            //                     if (value.multiply >= 0) {
            //                         poy.poy_list[index2].multiply = value.multiply;
            //                     }
            //                 }
            //             }
            //
            //         });
            //
            //     });
            //     window.localStorage.setItem('poy', JSON.stringify(poy));
            //
            // }
            if (d.result == 'ERROR') {
                notify(d.message, "error");
            } else if (d.result == 'MINMAX') {
                notify('ขั้นต่ำในการแทงไม่ตรงตามเงื่อนไขที่กำหนด', "error");
            } else if (d.result == 'MAXBETUSER') {
                notify('ยอดแทงเกินกว่ายอดจำกัดจำนวนการแทงต่อสมาชิก', "error");
            } else if (d.result == 'LIMIT_NUMBER'){
                notify(d.message, "error");
                var poy = JSON.parse(window.localStorage.getItem('poy'));
                $.each(d.multiply_change, function (i, v) {
                    $.each(poy.poy_list, function (index, value) {
                        if (v.option == value.option && v.number == value.number) {
                            $("#pl_" + index).addClass('multiply-change');
                        }
                    });
                });
            } else if (d.result == 'CHANGE') {
                //alert('WARNING ... BET MULTIPLY HAS BEEN CHANGED !!');
                notify('เรทการจ่ายมีการเปลี่ยนแปลง โปรดยืนยันอีกครั้ง', "error");

                /// ต้องทำให้เปลี่ยน / reload bet_list_detail + edit poy_list
                var poy = JSON.parse(window.localStorage.getItem('poy'));
                $.each(d.multiply_change, function (i, v) {
                    $.each(poy.poy_list, function (index, value) {
                        if (typeof(v) != "undefined" && typeof(value) != "undefined") {
                            if (v.option == value.option && v.number == value.number) {
                                if (v.multiply > 0) {
                                    poy.poy_list[index].multiply = v.multiply;
                                } else {
                                    poy.poy_list.splice(index, 1);
                                }
                            }
                        }
                    });
                });
                window.localStorage.setItem('poy', JSON.stringify(poy));
                show_bet_num();
            } else if (d.result == 'NOT_METHOD_ALLOW') {
                notify('not allow', 'error');
            } else if (d.result == 'TIME_OUT') {
                notify('หมดเวลาแทง', 'error');
            } else if (d.result == 'BALANCE_NOT_ENOUGH'){
                notify('ยอดเงินของคุณไม่เพียงพอ', 'error');
            } else if (d.result == 'CLOSE_NUMBER'){
                var numbers = '';
                $.each(d.multiply_change, function (i, v) {
                    if (v.is_close === true) {
                        numbers += v.number + ' ';
                        $("#pl_" + i).addClass('multiply-close');
                    }
                });
                notify('เลข '+numbers+' นี้ปิดรับแทง กรุณาลบรายการแล้ว กดส่งโพยอีกครั้ง', 'error');
            } else if (!testTab()) {
                notify('กรุณาใช้งานเพียง tap เดียวเท่านั้น', 'error');
            } else if (d.result == 'SUCCESS') {
                var poy = JSON.parse(window.localStorage.getItem('poy'));
                if (poy.poy_list.length == 0) return;
                $('#sendpoy').toggleClass('open');
                if ($('#sendpoy').hasClass('open')) {
                    $('#footer-member').addClass('d-none');
                }
                var fixbot111 = $('#sendpoy .fixbot').height();
                var wh = $(window).height();
                var fixbott111 = wh - fixbot111 - 50;
                $('#sendpoy .content-scroll').css('height', fixbott111);

            } else if (d.result == 'ERROR') {
                notify(d.msg, "error");
            }
            $('.triggerSendpoy').prop('disabled', false);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

}

function send_poy() {
    //ส่งโพยไปที่
    //show('loading',true);
    var poy = JSON.parse(window.localStorage.getItem('poy'));
    // if (poy.poy_list.length == 0) return;
    poy.note = $('#note').val();
    window.localStorage.setItem('poy', JSON.stringify(poy));
    //console.log('send_poy',poy);
    $.ajax({
        url: buyPoyUrl,
        cache: false,
        type: 'post',
        data: {
            poy: JSON.stringify(poy),
            _csrf: yii.getCsrfToken()
        },
        success: function (data) {
            //show('loading',false);
            // var d = JSON.parse(data);
            var d = data;
            hashcsrf = d.token;
            if (d.result == 'ERROR') {
                notify(d.message, "error");
            } else if (d.result == 'MINMAX') {
                //alert('ERROR ... MIN-MAX BET');
                notify('ขั้นต่ำในการแทงไม่ตรงตามเงื่อนไขที่กำหนด', "error");
            } else if (d.result == 'CHANGE') {
                //alert('WARNING ... BET MULTIPLY HAS BEEN CHANGED !!');
                notify('เรทการจ่ายมีการเปลี่ยนแปลง โปรดยืนยันอีกครั้ง', "error");
                console.log(poy.poy_list);
                /// ต้องทำให้เปลี่ยน / reload bet_list_detail + edit poy_list
                $.each(d.multiply_change, function (i, v) {
                    $.each(poy.poy_list, function (index, value) {
                        if (v.option == value.option && v.number == value.number) {
                            if (v.multiply > 0) {
                                poy.poy_list[index].multiply = v.multiply;
                            } else {
                                poy.poy_list.splice(index, 1);
                            }
                        }
                    });
                });
                window.localStorage.setItem('poy', JSON.stringify(poy));
                show_bet_num();
            } else if (d.result == 'NOT_METHOD_ALLOW') {
                notify('not allow', 'error');
            } else if (d.result == 'TIME_OUT') {
                notify('หมดเวลาแทง', 'error');
            } else if (d.result == 'BALANCE_NOT_ENOUGH'){
                notify('ยอดเงินของคุณไม่เพียงพอ', 'error');
            }  else if (d.result == 'CLOSE_NUMBER'){
                var numbers = '';
                $.each(d.multiply_change, function (i, v) {
                    if (v.is_close === true) {
                        numbers += v.number + ' ';
                        $("#pl_" + i).addClass('multiply-close');
                    }
                });
                notify('เลข '+numbers+' นี้ปิดรับแทง', 'error');
            } else if (d.result == 'SUCCESS') {
                /*$.fancybox.open($('#popupSuccess'), {
                    touch: false,
                    afterClose: function () {
                        parent.location.reload(true);
                    }
                });*/
                $('#printpoy').toggleClass('open');
                if ($('#printpoy').hasClass('open')) {
                    $('#footer-member').addClass('d-none');
                }
                var fixbot1111 = $('#printpoy .fixbot').height();
                var wh = $(window).height();
                var fixbott1111 = wh - fixbot1111 - 50;
                $('#printpoy .content-scroll').css('height', fixbott1111);

                notify('ส่งโพยเรียบร้อยแล้ว', "success");
                // window.location.replace(printPoyUrl+'?id='+d.thaiSharedGameChitId);
                $('#pl_success').remove("#note");
                $('#poy_id').html(d.poy_id);
                $('#date').html(d.date);
                $('#time').html(d.time);
                $('#price').removeClass('open');
                $('#sendpoy').removeClass('open');
                $('#printpoy').addClass('open');
                // $('.poynote').html(poy.note);
                reset_poy();
                //$('#content').removeClass('blur');
            } else if (d.result == 'ERROR') {
                notify(d.msg, "error");
            }
            $('.successbet').prop('disabled', false);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

}

$('.cart-item-lists').click(function () {
    var poy = JSON.parse(window.localStorage.getItem('poy'));
    if (poy.poy_list.length == 0) return;
    // $.fancybox.open($('#popupCart'), {
    //     touch: false
    // });
});

$('.box__button-number-sets').click(function () {
    // $.fancybox.open($('#popupNumberSet'), {
    //     touch: false
    // });
});

$(".btn-numpage").click(function () {
    if ($(this).data('disable') == 1) {
        return;
    }
    if ($(this).data('id') == "numpage_1") {
        $('[data-id=numpage_1]').addClass("d-none");
        $('[data-id=numpage_2]').removeClass("d-none");
        $('#numpage_1').removeClass("d-none");
        $('#numpage_2').addClass("d-none");
        $('#show_poy_list').addClass("d-flex");
        $(".yeekee__number").hide();
        $(".yeekee__lists-number").hide();
        $(".box__play").show();
        $(".cart-item-lists").show();

    } else if ($(this).data('id') == "numpage_2") {
        $('[data-id=numpage_2]').addClass("d-none");
        $('[data-id=numpage_1]').removeClass("d-none");
        $('#numpage_2').removeClass("d-none");
        $('#numpage_1').addClass("d-none");
        $('#show_poy_list').removeClass("d-flex");
        $(".box__play").hide();
        $(".yeekee__number").show();
        $(".yeekee__lists-number").show();
        $(".cart-item-lists").hide();
        LoadYingResult();
    }
});

function yingying(url) {
    $('#yingying').attr('href', url);
}

async function removecc(url) {
    $('[data-id=numpage_1]').addClass("d-none");
    $('[data-id=numpage_2]').removeClass("d-none");
    $('#numpage_1').removeClass("d-none");
    $('#numpage_2').addClass("d-none");
    $('#show_poy_list').addClass("d-flex");
    $(".yeekee__number").hide();
    $(".yeekee__lists-number").hide();
    $(".box__play").show();
    $(".cart-item-lists").show();
    await sleep(100);
    $('#yingying').attr('href', url);
}

$(".navigation-tab li").click(function () {
    if ($(this).data('id') == "numsets_1") {
        $('[data-id=numsets_1]').addClass("active");
        $('[data-id=numsets_2]').removeClass("active");
        $("#numfavorite").show();
        $("#mynumpoy").hide();
    } else if ($(this).data('id') == "numsets_2") {
        $('[data-id=numsets_1]').removeClass("active");
        $('[data-id=numsets_2]').addClass("active");
        $("#numfavorite").hide();
        $("#mynumpoy").show();
    }
});

function countDown(i = 10, str = null, cla = null) {
    var int = setInterval(function () {
        document.getElementsByClassName(cla)[0].innerText = i;
        if (i < 1) {
            document.getElementsByClassName(cla)[0].innerText = str;
            clearInterval(int);
        }
        i--;
    }, 1000);
}

var values_y = '',
    i_y = 0;
$('.key-pad.box__keyboard.yeekee__number button').click(function () {
    var bld = JSON.parse(bet_list_detail);
    if ((this).className == "btn btn-success btn-block submit-number yeekee__submit") {
        var value_num = $('.show-input-number').val();
        if (value_num.length == 5) {
            $.ajax({
                url: yeekeeNumber,
                cache: false,
                type: 'post',
                data: {
                    ynum: {
                        'id': bld.bet_id,
                        'ying': value_num
                    }
                },
                success: function (res) {
                    if (res.data == "SUCCESS") {
                        console.log('33444');
                        notify('เพิ่ม เลขเรียบร้อยแล้ว', "success");
                        var strbt = document.getElementsByClassName('yeekee__submit')[0].innerText;
                        LoadYingResult();
                        $('.yeekee__submit').prop('disabled', true);
                        countDown(9, strbt, 'yeekee__submit');
                        sleep(10 * 1000);
                        $('.yeekee__submit').prop('disabled', false);

                    } else if (res.data == "ERROR") {
                        notify('กรุณาลองใหม่อีกครั้ง', "error");
                    }else if (res.data == "TIME_AWAIT") {
                        notify('กรุณารอให้ครบเวลา', "error");
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
            $('.show-input-number').val('');
            values_y = '', i_y = 0;
        } else {
            notify('กรุณากรอกเลขให้ครบ 5 หลัก', "error");
        }
        return;
    }
    if ((this).className == "btn btn-danger btn-block delete__number") {
        $('.show-input-number').val(
            function (index, value) {
                i_y--;
                values_y = value.substr(0, value.length - 1);
                return value.substr(0, value.length - 1);
            })
        return;
    }
    if ((this).className == "btn btn-secondary btn-block random__number") {
        i_y = 5;
        values_y = Math.floor(9e4 * Math.random()) + 1e4;
        $('.show-input-number').val(values_y);
        return;
    }
    if (i_y < 5) {
        i_y++;
        values_y += (this).innerText;
        $('.show-input-number').val(values_y);
        return;
    }
    notify('กรุณากรอกเลขให้ครบ 5 หลัก', "error");
});

function LoadYingResult() {
    //console.log('LoadYingResult');
    var bld = JSON.parse(bet_list_detail);
    if (typeof yeekeeLoadNumberPostUrl === "undefined") {
        return ;
    }
    $.ajax({
        url: yeekeeLoadNumberPostUrl,
        cache: false,
        type: 'post',
        data: {
            id: bld.bet_id,
        },
        type: 'post',
        success: function (res) {
            var lang_rank = res.lang_rank,
                lang_dltime = res.lang_dltime,
                lang_memsend = res.lang_memsend;
            $("#total_ying").html(res.total_ying);
            $("#yresult").html('');
            $.each(res.ying, function (index, v) {
                if (index == 0 || index == 15) {
                    var list_ying = $('#list_ying-1').prop('outerHTML');//window['list_ying-1'].outerHTML;
                    list_ying = list_ying.replace('list_ying-1', 'list_ying_' + (index));
                } else {
                    var list_ying = $('#list_ying-0').prop('outerHTML');//window['list_ying-0'].outerHTML;
                    list_ying = list_ying.replace('list_ying-0', 'list_ying_' + (index));
                }

                $('#yresult').append(list_ying);
                $('#list_ying_' + index).addClass('d-flex');
                $('#list_ying_' + index + ' [data-id=ly_ranked]').html(index + 1);
                $('#list_ying_' + index + ' [data-id=ly_date]').html(v['dt']);
                $('#list_ying_' + index + ' [data-id=ly_ying]').html(v['ying']);
                $('#list_ying_' + index + ' [data-id=ly_send]').html(v['username']);
            });
        }
    });
}

function get_my_poy() {
    $.ajax({
        url: myPoyMemoUrl,
        cache: false,
        type: 'get',
        success: function (data) {
            // var my_poy = JSON.parse(data);
            var my_poy = data;
            //console.log(my_poy);
            var model_pull_poy = $('#model_pull_poy').prop('outerHTML');//window['model_pull_poy'].outerHTML;
            $.each(my_poy.poy, function (index, v) {
                var mdp = model_pull_poy;
                mdp = mdp.replace('d-none', '');
                mdp = mdp.replace('{poy_name}', v.bet_name);
                var pl = '';
                $.each(v.poy_list, function (i2, v2) {
                    pl += v2.number + ', '
                });
                mdp = mdp.replace('{poy_detail}', pl);
                mdp = mdp.replace('{poy_dt}', v.dt);
                mdp = mdp.replace('{poy_id}', v.poy_id);
                $("#mynumpoy tbody").append(mdp);
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}


function pull_to_poy(pull_type, id) {
    if (pull_type == 'poy') {
        var url = myPoyDetailMemoUrl + '?id=' + id;
        var data = {
            poy_id: id
        };
    } else if (pull_type == 'set_number') {
        var url = numberMemoDetailUrl + '?id=' + id;
    }
    $.ajax({
        url: url,
        type: 'get',
        success: function (data) {
            var pl = data;
            // var pl = JSON.parse(data);
            var add_poy_list = [];
            var bld = JSON.parse(bet_list_detail);
            // var bld = bet_list_detail;
            var pl_list = [];
            if (pull_type == 'poy') {
                pl_list = pl.poy_list;
            } else {
                // pl_list = JSON.parse(pl.mysetnumber.set_number);
                pl_list = pl.mysetnumber.set_number;
            }

            $.each(pl_list, function (index, v) {
                curr_option = [];
                var op = v.bet_option;
                if (parseInt(bld[op]) > 0) {
                    var minmax = $.grep(bld['bet_min_max'], function (b) {
                        return b.bet_option === op;
                    });
                    //console.log('minmax',minmax[0],minmax[0].bet_min,minmax[0]['bet_min']);
                    v.price = parseInt(minmax[0].bet_min);
                    v.multiply = bld[op];

                    var is_duplicate = false;
                    add_poy_list.push({
                        'option': op,
                        'number': v.number,
                        'price': v.price,
                        'multiply': v.multiply,
                        'is_duplicate': is_duplicate,
                    });
                    $('.panghuay_number[data-id="'+v.number+'"]').addClass('active');
                    $('.panghuay_number[data-id="'+v.number+'"]').addClass('active');
                }
            });
            set_poy_list(add_poy_list);
            show_bet_num();
            $('#poy').removeClass('open');
            $('#content').removeClass('blur');
            //$.fancybox.close($('#popupNumberSet'));
            $('#teng_bon_3').click();
            $('#teng_bon_3').click();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}


var student_number = 1;
$(".group-btn-add").click(function () {
    ++student_number;
    $('#structuresetcreate').clone().attr('data-id', 'student-' + (student_number)).appendTo(".box__number-set-lists").find("input").val("").attr('minlength', '2').attr('maxLength', '2').attr('placeholder', $('#numbersetinput').data('default')).end();
});

$(document).on('change', '#structuresetcreate', function () {
    var datas = $('[data-id=' + $(this).data('id') + '] #numbersetinput');
    var value = $('option:selected', '[data-id=' + $(this).data('id') + ']').attr('value');
    var mlength = value.split("_");
    if (mlength.length == 4) {
        mlength = mlength[3];
    } else if (mlength.length == 2) {
        mlength = mlength[1];
    } else {
        mlength = mlength[2];
    }
    datas.attr('minlength', mlength).attr('maxlength', mlength);
    datas.attr('placeholder', datas.attr('placeholder').replace(/\d/g, mlength));
})

function get_my_set_number() {
    $.ajax({
        url: numberMemoUrl,
        type: 'get',
        success: function (data) {
            var my_set_number = data;
            // var my_set_number = JSON.parse(data);
            // console.log(my_set_number);
            var model_set_number = $('#model_set_number').prop('outerHTML');//window['model_set_number'].outerHTML;
            $.each(my_set_number.set_number, function (index, v) {
                var mdp = model_set_number;
                mdp = mdp.replace('d-none', '');
                mdp = mdp.replace('{set_name}', v.set_name);
                mdp = mdp.replace('{id}', v.id);
                mdp = mdp.replace('{dt}', v.dt);
                var set_number = v.set_number;
                var pl = '';
                $.each(set_number, function (i2, v2) {
                    pl += v2.number + ', ';
                });
                mdp = mdp.replace('{set_number}', pl);
                $("#numfavorite tbody").append(mdp);
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

}

