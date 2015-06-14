<?php

include_once ('socket.io.php');

// Create a socket.io connection and send a simple message.
$socketio = new SocketIO();
if ($socketio->send('localhost', 3000, 'chat message', 'foo')) {
  echo 'we sent the message and disconnected';
}
else {
  echo 'Sorry, we have a mistake :\'(';
}
