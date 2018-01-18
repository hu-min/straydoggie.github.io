var pageHeight = window.innerHeight;
var pageWidth = window.innerWidth;

$(".view_exec_content").each(function () {
    var element = $("#" + this.id);
    element.height(pageHeight + "px");
    element.width(pageWidth + "px");
    //drag_prvent(element);
});


$("#background").height(pageHeight);
$("#background").width(pageWidth);
drag_prvent($("#background"));

function control_jump(to) {
    if (to == "next") {
        if (pointer < pointer_array.length - 1) {
            pointer++;
        } else {
            pointer = 0;
        }
    } else if (to == "prev") {
        if (pointer > 0) {
            pointer--;
        } else {
            pointer = pointer_array.length - 1;
        }
    } else if (to == 0) {
        pointer = 0;
    } else {

    }
    content_fill();
}

function content_fill() {
    content_fill_prepare();
    current_data=exec_data[pointer_array[pointer]];
    type=current_data.type;
    content=current_data.content;
    //type = exec_data[pointer_array[pointer]].type;
    //content = exec_data[pointer_array[pointer]].content;
    if (type == "unichoice") {
        content_fill_unichoice();
    } else if (type == "multichoice") {
        content_fill_multichoice();
    } else if (type == "fillblank") {
        content_fill_fillblank();
    }else if(type=="truefalse"){
        content_fill_truefalse();
    }
    adjust_cmd();
    test();
}

function content_fill_prepare() {
    $("#exec_content_unichoice_option").empty();
    $("#exec_content_multichoice_option").empty();
    $("#exec_content_unichoice").hide();
    $("#exec_content_multichoice").hide();
    $("#exec_content_fillblank").hide();
    $("#exec_content_truefalse").hide();
}

function adjust_cmd() {
    $("#blank").height(0);
    var blank_height = pageHeight - $("#exec_command").height() - $("#exec_command").offset().top;
    $("#blank").height(blank_height - 30);
}

function test() {
    $("#background").height($(document).height());
    var pattern = Trianglify({
        width: window.innerWidth,
        height: $(document).height()
    });
    $("#background").html(pattern.svg());
}

function drag_prvent(element) {
    element.on('touchmove', function (e) {
        e.preventDefault();
    });
}