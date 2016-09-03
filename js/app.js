var messagebox = document.getElementById("messagebox");
var username = document.getElementById("username");
var chatcontainer = document.getElementById("chatcontainer");
var conn;

username.addEventListener('keypress', function(evt){
    if (evt.keyCode != 13 || this.value == "") {
      return;
    }

    // console.log(evt.value);
    evt.preventDefault();

    var name = this.value;
    this.style.display = "none";
    chatcontainer.style.display = "block";

    conn = new Connection(name, "chatwindow", "chat.dev:2000");
});

messagebox.addEventListener('keypress', function(evt){
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
