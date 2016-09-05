new Vue({
    el: "#chatApp",
    data: {
        usercount: "",
        username: "",
        conn: null,
        chatwindow: "",
        open: false,
        socket: null,
        url: "",
        chats: [],
        premessage: "",
        users: []
    },
    methods: {
        updateUsername: function() {
            console.log(this.username);
            this.socket.send(JSON.stringify({
                action: 'setname',
                username: this.username
            }));
        },
        sendmessage: function(){
            console.log("Clicked enter");
            this.sendMsg(this.premessage);
            this.premessage = "";
        },
        initialSetup: function () {
            this.open = false;
            this.socket = new WebSocket("ws://" + this.url);
            this.setupConnectionEvents();
        },
        userSubmit: function (evt) {
            if ($("#username").val() == "")
            return;
            this.username = $("#username").val();
            $("#login-container").css('display', 'none');
            chatcontainer.style.display = "block";
            this.url = "chat.dev:8080";
            this.conn = this.initialSetup();
        },
        addChatMessage: function (name, msg) {

            this.chats.push({type: 'user', message: msg, name: name, time: new Date(Date.now()).toLocaleTimeString()});
            //this.chatwindow.innerHTML += '<p id="chat-user-msg"><span>' + name + '> </span> ' + msg + '(' + new Date(Date.now()).toLocaleTimeString() + ')' + '</p>';
        },

        addSystemMessage: function (msg) {
            this.chats.push({type: 'system', message: msg, name: 'system', time: new Date(Date.now()).toLocaleTimeString()});
            //this.chatwindow.innerHTML += '<p id="chat-system-msg">' + msg + '(' + new Date(Date.now()).toLocaleTimeString() + ')' + '</p>';
        },
        setupConnectionEvents: function () {
            var self = this;

            self.socket.onopen = function (evt) {
                self.connectionOpen(evt);
            };
            self.socket.onmessage = function (evt) {
                self.connectionMessage(evt);
            };
            self.socket.onclose = function (evt) {
                self.connectionClose(evt);
            };
        },
        connectionOpen: function (evt) {
            this.open = true;
            this.addSystemMessage("Connected to the server.");
            console.log("Connection Opened");
            this.updateUsername();
        },
        connectionMessage: function (evt) {
            if (!this.open)
            return;

            var data = JSON.parse(evt.data);
            switch (data.action) {
                case 'setname':
                    if (data.success) {
                        this.addSystemMessage("Set username to " + this.username);
                    } else {
                        this.addSystemMessage("Username " + this.username + " has been taken.");
                    }
                    break;
                case 'message':
                    this.addChatMessage(data.username, data.msg);
                    break;
                case 'usercount':
                    this.usercount = data.usercount + (data.usercount === "1" ? ' user online' : ' users online');
                    break;
                default:
                    break;

            }
        },
        connectionClose: function (evt) {
            this.open = false;
            this.addSystemMessage("Connection Closed to the server.");
        },
        sendMsg: function (message) {
            if (this.open) {
                this.socket.send(JSON.stringify({
                    action: 'message',
                    msg: message
                }));

                this.addChatMessage(this.username, message);
            } else {
                this.addSystemMessage("You are not connected to the server.");
            }
        }

    }

})
