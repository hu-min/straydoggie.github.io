var pointer = 0;
var pointer_array;
var exec_data;
var raw_data;

function start(id, count) {
    //id:101000,102000
    //count:54,34
    $.post("./php/exec_get_data.php", {
            key: "get",
            ident: id,
            length: count
        },
        function (data, status) {
            process_data(data);
        });
    $("#view_config").hide();
    $("#view_essential").show();
}

function process_data(data) {
    raw_data = data;
    exec_data = eval(data);
    pointer_array = gen_pointer_array(true);
    pointer = 0;
    content_fill();
}

function gen_pointer_array(random) {
    arr1 = new Array();
    arr2 = new Array();
    for (var i = 0; i < exec_data.length; i++) {
        arr1.push(i);
    }
    if (random) {
        for (i = 0; i < exec_data.length; i++) {
            var num = Math.floor(Math.random() * arr1.length);
            arr2.push(arr1[num]);
            arr1.splice(num, 1);
        }
        return arr2;
    } else {
        return arr1;
    }
}

function echo(str) {
    $("#view_result").html(str);
}
//overall elements
var current_data;
var optionletter = new Array("A", "B", "C", "D", "E", "F", "G", "H", "I");
var number;
var text;
var option;
var answer;
var content;
var ansarr;
var type;