<?php

namespace App\Models;

use Database\DBConnection;
use PDO;

class Model
{
    protected $db;
    protected $table ;

    public function __construct(DBConnection  $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        return $this->getQuery("SELECT * FROM {$this->table} WHERE is_active = 1  ORDER BY PostingDate");
    }

    public function getById(int $id): Model
    {

        return $this->getQuery("SELECT * FROM {$this->table} where  id = ?  and is_active = 1", $id, true);
    }

    public function getQuery($sql, int $params = null, bool $single = null)
    {

        $method = is_null($params) ? 'query' : 'prepare';
        $fetch  = is_null($single) ? 'fetchAll' : 'fetche';
        $stmt = $this->db::getInstance()->$method($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_class($this), [$this->db]);
        if ($method==='query') {
            return  $stmt->fetchAll();
        } else {
            $stmt->execute([$params]);
            return $stmt ->fetch();
        }

    }
}
