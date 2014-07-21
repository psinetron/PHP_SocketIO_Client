<?php
/**
 * Class SocketIO
 * develope by psinetron (slybeaver)
 * Git: https://github.com/psinetron
 * web-site: http://slybeaver.ru
 *
 */
class SocketIO
{

    /**
     * @param null $host - $host of socket server
     * @param null $port - port of socket server
     * @param null $message - message to socket server
     * @param string $address - addres of socket.io on socket server
     * @param string $transport - transport type
     * @return bool
     */

    public function send($host = null, $port = null, $message = null, $address = "/socket.io/websocket/", $transport = 'websocket')
    {
        $fd = fsockopen($host, $port, $errno, $errstr);
        if (!$fd) {
            return false;
        } //Can't connect tot server
        $key = $this->generateKey();

        $out = "GET $address?transport=$transport HTTP/1.1\r\n";
        $out .= "Host: http://$host:$port\r\n";
        $out .= "Upgrade: WebSocket\r\n";
        $out .= "Connection: Upgrade\r\n";
        $out .= "Sec-WebSocket-Key: $key\r\n";
        $out .= "Sec-WebSocket-Version: 13\r\n";
        $out .= "Origin: *\r\n\r\n";
        
        
        
        fwrite($fd, $out);
        
        //*TODO
        // EN: Need to wait response status before sending info
        // RUS: Нужно убедиться что сервер ответил, прежде чем выполнять следующию строку
        
        fwrite($fd, $this->hybi10Encode('42["message", "' . addslashes($message) . '"]'));
        return true;
    }


    private function generateKey($length = 16)
    {
        $c = 0;
        $tmp = '';

        while ($c++ * 16 < $length) {
            $tmp .= md5(mt_rand(), true);
        }

        return base64_encode(substr($tmp, 0, $length));
    }


    private function hybi10Encode($payload, $type = 'text', $masked = true)
    {
        $frameHead = array();

        $payloadLength = strlen($payload);
        switch ($type) {
            case 'text':
                $frameHead[0] = 129;
                break;
            case 'close':
                $frameHead[0] = 136;
                break;
            case 'ping':
                $frameHead[0] = 137;
                break;
            case 'pong':
                $frameHead[0] = 138;
                break;
        }
        if ($payloadLength > 65535) {
            $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
            $frameHead[1] = ($masked === true) ? 255 : 127;
            for ($i = 0; $i < 8; $i++) {
                $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
            }
            if ($frameHead[2] > 127) {
                $this->close(1004);
                return false;
            }
        } elseif ($payloadLength > 125) {
            $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
            $frameHead[1] = ($masked === true) ? 254 : 126;
            $frameHead[2] = bindec($payloadLengthBin[0]);
            $frameHead[3] = bindec($payloadLengthBin[1]);
        } else {
            $frameHead[1] = ($masked === true) ? $payloadLength + 128 : $payloadLength;
        }
        foreach (array_keys($frameHead) as $i) {
            $frameHead[$i] = chr($frameHead[$i]);
        }
        if ($masked === true) {
            $mask = array();
            for ($i = 0; $i < 4; $i++) {
                $mask[$i] = chr(rand(0, 255));
            }

            $frameHead = array_merge($frameHead, $mask);
        }
        $frame = implode('', $frameHead);

        for ($i = 0; $i < $payloadLength; $i++) {
            $frame .= ($masked === true) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
        }

        return $frame;
    }

}

?>
