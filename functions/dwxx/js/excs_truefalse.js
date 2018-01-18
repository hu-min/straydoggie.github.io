function content_fill_truefalse() {
    number = content.number;
    text = content.text;
    answer = content.answer;
    $("#exec_content_truefalse_option").html(
        "<input id=\"tfbtn_true\" class=\"valid_btn\" type=\"button\" value=\"True\" onclick=\"check_truefalse('T')\">"+
        "<br>"+
        "<input id=\"tfbtn_false\" class=\"valid_btn\" type=\"button\" value=\"False\" onclick=\"check_truefalse('F')\">"
    );
    $("#exec_content_truefalse_text").html('<p class="text_inner">' + number + '. ' + text + '</p>');
    $("#exec_title").html('判断题 ' + (pointer + 1) + '/' + pointer_array.length);
    $("#exec_content_truefalse").show();
}

function check_truefalse(ans) {
    //check answer of uni-choice in exercise
    if (ans == answer) {
        //right
        control_jump("next");
        return true;
    } else {
        //wrong
        $("#audio_err")[0].play();
        if(ans=="T"){$("#tfbtn_true").attr("style", "background:rgba(255, 0, 0, 0.6)");}
        else{$("#tfbtn_false").attr("style", "background:rgba(255, 0, 0, 0.6)");}
        return false;
    }
}