<?php


class Issue
{
    public $description;
    public $votes;
    public $tags;
    public $id;

    public function insert(){
        $res = DB::query('INSERT INTO issues (description) values (?)', $this->description);
        $this->id = DB::db()->lastInsertId;
    }

    public function toJson(){
        return json_encode([
            'description'=>$this->description,
            'votes'=>$this->votes,
            'id'=>$this->id]);
    }

    public static function findAll(){
        $res = DB::query('SELECT * FROM issues');
        $objs = [];
        while($row = $res->fetch()){
            $obj = new static();
            $obj->description = $row['description'];
            $obj->votes = (int) $row['votes'];
            $obj->id = (int) $row['id'];
            $objs[] = $obj;
        }

        return $objs;
    }


}


class DB
{
    private $db;

    private function __construct()
    {
        $this->db = new PDO(Config::db('connstr'), Config::db('user'), Config::db('pass'));
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function db()
    {
        if(!self::$db){
            self::$db = new self();
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
            if(file_exists('.config')){
                $lines = file('.config', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach($lines as $line){
                    $line = trim($line);
                    if($line[0] == "#")
                        continue;
                    $line = strtolower($line);
                    $parts = explode("=", $line);
                    $var = $parts[0];
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
            if(count($args) > 0 && isset($data[$func][$args[0]])){
                return $data[$func][$arg[0]];
            }
        }
        return count($args)>= 1? $args[1] : null;
    }

}
