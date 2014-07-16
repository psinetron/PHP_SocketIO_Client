PHP_SocketIO_Client
===================

EN: PHP client for socket.io (websocket client)

How to use:
```
$socketio = new SocketIO();
if ($socketio->send('localhost', 9090, 'Hello world!')){
    echo 'we sent the message and disconnected';
} else {
    echo 'Sorry, we have a mistake :\'(';
}
```



RUS: PHP клиент для socket.io

Как использовать:
```
$socketio = new SocketIO();
if ($socketio->send('localhost', 9090, 'Привет мир!')){
    echo 'Мы отправили сообщение и отключились от сокета';
} else {
    echo 'Возникла ошибка при общении с  веб-сокетом :\'(';
}
```
