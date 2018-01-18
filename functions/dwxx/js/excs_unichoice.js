function content_fill_unichoice() {
    option = content.option;
    number = content.number;
    text = content.text;
    answer = content.answer;
    for (var i = 0; i < option.length; i++) {
        $("#exec_content_unichoice_option").append($("#option_eg_unichoice").clone().attr('id', 'option_' + i));
        $("#option_" + i).html('<p class="text_inner">' + optionletter[i] + '. ' + option[i] + '</p>');
        $("#option_" + i).attr('onclick', 'check_unichoice("' + optionletter[i] + '");');
        $("#option_" + i).show();
    }
    $("#exec_content_unichoice_text").html('<p class="text_inner">' + number + '. ' + text + '</p>');
    $("#exec_title").html('单项选择题 ' + (pointer + 1) + '/' + pointer_array.length);
    $("#exec_content_unichoice").show();
}

function check_unichoice(ans) {
    //check answer of uni-choice in exercise
    if (ans == answer) {
        //right
        control_jump("next");
        return true;
    } else {
        //wrong
        $("#audio_err")[0].play();
        $("#option_" + optionletter.indexOf(ans)).attr("style", "background:rgba(69, 69, 69, 0.6)");
        return false;
    }
}