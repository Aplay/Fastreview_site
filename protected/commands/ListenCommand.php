<?php
/* go to protected and run in console: php -q yiiclocal listen or just yiiclocal listen */
class ListenCommand extends CConsoleCommand {
        public $host = 'localhost'; //host
        public $port = '8000'; //port
        public $null = NULL; //null var
        public $clients = array();

        // Тут шлем сообщение всем клиентам
        private function send_message($msg)
        {
            foreach($this->clients as $changed_socket)
            {
                @socket_write($changed_socket,$msg,strlen($msg));
            }
            return true;
        }


        //Дкодирование сообщения пришедшего от браузера
        private function unmask($text) {
            $length = ord($text[1]) & 127;
            if($length == 126) {
                $masks = substr($text, 4, 4);
                $data = substr($text, 8);
            }
            elseif($length == 127) {
                $masks = substr($text, 10, 4);
                $data = substr($text, 14);
            }
            else {
                $masks = substr($text, 2, 4);
                $data = substr($text, 6);
            }
            $text = "";
            for ($i = 0; $i < strlen($data); ++$i) {
                $text .= $data[$i] ^ $masks[$i%4];
            }
            return $text;
        }

        //Закодировать сообщение перед отправкой клиенту
        private function mask($text)
        {
            $b1 = 0x80 | (0x1 & 0x0f);
            $length = strlen($text);

            if($length <= 125)
                $header = pack('CC', $b1, $length);
            elseif($length > 125 && $length < 65536)
                $header = pack('CCn', $b1, 126, $length);
            elseif($length >= 65536)
                $header = pack('CCNN', $b1, 127, $length);
            return $header.$text;
        }

        //Делаем рукопожатие
        private function perform_handshaking($receved_header,$client_conn, $host, $port)
        {
            $headers = array();
            $lines = preg_split("/\r\n/", $receved_header);
            foreach($lines as $line)
            {
                $line = chop($line);
                if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
                {
                    $headers[$matches[1]] = $matches[2];
                }
            }

            $secKey = $headers['Sec-WebSocket-Key'];
            $secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
            //hand shaking header
            $upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
                "Upgrade: websocket\r\n" .
                "Connection: Upgrade\r\n" .
                "WebSocket-Origin: $host\r\n" .
                "WebSocket-Location: ws://$host:$port/users/test\r\n".
                "Sec-WebSocket-Accept:$secAccept\r\n\r\n";
            socket_write($client_conn,$upgrade,strlen($upgrade));
        }

        public function run($args) {
            //Создаем сокет
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            //многоразовый порт
            socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

            //привязываем сокет к порту
            socket_bind($socket, 0, $this->port);

            //ставим порт на прослушку
            socket_listen($socket);

            //create & add listning socket to the list
            $this->clients = array($socket);

            //делаем бесконечный цикл, чтобы наш скрипт не останавливался
            while (true) {
                //управление несколькими соединениями
                $changed = $this->clients;
                //возвращает объект сокета в $changed array
                socket_select($changed, $null, $null, 0, 10);

                //проверяем, явяляется ли соединение новым
                if (in_array($socket, $changed)) {
                    $socket_new = socket_accept($socket); //принимаем сокет
                    $this->clients[] = $socket_new; //добавляем сокет в массив клиентов

                    $header = socket_read($socket_new, 1024); //считываем данные из соединения
                    $this->perform_handshaking($header, $socket_new, $this->host, $this->port); //делаем рукопожатие

                    socket_getpeername($socket_new, $ip); //берем ip адрес клиента
                    $response = $this->mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected')));
                    $this->send_message($response); //шлем всем сообщение, о том, что пришел новый клиент

                    //make room for new socket
                    $found_socket = array_search($socket, $changed);
                    unset($changed[$found_socket]);
                }

                //цикл для всех соединенных сокетов
                foreach ($changed as $changed_socket) {

                    //проверяем, есть ли новое сообщение
                    while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
                    {
                        $received_text = $this->unmask($buf); //декодируем его

                        //готовим тестовое сообщение клиентам
                        $response_text = $this->mask(json_encode(array('type'=>'usermsg', 'message'=>$received_text)));
                        $this->send_message($response_text); //шлем
                        break 2; //выходим из этого цикла
                    }

                    $buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
                    if ($buf === false) { // проверяем, пустое ли сообщение
                        // удаляем соединение из списка клиентов
                        $found_socket = array_search($changed_socket, $this->clients);
                        socket_getpeername($changed_socket, $ip);
                        unset($this->clients[$found_socket]);

                        //сообщаем всем, что клиент вышел
                        $response = $this->mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
                        $this->send_message($response);
                    }
                }
            }
            // закррываем соединение
            socket_close($sock);
        }
}