function content_fill_fillblank() {
    number = content.number;
    text = content.text;
    answer = content.answer;
    ansarr = new Array();
    content_fill_prepare();
    var fillblank_html = "";
    var tmp_blanknumber = -1;
    fillblank_html = content.number + ". ";
    for (var i = 0; i < text.length; i++) {
        if (text[i] == "%" & text[i + 1] == "_" & text[i + 2] == "%") {
            tmp_blanknumber++;
            var tmp_blanklength = 0;
            $.each(answer, function (idx, val) {
                if (idx == tmp_blanknumber) {
                    $.each(answer[idx], function (idx_s, val_s) {
                        if (tmp_blanklength < val_s.length) {
                            tmp_blanklength = val_s.length;
                        }
                    });
                }
            });
            tmp_blanklength = parseFloat($("#input_eg_fillblank").css("font-size")) * (tmp_blanklength + 2);
            if(tmp_blanklength>pageWidth-30){tmp_blanklength=pageWidth-35;}
            fillblank_html = fillblank_html + '<input id="blank_' + tmp_blanknumber +
                '" class="fillblank_input" type="text" style="width:' +
                tmp_blanklength + 'px"/>';
            i = i + 2;
        } else {
            fillblank_html += text[i];
        }
    }
    $("#exec_content_fillblank_text").html('<p class="text_inner">' + fillblank_html + '</p>');
    $("#exec_title").html('填空题 ' + (pointer + 1) + '/' + pointer_array.length);
    $("#exec_content_fillblank").show();
}

function valid_fillblank() {
    ansarr = new Array();
    var wrong = false;
    $(".fillblank_input").each(function (idx, val) {
        if (val.id != "input_eg_fillblank") {
            var curinput = $("#" + val.id);
            var cur_wrong = true;
            $.each(answer[idx], function (index, value) {
                if (curinput.val() == value) {
                    cur_wrong = false;
                    return false;
                }
            });
        }

        if (cur_wrong) {
            wrong = true;
            return false;
        }

    });
    if (wrong) {
        popup("Try again~<br><br><p class=\"text_inner\" style=\"font-size:12px\">点击\"?\"可查看正确答案</p>");
        $("#audio_err")[0].play();
    } else {
        control_jump("next");
    }

}

function help_fillblank() {
    $.each(answer, function (idx, val) {
        $("#blank_" + idx).val(val[0]);
    });
}