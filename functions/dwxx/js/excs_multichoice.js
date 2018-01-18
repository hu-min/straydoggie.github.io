function content_fill_multichoice() {
    try {
        number = content.number;
        text = content.text;
        option = content.option;
        answer = content.answer;
        ansarr = new Array();
    } catch (e) {
        alert(e.message+"\r\n"+current_data.identifier);
        //alert(current_data.identifier);
        //alert(current_data.content);
        //alert(raw_data);
        //popup_echo(raw_data);
        //alert(e.message);
        //alert(e.description)
        //alert(e.number)
        //alert(e.name)
    }
    content_fill_prepare();
    for (var i = 0; i < option.length; i++) {
        $("#exec_content_multichoice_option").append($("#option_eg_multichoice").clone().attr('id', 'option_' +
            i));
        $("#option_" + i).html('<p class="text_inner">' + optionletter[i] + '. ' + option[i] + '</p>');
        $("#option_" + i).attr('onclick', 'chose_multichoice("' + optionletter[i] + '");');
        $("#option_" + i).show();
    }
    $("#exec_content_multichoice_text").html('<p class="text_inner">' + number + '. ' + text + '</p>');
    $("#exec_title").html('多项选择题 ' + (pointer + 1) + '/' + pointer_array.length);
    $("#exec_content_multichoice").show();

}

function check_multichoice() {
    var valid = true;
    $.each(ansarr, function (index, value) {
        if (answer.indexOf(value) == -1) {
            valid = false;
        }
    });
    $.each(answer, function (index, value) {
        if (ansarr.indexOf(value) == -1) {
            valid = false;
        }
    });
    if (valid) {
        control_jump("next");
    } else {
        var i = 0;
        var shadow_style_normal = $("#exec_content_multichoice_text").css("box-shadow");
        while ($("#option_" + i).length > 0) {
            $("#option_" + i).css("box-shadow", shadow_style_normal)
            i++;
        }
        ansarr = new Array();
        //alert("Wrong");
        popup("try again~");
        $("#audio_err")[0].play();
    }
}

function chose_multichoice(ans) {
    var shadow_style_normal = $("#exec_content_multichoice_text").css("box-shadow");
    var onselected_color = "#0af";
    var onselected_shadow = "3px";
    var onselected_blursize = "1px";
    var tmp =
        ",width 0px scale color inset,-width 0px scale color inset,0px -width scale color inset,0px width scale color inset";
    var shadow_style_append = tmp.replace(/width/g, onselected_shadow).replace(/scale/g, onselected_blursize).replace(
        /color/g, onselected_color);
    var shadow_style_select = shadow_style_normal + shadow_style_append;
    var numans_current = (ansarr.indexOf(ans));
    var numans = optionletter.indexOf(ans);
    if (numans_current == -1) {
        ansarr.push(ans);
        $("#option_" + numans).css("box-shadow", shadow_style_select);
    } else {
        ansarr.splice(numans_current);
        $("#option_" + numans).css("box-shadow", shadow_style_normal);
    }
}