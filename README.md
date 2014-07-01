PHP_SocketIO_Client
===================

PHP client for socket.io (websocket client)

How to use:
$socketio = new SocketIO();
if ($socketio->send('localhost', 9090, 'Hello world!')){
    echo 'we sent the message and disconnected';
} else {
    echo 'Sorry, we have a mistake :\'(';
}
