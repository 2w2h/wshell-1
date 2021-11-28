<?php

namespace WshellBundle\Terminal;

use Datto\JsonRpc\Evaluator;

class Terminal implements Evaluator
{
    public function describe()
    {
        return [
            'ls'        => 'Выдаёт содержимое текущей директории в виде списка, необходим токен. (Использование: ls [token])',
            'login'     => 'login to the server (return token). Using:\nlogin [login] [password]',
            'soundtest' => 'Soundtest command',
            'whoami'    => 'return user information',
            'asciicam'  => 'ASCII camera(https://github.com/idevelop/ascii-camera)',
            'ram'       => 'check RAM memory usage',
            'test'      => 'Testing of all elements of the system',
            'imitation' => 'imitation of a typical human conversation from the standpoint computer\n
      The ideal solution for the Turing test',
            'random'    => 'Generate a random number. most random',
            'bb'        => 'Beavis & Butt-head ASCII',
            'help'      => 'help',
        ];
    }

    public function evaluate($method, $arguments)
    {
        return $this->$method(...$arguments);
    }

    public function help($command = null)
    {
        if ($command) {
            return $this->describe()[$command] ?? 'Не найдена справка по этой команде';
        }
        $result = [];
        foreach ($this->describe() as $command => $help) {
            $result[] = $command . ": " . $help;
        }
        return implode("\n", $result);
    }

    public function ls($token = null, $path = null)
    {
        if (strcmp(md5("noname:1234"), $token) == 0) {
            if (@is_file($path)) {
                throw new \Exception("No. I'ts file, why are you doing this? By hand enter path, bummer");
            } elseif (@preg_match("/\.\./", $path)) {
                throw new \Exception("No directory traversal Dude");
            } elseif (@!is_dir($path)) {
                throw new \Exception("Nothing. You make me feel sad.");
            } else {
                $base = preg_replace("/(.*\/).*/", "$1", $_SERVER["SCRIPT_FILENAME"]);
                $path = $base . ($path[0] != '/' ? "/" : "") . $path;
                $dir = opendir($path);
                while ($name = readdir($dir)) {
                    $fname = $path . "/" . $name;
                    if (!is_dir($name) && !is_dir($fname)) {
                        $list[] = $name;
                    }
                }
                closedir($dir);
                return $list;
            }
        } else {
            throw new \Exception("Access Denied");
        }
    }

    public function login($user = '', $passwd = '')
    {
        if (strcmp($user, 'noname') == 0 && strcmp($passwd, '1234') == 0) {
            return md5($user . ":" . $passwd);
        } else {
            throw new \Exception("Wrong Password. This is not the case, see 'help login'");
        }
    }

    public function soundtest()
    {
        return '<audio controls><source src="sound/test.mp3" type="audio/mpeg"></audio>';
    }

    public function whoami()
    {
        return "your User Agent : " . $_SERVER["HTTP_USER_AGENT"] .
            "\nyour IP : " . $_SERVER['REMOTE_ADDR'] .
            "\nyou access this from : " . $_SERVER["HTTP_REFERER"];
    }

    public function asciicam()
    {
        return '<pre id="ascii"></pre>
<script src="script/camera.js"></script>
<script src="script/ascii.js"></script>
<script src="script/app.js"></script>';
    }

    public function ram()
    {
        return 'pRamPeak: ' . (memory_get_peak_usage(TRUE) >> 10) . 'kB curMemoryPeak: ' . (memory_get_peak_usage() >> 10) . "kB";
    }

    public function test()
    {
        // dont true. Need make it with help JS!
        //sleep(2);
        $memory = (int)(diskfreespace('.') / 1024 / 1024);
        return "free physical memory: $memory Mb
Character proccesor: 5x4.2 GHz EBP64 Mesk Blesa Company
Visualization driver: not information
Sound driver: not information
I/O controller: NABLA-terminal ver 1.0";
    }

    public function imitation()
    {
        return "Bla Bla Bla! Bla-blabla bla-bla-bla\n
        bla bla bla bla bla bla\n
        Bla BLALBLABA babla!\n
        bla bla? bla blabla. Bla.";
    }

    public function random()
    {
        return '42';
    }

