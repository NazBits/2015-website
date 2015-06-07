<?php


class Issue
{
    public $description;
    public $votes;
    public $tags;
    public $id;

    public function insert(){
        $res = DB::query('INSERT INTO issues (description,votes) values (?,0)', [$this->description]);
        $this->id = DB::db()->lastInsertId();
    }

    public function update()
    {
        $res = DB::query('UPDATE issues SET description=?,votes=? WHERE id=?',[
            $this->description, $this->votes, $this->id
        ]);
    }

    public function toJson(){
        return json_encode([
            'description'=>$this->description,
            'votes'=>$this->votes,
            'id'=>$this->id]);
    }

    public static function findAll(){
        $res = DB::query('SELECT * FROM issues ORDER BY votes DESC');
        $objs = [];
        while($row = $res->fetch()){

            $objs[] = static::fromRow($row);
        }

        return $objs;
    }



    public static function find($id)
    {
        $res = DB::query('SELECT * FROM issues WHERE id=?',[$id]);
        $row = $res->fetch();
        if($row)
            return static::fromRow($row);
    }

    private static function fromRow($row)
    {
        $issue = new static();
        $issue->description = $row['description'];
        $issue->votes = (int) $row['votes'];
        $issue->id = (int) $row['id'];
        return $issue;
    }

}

class Antivirus
{
    public $id;
    public $name;
    public $image;
    public $votes = 0;

    public function update()
    {
        $res = DB::query('UPDATE antiviruses SET name=?,votes=? WHERE id=?',[
            $this->name, $this->votes, $this->id
        ]);
    }

    public static function findAll()
    {
        $res = DB::query('SELECT * FROM antiviruses ORDER BY votes DESC');
        $avs = [];
        while($row = $res->fetch()){

            $avs[] = static::fromRow($row);
        }

        return $avs;
    }

    public static function find($id)
    {
        $res = DB::query('SELECT * FROM antiviruses WHERE id=?',[$id]);
        $row = $res->fetch();
        if($row)
            return static::fromRow($row);
    }

    public static function fromRow($row)
    {
        $av = new static();
        $av->id = (int) $row['id'];
        $av->name = $row['name'];
        $av->image = $row['image'];
        $av->votes = (int) $row['votes'];
        return $av;
    }

}

class Session
{
    public static function init()
    {
        if(!isset($_SESSION)){
            session_set_cookie_params(864000);
            session_start();
        }
        if(!isset($_SESSION['init'])){
            $_SESSION['init'] = true;
            $_SESSION['issueVotes'] = [];
            $_SESSION['avBooked'] = [];
        }
    }

    public function issueVoted($issueId)
    {
        self::init();
        return isset($_SESSION['issueVotes'][$issueId]);
    }

    public function saveIssueVote($issueId)
    {
        self::init();
        $_SESSION['issueVotes'][$issueId] = true;
    }

    public function avBooked($avId = null)
    {
        self::init();
        if(!$avId)
            return count($_SESSION['avBooked']) > 0;
        return isset($_SESSION['avBooked'][$avId]);
    }

    public function saveAVBook($avId)
    {
        self::init();
        $_SESSION['avBooked'][$avId] = true;
    }
}

class Request
{
    public static function post($var,$default)
    {
        if(isset($_POST[$var]))
            return $_POST[$var];
        return $default;
    }

    public static function get($var,$default)
    {
        if(isset($_GET[$var]))
            return $_POST[$var];
        return $default;
    }

    public static function any($var, $default)
    {
        if(isset($_GET[$var]))
            return $_GET[$var];
        if(isset($_POST[$var]))
            return $_POST[$var];
        return $default;
    }

}

class DB
{
    private static $db;

    private function __construct()
    {
        $envdb = getenv('DATABASE_URL');
        $dbopts = parse_url($envdb);
        $connstr = $envdb? 'pgsql:dbname='.ltrim($dbopts['path'],'/').';host='.$dbopts['host'] : Config::db('connstr');
        $user = $envdb? $dbopts['user'] : Config::db('user');
        $pass = $envdb? $dbopts['pass'] : Config::db('pass');

        $this->db = new PDO($connstr, $user, $pass);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function db()
    {
        if(!self::$db){
            $instance = new self();
            self::$db = $instance->db;
        }
        return self::$db;
    }

    public static function query($q, $vars)
    {
        $stmt = self::db()->prepare($q);
        foreach($vars as $key=>$val){
            $var = is_numeric($key)? $key + 1 : $key;
            $stmt->bindValue($var, $val);
        }
        $stmt->execute();
        return $stmt;
    }
}

class Config
{
    private static $loaded = false;
    private static $data = [];

    private static function load()
    {
        if(!self::$loaded){
            if(file_exists(__DIR__."/config")){
                $lines = file(__DIR__.'/config', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach($lines as $line){
                    $line = trim($line);
                    if($line[0] == "#")
                        continue;
                    $line = $line;
                    $parts = explode("=", $line);
                    $var = strtolower($parts[0]);
                    $value = $parts[1];
                    $varParts = explode("_", $var);
                    $namespace = $varParts[0];
                    $name = $varParts[1];
                    if(isset(self::$data[$namespace])){
                        self::$data[$namespace] = [];
                    }
                    self::$data[$namespace][$name] = $value;
                }
            }
            self:$loaded = true;
        }
    }

    public static function __callStatic($func, $args)
    {
        self::load();
        if(isset(self::$data[$func])){
            if(count($args) > 0 && isset(self::$data[$func][$args[0]])){
                return self::$data[$func][$args[0]];
            }
        }
        return count($args)>= 1? $args[1] : null;
    }

}
