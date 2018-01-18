function popup(text, caption) {
    if (!caption) {
        caption = "╮(╯▽╰)╭...";
    }
    $("#popup_error").width(pageWidth * 0.6);
    $("#popup_error").children("#caption").html('<p class="text_inner">' + caption + '</p>');
    $("#popup_error").children("#text").html('<p class="text_inner">' + text + '</p>');
    $("#popup_error").children("#exitbutton").click(function () {
        popupclose();
    });
    $("#popup_error").children("#exitbutton").val("OK");
    $("#popup_error").css("left", (pageWidth - $("#popup_error").width()) * 0.5 + "px");
    $("#popup_error").css("top", (pageHeight - $("#popup_error").height()) * 0.3 + "px");
    $("#popup_cover").height(pageHeight);
    $("#popup_cover").width(pageWidth);
    $("#popup_cover").show();
    $("#popup_error").show();
}

function popupclose() {
    $(".popupview").each(function () {
        $("#" + this.id).hide();
    });
    $("#popup_cover").hide();
}

function popup_echo(text){
    $("#popup_echo").width(pageWidth * 1);
    $("#popup_echo").height(pageHeight * 1);
    $("#popup_echo").css("left", "0px");
    $("#popup_echo").css("top", "0px");
    $("#popup_echo").children("#echo_content").html('<p class="text_inner">' + text + '</p>');
    $("#popup_echo").click(function(){
        popupclose();
    });
    $("#popup_echo").show();
}