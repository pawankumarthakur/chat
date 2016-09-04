var messagebox = document.getElementById("messagebox");
var username = document.getElementById("username");
var chatcontainer = document.getElementById("chatcontainer");
var conn;

$("#submitusername").click(function (evt) {
    if (username.value == "")
        return;
    evt.preventDefault();
    var name = username.value;
    $("#login-container").css('display', 'none');
    chatcontainer.style.display = "block";

    conn = new Connection(name, "chatwindow", "chat.warlock31.com:8080", "usercount");

});

messagebox.addEventListener('keypress', function (evt) {
    if (evt.keyCode != 13 || conn == undefined) {
        return;
    }

    // console.log(msg.value);
    evt.preventDefault();

    if (this.value == "") {
        return;
    }

    conn.sendMsg(this.value);
    this.value = "";
});
