<?php

namespace database;

use Exception;
use PDOException;
use PDO;

class mysql
{
    private $HOST = 'localhost';
    private $DATABASE = 'oyunrehb_cryptomessenger';
    private $USER = 'oyunrehb_selcuksahin';
    private $PASSWORD = '64666198sst.de';
    private $CHARSET = 'UTF8';
    private $COLLATION = 'utf8_general_ci';
    private $PDO = null;
    private $QR = null;
    private $QUERYSTRING = null;
    private $QUERYPARAMS = [];

    public function __construct()
    {
        $this->connect_sql();
    }

    private function connect_sql()
    {
        $ConnectionString = "mysql:host=" . $this->HOST . ";dbname=" . $this->DATABASE . ";";
        try {
            $this->PDO = new PDO($ConnectionString, $this->USER, $this->PASSWORD);
            $this->PDO->exec("SET NAMES '" . $this->CHARSET . "' COLLATE '" . $this->COLLATION . "'");
            $this->PDO->exec("SET CHARACTER SET '" . $this->CHARSET . "'");
            $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exc) {
            exit("Mysql bağlantı hatası ;" . $exc->getMessage());
        }
    }
    public function manual_operation(string $query, array $params = null)
    {
        if (is_null($params)) {
            $operation = $this->PDO->query($query);
        } else {
            $operation = $this->PDO->prepare($query);
            $operation->execute($params);
        }
        return $operation;
    }

    //! SELECT
    public function select(string $columnName, string $tableName, string $tag = null)
    {
        $explode = explode(" ", $columnName);
        $imlode = implode("", $explode);
        $this->QUERYSTRING = "SELECT $imlode FROM $tableName $tag";
        return $this;
    }

    //! INSERT
    // public function insert(string $tableName, string $columnName, array $params)
    // {
    //     try {
    //         $this->QUERYSTRING = "INSERT INTO $tableName SET $columnName";
    //         $this->QUERYPARAMS = array_merge($this->QUERYPARAMS, $params);
    //         $this->execute();
    //     } catch (PDOException $exc) {
    //         exit($exc->getMessage());
    //     }
    //     return $this->PDO->lastInsertId();
    // }
    public function insert(string $tableName, string $columnName, string $values, array $params)
    {
        try {
            $this->QUERYSTRING = "INSERT INTO $tableName($columnName) VALUES ($values) ";
            $this->QUERYPARAMS = array_merge($this->QUERYPARAMS, $params);
            $this->execute();
            $this->clean_query();
        } catch (PDOException $exc) {
            exit($exc->getMessage());
        }
        return $this->PDO->lastInsertId();
    }

    //! UPDATE
    public function update(string $tableName, string  $columnName, array $params)
    {
        try {
            $this->QUERYSTRING = "UPDATE $tableName SET $columnName";
            $this->QUERYPARAMS = array_merge($this->QUERYPARAMS, $params);
            return $this;
        } catch (PDOException $exc) {
            exit($exc->getMessage());
        }
    }

    //! DELETE
    public function delete(string $tableName)
    {
        try {
            $this->QUERYSTRING = "DELETE FROM $tableName";
            return $this;
        } catch (PDOException $exc) {
            exit($exc->getMessage());
        }
    }

    //* WHERE
    public function where(string $filters, array $params = null)
    {
        $this->QUERYSTRING .= " WHERE $filters ";
        if (count($params) >= 0) {
            $this->QUERYPARAMS = array_merge($this->QUERYPARAMS, $params);
        }
        return $this;
    }

    //* HAVING
    public function having(string $filters, array $params)
    {
        $this->QUERYSTRING .= " HAVING $filters ";
        if (count($params) >= 0) {
            $this->QUERYPARAMS = array_merge($this->QUERYPARAMS, $params);
        }
        return $this;
    }

    //* ORDER BY
    public function order_by(string $orderBy, string $align = "desc")
    {
        $this->QUERYSTRING .= " ORDER BY $orderBy $align ";
        return $this;
    }

    //* GROUP BY
    public function group_by(string $groupBy)
    {
        $this->QUERYSTRING .= " GROUP BY $groupBy ";
        return $this;
    }

    //* LIMIT
    public function limit(int $limit)
    {
        $this->QUERYSTRING .= " LIMIT $limit ";
        return $this;
    }

    //* LIKE
    public function like(string $search, string $position)
    {
        switch ($position) {
            case "START":
                $this->QUERYSTRING .= " LIKE '" . $search . "%' ";
                return $this;
                break;
            case "END":
                $this->QUERYSTRING .= " LIKE '%" . $search . "' ";
                return $this;
                break;
            case "BETWEEN":
                $this->QUERYSTRING .= " LIKE '%" . $search . "%' ";
                return $this;
                break;

            default:
                echo "Geçersiz değer";
                break;
        }
    }

    //* AND
    public function and(string $tag, string $tag2, string $key)
    {
        $this->QUERYSTRING .= " AND $tag.$key=$tag2.$key ";
        return $this;
    }

    //* JOIN
    public function join(string $tableName, string $tag, string $key, string $joinType = "INNER")
    {
        $joinTable = explode(" ", $this->QUERYSTRING);
        $this->QUERYSTRING .= " $joinType JOIN $tableName $tag ON $tag.$key=$joinTable[4].$key ";
        return $this;
    }

    //* CASE WHEN THEN
    public function select_case(string $columnName)
    {
        $explode = explode(" ", $columnName);
        $imlode = implode("", $explode);
        $this->QUERYSTRING = "SELECT $imlode , CASE ";
        return $this;
    }
    public function when_then(string $when, string $then, array $params)
    {
        $this->QUERYSTRING .= " WHEN $when THEN '$then' ";
        $this->QUERYPARAMS = array_merge($this->QUERYPARAMS, $params);
        return $this;
    }
    public function else_from(string $else, string $tableName, string $as = null)
    {
        $this->QUERYSTRING .= " ELSE '$else' END ";
        if (!is_null($as)) {
            $this->QUERYSTRING .= " AS $as ";
        }
        $this->QUERYSTRING .= " FROM $tableName ";
        return $this;
    }
    /*
    SELECT OrderID, Quantity,
        CASE
            WHEN Quantity > 30 THEN "The quantity is greater than 30"
            WHEN Quantity = 30 THEN "The quantity is 30"
            ELSE "The quantity is under 30"
        END
    FROM OrderDetails;
   */

    //* GET
    private function get()
    {
        if (count($this->QUERYPARAMS) == 0) {
            $this->QR = $this->PDO->query($this->QUERYSTRING);
        } else {
            $this->execute();
        }
    }
    public function get_all()
    {
        $this->get();
        $this->clean_query();
        return $this->QR->fetchAll();
    }
    public function get_one()
    {
        $this->get();
        $this->clean_query();
        return $this->QR->fetch();
    }

    public function get_column()
    {
        $this->get();
        $this->clean_query();
        return $this->QR->fetchColumn();
    }

    //* SET
    public function set()
    {
        try {
            $this->execute();
            $this->clean_query();
            return $this->QR->rowCount();
        } catch (Exception $exc) {
            exit($exc->getMessage());
        }
    }

    //* EXECUTE
    private function execute()
    {
        try {
            $this->QR = $this->PDO->prepare($this->QUERYSTRING);
            $this->QR->execute($this->QUERYPARAMS);
        } catch (Exception $exc) {
            exit($exc->getMessage());
        }
    }

    private function clean_query()
    {
        $this->QUERYSTRING = null;
        $this->QUERYPARAMS = [];
    }

    public function __destruct()
    {
        $this->PDO = null;
        $this->QR = null;
        $this->clean_query();
    }
}
