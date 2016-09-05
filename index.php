<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Chat | Warlock31</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/style.css" media="screen" title="no title">
</head>
<body>
<div class="container" id="chatApp">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <li role="presentation" class="active"><a h ref="#">Home</a></li>
                <li role="presentation"><a href="#">Users</a></li>
                <li role="presentation"><a href="#">Contact</a></li>
            </ul>
        </nav>
        <h3 class="text-muted" id="usercount">Chat <span>{{usercount}}</span></h3>
    </div>

    <div class="row" id="login-container">
        <div class="col-md-6 col-md-offset-3">
            <h2>Login to Chat</h2>
            <div class="form-group" id="user">
                <label for="username">Enter your username:</label>
                <input type="text" id="username" autocomplete="off" class="form-control"
                       placeholder="enter your username">
            </div>
            <div class="form-group">
                <input type="button" id="submitusername" v-on:click="userSubmit" class="btn btn-success" value="Login">
            </div>
        </div>

    </div>

    <div class="" id="chatcontainer">
        <div class="chat" id="chatwindow" v-model="chatwindow">
            <li class="chatbox" v-for="chat in chats">
                <p v-if="chat.type == 'system'" id="chat-system-msg">
                    <span>{{chat.name}}: </span> {{chat.message}} ({{chat.time}})
                </p>
                <p v-if="chat.type == 'user'" id="chat-user-msg">
                    <span>{{chat.name}}: </span> {{chat.message}} ({{chat.time}})
                </p>
            </li>
        </div>
        <div class="form">
            <textarea name="message" v-model="premessage" id="messagebox" v-on:keyup.enter="sendmessage" placeholder="Write here"></textarea>
        </div>
    </div>
</div>


<script src="js/jquery-3.1.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/vue.min.js"></script>
<script src="js/vue.app.js"></script>
</body>
</html>
