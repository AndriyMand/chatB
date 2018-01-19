//var baseURL = "https://app.auspex.com.ua/script-test/Study(menu)/interface/action.php";
//var operatorID = 99;

$(document).ready(function () {
	

	/*
	//$("#phone").mask("(99) 9999?9-9999");
	$("#phone").on("blur", function() {
		var last = $(this).val().substr( $(this).val().indexOf("-") + 1 );
		
		if( last.length == 3 ) {
			var move = $(this).val().substr( $(this).val().indexOf("-") - 1, 1 );
			var lastfour = move + last;
			
			var first = $(this).val().substr( 0, 9 );
			
			$(this).val( first + '-' + lastfour );
		}
	});
	*/
	
	$('.addTags_group').hide();
	

	$(function(){
		$('p').Emoji();
	});
	
	$(function(){
		$('p').Emoji({
			path:  'img/emojione/',
			class: 'emoji',
			ext:   'png'
		});
	});
	
	$(document).on('click', '.li_menu', function (e) {
        e.preventDefault();
        //alert($(this).closest('.tr_tags').find('.i_tags').val());
		
		var section = $(this).data("section");
		var action  = $(this).data("action");
		
        $.ajax({
            type: 'post',
            url: window.location.toString(),
            data: {
                menu:1,
                section:section,
                action:action
            },
            success : function(result, textStatus) {
                if (textStatus === "success") {
					$( ".all_actions" ).empty();
					$(".all_actions").html(result);
                }
            }
        });
    });
	
	$(document).on('click', '.li_detail', function (e) {
        e.preventDefault();
		window.location.href = $(this).attr('href');
		document.getElementById('abc').style.display = "none";
    });
	
	$(document).on('click', '#create_block', function (e) {
		//window.location.href = $(this).attr('href');
        document.getElementById('abc').style.display = "block";
        e.preventDefault();
		$('html,body').animate({
            scrollTop: 0
        }, 400);
        $('#popup_form').find("input[type=text], textarea").val("");
		$('#popup_form').find(".emoji-wysiwyg-editor").text("");
        $("#action_type").val("create");
        $(".additionalFields").empty();
    });
	
	$(document).on('click', '.btnType', function (e) {
        e.preventDefault();
		
		$('.btnType').removeClass('colored');
		$(this).toggleClass('colored');
		
		var type   = $(this).data("type");
		var btn_id = '';
		var fields = '';

		if (typeof $(this).data("id") !== "undefined") {
			btn_id = $(this).data("id");
		}

		//var block_id = $('.block_set p').map(function(){
		var block_id = $('.block_info').map(function(){
		   return this.id;
		}).get();
		
		//var block_name = $('.block_set p').map(function(){
		var block_name = $('.block_info').map(function(){
		   return $(this).val();
		}).get();
		
		//console.log(block_name);
		
		$(this).closest('td').find( ".i_btn_link" ).remove();
		$(this).closest('td').find( ".i_btn_block" ).remove();
		$(this).closest('td').find( ".i_btn_call" ).remove();
		$(this).closest('td').find( ".i_btn_block_val" ).remove();

		
		switch (type) {
		  case 0:
			fields = "\
					<input type='hidden' class='i_btn_block_val' name='block[button][btn_block]["+btn_id+"]' value='0'>\n\
					<input type='text' class='i_btn_link' name='block[button][special_val]["+btn_id+"]' placeholder='Input link*'>\n\
			";
			break;
		  case 1:
			fields = "<div class='i_btn_block'>";

			var arrayLength = block_id.length;
			
			fields += "<select class='i_btn_block_val' name='block[button][special_val]["+btn_id+"]'>";
			for (var i = 0; i < arrayLength; i++) {
				fields += "\
						<option value='" + block_id[i] + "'> " + block_name[i] + "</option>\n\
				";
			}
			
			fields += "</select></div>";
		  
			
			break;
		  case 2:
			fields = "\
					<div class='i_btn_call'>\n\
						<input type='text' id='phone' class='i_btn_phone_number' name='block[button][special_val]["+btn_id+"]' placeholder='(99) 9999-9999'>\n\
					</div>\n\
			";
			break;
		}
		$(this).closest('td').find('.btn_type').val(type);
		
		$(this).closest('td').append(fields);
	});
	
	$(document).on('click', '.add', function (e) {
        e.preventDefault();
		var type = $(this).data("type");
		
		var fields = "\
			<table border='1' class='addTable'>\n\
				<tr>\n\
					<td colspan='2'>\n\
						<p style='text-align:center'>" + type + "</p>\n\
					</td>\n\
				<tr>\n\
                <tr>\n\
                    <td>\n\
                        <span><input class='deleteItem' width='25' type='image' src='../../fileadmin/images/close.ico' /></span>\n\
                    </td>\n\
                    <td>\n\
					";
		
		switch (type) {
		  case 'Button':
			fields += "\
					<div class='addDiv'><button type='button' class='btnType colored' data-type='0'>Link</button></div>\n\
					<div class='addDiv'><button type='button' class='btnType' data-type='1'>Block</button></div>\n\
					<div class='addDiv'><button type='button' class='btnType' data-type='2'>Call</button></div>\n\
					<input type='hidden' class='btn_type' name='block[button][type][]' value='0'>\n\
					<input type='hidden' class='i_btn_block_val' name='block[button][btn_block][]' value='0'>\n\
					<input type='text' class='i_btn_name' name='block[button][name][]' placeholder='Input name*'>\n\
					<input type='text' class='i_btn_color' name='block[button][bg_color][]' placeholder='Input color'>\n\
					<input type='text' class='i_btn_text_color' name='block[button][text_color][]' placeholder='Input text color (css)'>\n\
					<input type='text' class='i_btn_link' name='block[button][special_val][]' placeholder='Input link*'>\n\
			";
			break;
		  case 'Link':
			fields += "\
					<input type='text' name='block[link][name][]' placeholder='Input title' required>\n\
					<input type='text' name='block[link][host][]' placeholder='Input link' required>\n\
			";
			break;
		  case 'Image':
			fields += "\
					<input type='text' name='file[host][]' placeholder='Input link'>\n\
					<input type='file' name='file[]' accept='image/*'>\n\
			";
			break;
		  case 'Text':
			fields += "<input type='text' name='block[text][name][]' placeholder='Input text' required>";
			break;
		}		

		fields += "\
                    </td>\n\
                <tr>\n\
            </table>\n\
		";
		
		$(".additionalFields").append(fields);
    });
	
    $(document).on('click', '.addTagsBtn', function (e) {
        e.preventDefault();
        //alert($(this).closest('.tr_tags').find('.i_tags').val());
		$('.addTags_group').hide();
        console.log($(this).closest('.tr_tags').find('.i_tags'));
        $.ajax({
            type: 'post',
            url: window.location.toString(),
            data: {
                addTags:1,
                tagsId:$(this).val(),
                tagsVal:$(this).closest('.tr_tags').find('.i_tags').val()
            },
            success : function(result, textStatus) {
                if (textStatus === "success") {
                       $("body")[0].outerHTML = result;
                       if ($('#addTagsStatus').val() > 0) {
                            //document.getElementById('tags_set_table').style.display = "block";
                       } else {
                           alert('Tags insertion failed!');
                       }
                }
            }
        });
    });
	
	$(document).on('click', '.saveKeyDescr', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: window.location.toString(),
            data: {
                changeDescr:1,
                keyId:$(this).val(),
                descrVal:$(this).closest('div').find('#i_descr').val()
            },
            success : function(result, textStatus) {
                if (textStatus === "success") {
                       //$("body")[0].outerHTML = result;
					   location.reload();
                }
            }
        });
    });
	
	$(document).on('click', '.addKeysBtn', function (e) {
        e.preventDefault();
        console.log($(this).closest('.keyboard_add').find('.i_tags').val());
        $.ajax({
            type: 'post',
            url: window.location.toString(),
            data: {
                addKeys:1,
                keysVal:$(this).closest('.keyboard_add').find('.i_tags').val()
            },
            success : function(result, textStatus) {
                if (textStatus === "success") {
                       $("body")[0].outerHTML = result;
                }
            }
        });
    });
    
    $(document).on('click', '#create_tag_set_btn', function (e) {
        e.preventDefault();
        //alert($(this).closest('.tr_tags').find('.addTagsBtn').val());
        var tagSet = $(this).closest('#tags_set_table').find('.tag_set').val();
		if (typeof tagSet !== "undefined" && tagSet.length > 0) {
			$.ajax({
				type: 'post',
				url: window.location.toString(),
				data: {
					setTagSet:1,
					tagSet:tagSet
				},
				success : function(result, textStatus) {
					if (textStatus === "success") {
						   $("body")[0].outerHTML = result;
					}
				}
			});
		} else {
			alert('Please input value');
		}
    });
	
	$(document).on('click', '#create_key_btn', function (e) {
        e.preventDefault();
        //alert($(this).closest('.tr_tags').find('.addTagsBtn').val());
        var keySet = $(this).closest('.tr_tags').find('.key_input').val();
		if (typeof keySet !== "undefined" && keySet.length > 0) {
			$.ajax({
				type: 'post',
				url: window.location.toString(),
				data: {
					setKeySet:1,
					keySet:keySet
				},
				success : function(result, textStatus) {
					if (textStatus === "success") {
						   $("body")[0].outerHTML = result;
					}
				}
			});
		} else {
			alert('Please input value');
		}
    });

    
    $(document).on('click', '.tags_row p', function (e) {
        e.preventDefault();
        //alert($(this).closest('.tr_tags').find('.addTagsBtn').val());
        var tagVal = $(this).text();
		$(this).hide();
        $.ajax({
            type: 'post',
            url: window.location.toString(),
            data: {
                deleteTag:1,
                tagsId:$(this).closest('.tr_tags').find('.addTagsBtn').val(),
                tagVal:tagVal
            },
            success : function(result, textStatus) {
                if (textStatus === "success") {
                       if ($('#deleteTagStatus').val() === 0) {
                           alert('Tags delete failed!');
                       }
                }
            }
        });
    });
	
	$(document).on('click', '#deleteBtn', function (e) {
        e.preventDefault();
        //var tagSetId = $(this).data("id");
        $.ajax({
            type: 'post',
            url: window.location.toString(),
            data: {
                deleteBtn:1,
                itemId:$(this).data("id"),
				table:$(this).data("table")
            },
            success : function(result, textStatus) {
                if (textStatus === "success") {
                       $("body")[0].outerHTML = result;
                }
            }
        });
    });

	$(document).on('click', '#key_checkboxes label', function (e) {
        e.preventDefault();
		$(this).find('input').prop('checked', true);
		$(this).closest('.for_padding').find('input:checkbox[name=blockCheck]').prop("checked", false);
		$(this).closest('.for_padding').find('.checkBlock[data-key="' + $(this).find('.radio').val() + '"]').attr('checked', true);
    });

    $(document).on('click', '.blocks_hot p', function (e) {
        e.preventDefault();
		$(this).hide();
        var blockDeleteVal = $(this).text();
        $.ajax({
            type: 'post',
            url: window.location.toString(),
            data: {
                blockDelete:1,
                blockDeleteId:$(this).attr('class'),
                blockDeleteVal:blockDeleteVal,
                tagSetId:$(this).closest('.tr_tags').find('.tags_id p').text()
            },
            success : function(result, textStatus) {
                if (textStatus === "success") {
                       $("body")[0].outerHTML = result;
                }
            }
        });
    });
    
    $(document).on('click', '.block_set p', function (e) {
        e.preventDefault();
		
        var checked_tag_sets = new Array();
        $('.tags_col:checkbox:checked').each(function(){
            checked_tag_sets.push($(this).val());
        });
		
		//var checked_keys = new Array();
        //$('.keys_col:checkbox:checked').each(function(){
        //    checked_keys.push($(this).val());
        //});
		
        var blockVal = $(this).text();
        if (checked_tag_sets.length > 0 || checked_keys.length > 0) {
            $.ajax({
                type: 'post',
                url: window.location.toString(),
                data: {
                    setBlockToTags:1,
                    blockIdblockId:$(this).attr('id'),
                    checked_tag_sets:checked_tag_sets,
					//checked_keys:checked_keys,
                    blockVal:blockVal,
                    blockId:$(this).attr('id')
                },
                success : function(result, textStatus) {
                    if (textStatus === "success") {
                           $("body")[0].outerHTML = result;
                    }
                }
            });
        } else {
            alert('Please select any box set');
        }
    });
    
	
	/*
	$("input:checkbox").on('click', function() {
		var $box = $(this);
		if ($box.is(":checked")) {
			var group = "input:checkbox[name='" + $box.attr("name") + "']";
			$(group).prop("checked", false);
			$box.prop("checked", true);
		} else {
			$box.prop("checked", false);
		}
	});
	*/
	
	$(document).on('click', '.show_tags_group', function (e) {
        e.preventDefault();
		$(this).hide();
        $(this).closest('td').find('.addTags_group').show();
    });

    $(document).on('click', '.editBlock', function (e) {
        e.preventDefault();
        //alert($(this).closest('.edit').find("#blockId").val());
		//console.log($(this).closest('.edit').find(".blockId").val());
		//console.log($(this).closest('.edit').find(".blockId").attr('data-buttons'));
		//console.log($(this).closest('.edit').find(".blockId").data('attach'));
		$('html,body').animate({
            scrollTop: 0
        }, 400);
        $(".formAdditional").empty();
        $.ajax({
            type: 'post',
            url: window.location.toString(),
            data: {
                edit:1,
                blockId:$(this).closest('.edit').find(".blockId").val()
            },
            success : function(result, textStatus) {
                if (textStatus === "success") {
					console.log(result);
                    $('#abc').find(".formAdditional").append($(result).find(".additionalFields"));
                    $('#popup_form').append($(result).find("#hidden_blockId"));
                    $('#i_hotKeys').val($(result).find("#hidden_hotKeys").val());

                    $('#i_text').val($(result).find("#hidden_text").val());
                    $('#i_header').val($(result).find("#hidden_header").val());

                    $('p.i_text').find(".emoji-wysiwyg-editor").text($(result).find("#hidden_text").val());
                    $('p.i_header').find(".emoji-wysiwyg-editor").text($(result).find("#hidden_header").val());
                }
            }
        });
        $("#action_type").val("edit");
        document.getElementById('abc').style.display = "block";
    });
    
    $(document).on('click', '.deleteBlock', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: window.location.toString(),
            data: {
                delete:1,
                blockId:$(this).closest('.edit').find(".blockId").val()
            },
            success : function(result, textStatus) {
                if (textStatus === "success") {
                       $("body")[0].outerHTML = result;
                       if ($('#deleteStatus').val() > 0) {
                            document.getElementById('blocks_table').style.display = "block";
                            document.getElementById('blocks_headers').style.display = "none";
                            $("#show_blocks").html('Show blocks list');
                       } else {
                           alert('Delete failed!');
                       }
                }
            }
        });
    });
    

    $(document).on('submit', '.button1', function (e) {
        e.preventDefault();
    });
    
    $(document).on('click', '.serialize', function (e) {
        e.preventDefault();
        $("#serialized").val($("form").serialize());
        $("#form").submit();
    });

    $(document).on('click', '.deleteItem', function (e) {
        e.preventDefault();
        $(this).closest('table').remove();
    });
	
	$(document).mouseup(function(e) 
	{
		var container = $("#abc");

		// if the target of the click isn't the container nor a descendant of the container
		if (!container.is(e.target) && container.has(e.target).length === 0) 
		{
			container.hide();
		}
	});

    $(document).on('click', 'img.close', function (e) {
        e.preventDefault();
        document.getElementById('abc').style.display = "none";
        $('#popup_form').find("input[type=text], textarea").val("");
		$('#popup_form').find(".emoji-wysiwyg-editor").text("");
        $(".additionalFields").empty();
    });
    
    $(document).on('click', '.clear', function (e) {
        e.preventDefault();
        $('#popup_form').find("input[type=text], textarea").val("");
        $('#popup_form').find(".emoji-wysiwyg-editor").text("");
        $(".additionalFields").empty();
    });

    $(document).on('click', '#show_blocks', function (e) {
        e.preventDefault();
       
        if ($(this).text() === 'Show blocks detail') {
            document.getElementById('blocks_headers').style.display = "none";
            document.getElementById('blocks_table').style.display = "block";
            $("#show_blocks").html('Show blocks list');
        } else {
            document.getElementById('blocks_headers').style.display = "block";
            document.getElementById('blocks_table').style.display = "none";
            $("#show_blocks").html('Show blocks detail');
        }
        
    });
	
	$(document).on('click', '#show_keyboard', function (e) {
        e.preventDefault();
        $('#keywords').show();
        
    });


    $(document).on('click', '#save_block', function (e) {
        e.preventDefault();

        if ($('#i_header').val().length > 0 && $('#i_text').val().length > 0) {
            $('#popup_form').submit();
        } else {
            alert('input all values!');
		}
    });
	
	$(document).on('click', '#save_keyboard', function (e) {
        e.preventDefault();
        if (jQuery('#keyboard_form input[type=checkbox]:checked').length > 0) {
			$('#keyboard_form').submit();
		} else {
			alert('You need to select any keyboard');
		}
    });

});

function notEmpty(elem) {
	if (elem.val().length < 1) {
		$(elem).addClass("error");
		return false;
	} else {
		$(this).removeClass("error");
		return true;
	}
}

function showValues() {
    return $("form").serialize();
}

function div_show() {
    document.getElementById('abc').style.display = "block";
}

function close() {
    document.getElementById('abc').style.display = "none";
    $('#popup_form').find("input[type=text], textarea").val("");
    $(".additionalFields").empty();
}