    public function bb()
    {
        return <<<EOD
          ________________
         /                \
        / /          \ \   \
        |                  |
       /                  /
      |      ___\ \| | / /
      |      /          \
      |      |           \
     /       |      _    |
     |       |       \   |
     |       |       _\ /|     I am Corn Julio!!! I need TP for my
     |      __\     <_o)\o-    bunghole!!!! Where we come from we
     |     |             \     have no bungholes...Would you like
      \    ||             \    to be my bunghole?
       |   |__          _  \    /
       |   |           (*___)  /
       |   |       _     |    /
       |   |    //_______/
       |  /       | UUUUU__
        \|        \_nnnnnn_\-\
         |       ____________/
         |      /
         |_____/


                                      .-------------.
                                     /               \
                                    / .-----.         \
 I am the Great Cornholio!!         |/ --`-`-\         \
                                    |         \        |
 I need TP for my bunghole!!         |   _--   \       |
                                     _| =-.     |      |
 Come out with your pants down!      o|/o/      |      |
                                     /  ~       |      |
 ARE YOU THREATENING ME??          (____@)  ___ |      |
                                       _===~~~.`|      |
 Oh. heh-heh.  Sorry about that.   _______.--~  |      |
                                    \_______    |      |
 heh-heh.  This is cool.  heh-heh        |  \   |      |
                                          \_/__/       |
                                        /            __\
                                        -| Metallica|| |
                                        ||          || |
                                        ||          || |
                                        /|          / /

     ________________                            ______________
    /                \                          / /            \-___
   / /          \ \   \                         |     -    -         \
   |                  |                         | /         -   \  _  |
  /                  /                          \    /  /   //    __   \
 |      ___\ \| | / /                            \/ // // / ///  /      \
 |      /         \                              |             //\ __   |
 |      |           \                            \              ///     \
/       |      _    |                             \               //  \ |
|       |       \   |                              \   /--          //  |
|       |       _\ /|                               / (o-            / \|
|      __\     <_o)\o-                             /            __   /\ |
|     |             \                             /               )  /  |
 \    ||             \                           /   __          |/ / \ |
  |   |__          _  \                         (____ *)         -  |   |
  |   |           (*___)                            /               |   |
  |   |       _     |                               (____            |  |
  |   |    //_______/                                ####\           | |
  |  /       | UUUUU__                                ____/ )         |_/
   \|        \_nnnnnn_\-\                             (___             /
    |       ____________/                              \____          |
    |      /                                              \           |
    |_____/                                                \___________\
   /\/\/\/\/\/\/\/\/\                        /\/\/\/\/\/\/\/\/\/\/\/\/\
  /                  \                      /                          \
 <     B E A V I S    >          AND       <     B U T T - H E A D      >
  \                  /                      \                          /
   \/\/\/\/\/\/\/\/\/                        \/\/\/\/\/\/\/\/\/\/\/\/\/


AVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVIS
BEAVIS ________________BEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVI
SBEAV /                \ISASSWIPEBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISB
AVISB/ /          \ \   \EAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAV
ISBEA|                  |VISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVIS
BEAV/                  /BEAVISBEAVISBEAVISFARTKNOCKERSBEAVISBEAVISBEAV
ISB|      ___\ \| | / /ISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEA
VIS|      /          \VISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEA
BEA|      |           \BEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVI
VI/       |      _    |SBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAV
SB|       |       \   |
EA|       |       _\ /|     THESE ARE THOSE GUYS FROM MY DREAM!!
VI|      __\     <_o)\o-    THOSE ALIEN GUYS!! THEY LIKE COME INTO
SB|     |             \     MY ROOM WITH THIS SHINING WHITE LIGHT
EAV\    ||             \    AND THEY'VE GOT LIKE NADS ON THEIR
ISBE|   |__          _  \   HEADS AND THEN THEY LIKE TIE ME TO A
AVIS|   |           (*___)  CHAIR AND GET LIKE MEDIEVAL ON MY ASS!!
BEAV|   |       _     |    /
ISBE|   |    //_______/     BEAVISBEAVISBEAVISBEAVISBEAVISBEAVBUNGHOLE
AVIS|  /       | UUUUU__    BEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVIS
BEAVI\|        \_nnnnnn_\-\ BEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVIS
SBEAVI|       ____________/ BEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVIS
BEAVIS|      /BEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBEAVISBE
EOD;
    }
}
