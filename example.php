<?php

include_once ('socket.io.php');

//Create a socket.io connection and send a simple message
$socketio = new SocketIO();
if ($socketio->send('localhost', 9090, 'Hello world!')){
    echo 'we sent the message and disconnected';
} else {
    echo 'Sorry, we have a mistake :\'(';
}



?>