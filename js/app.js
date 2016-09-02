var chat = document.getElementById("chatwindow");
var msg = document.getElementById("messagebox");

var socket = new WebSocket("ws://chat.dev:2002");

var open = false;

function addmessage(msg){
  chat.innerHTML += "<p>" + msg + "</p>";
}

msg.addEventListener('keypress', function(evt){
    if (evt.charCode != 13) {
      return;
    }

    // console.log(msg.value);
    evt.preventDefault();

    if (msg.value == "" || !open) {
      return;
    }
    socket.send(JSON.stringify({
      msg: msg.value
    }));

    addmessage(msg.value);
    msg.value = "";
});

socket.onopen = function(){
  open = true;
  addmessage("Connected");
};

socket.onmessage = function(evt){
  var data = JSON.parse(evt.data);
  addmessage(data.msg);
};

socket.onclose = function(){
  open = false;
  addmessage("Disconnected");
};
