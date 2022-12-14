<?php
class Other
{
    private function query_lib(string $queryType, string $tableName, string $colName, array $params = null, string $filters = null, int $limit = null, string $orderBy = null, string $align = "ASC")
    {
        switch ($queryType) {
            case 'SELECT':
                $status = 0;
                $status += is_null($limit) ? 0 : 1;
                $status += is_null($orderBy) ? 0 : 2;
                $status += is_null($filters) ? 0 : 4;

                $queryStr = [
                    0 => "SELECT $colName FROM $tableName",
                    1 => "SELECT $colName FROM $tableName LIMIT $limit",
                    2 => "SELECT $colName FROM $tableName ORDER BY $orderBy $align",
                    3 => "SELECT $colName FROM $tableName ORDER BY $orderBy $align LIMIT $limit",
                    4 => "SELECT $colName FROM $tableName WHERE $filters",
                    5 => "SELECT $colName FROM $tableName WHERE $filters LIMIT $limit",
                    6 => "SELECT $colName FROM $tableName WHERE $filters ORDER BY $orderBy $align",
                    7 => "SELECT $colName FROM $tableName WHERE $filters ORDER BY $orderBy $align LIMIT $limit"
                ];
                echo $queryStr[0];
                if ($status >= 0 and $status <= 3) {
                    $this->QR = $this->PDO->query($queryStr[$status]);
                    break;
                } elseif ($status >= 4 and $status <= 7) {
                    $this->QR = $this->PDO->prepare($queryStr[$status]);
                }
                break;

            case 'INSERT':
                $this->QR = $this->PDO->prepare("INSERT INTO " . $tableName . " SET " . $colName);
                break;

            case 'UPDATE':
                $this->QR = $this->PDO->prepare("UPDATE " . $tableName . " SET " . $colName . " WHERE " . $filters);
                break;

            case 'DELETE':
                $this->QR = $this->PDO->prepare("DELETE FROM " . $tableName . " WHERE " . $filters);
                break;

            default:
                echo "Ge??ersiz talep";
                break;
        }

        $this->QR->execute($params);
    }

    public function select_all(string $tableName, string $filters = null, array $params = null, string $colName = "*")
    {
        try {
            $this->query_lib("SELECT", $tableName, $colName, $params, $filters);
        } catch (PDOException $exc) {
            exit($exc->getMessage());
        }
        return $this->QR->fetchAll();
    }

    public function select_all_limit(string $tableName, int $limit, string $filters = null, array $params = null, string $colName = "*")
    {
        try {
            $this->query_lib("SELECT", $tableName, $colName, $params, $filters, $limit);
        } catch (PDOException $exc) {
            exit($exc->getMessage());
        }
        return $this->QR->fetchAll();
    }
    public function select_all_order(string $tableName, string $orderBy, string $align = "ASC", string $filters = null, array $params = null, string $colName = "*")
    {
        try {
            $this->query_lib("SELECT", $tableName, $colName, $params, $filters, null, $orderBy, $align);
        } catch (PDOException $exc) {
            exit($exc->getMessage());
        }
        return $this->QR->fetchAll();
    }
    public function select_all_ol(string $tableName, string $orderBy, int $limit, string $align = "ASC", string $filters = null, array $params = null, string $colName = "*")
    {
        try {
            $this->query_lib("SELECT", $tableName, $colName, $params, $filters, $limit, $orderBy, $align);
        } catch (PDOException $exc) {
            exit($exc->getMessage());
        }
        return $this->QR->fetchAll();
    }

    public function select_row(string $tableName, string $filters, array $params, string $colName = "*")
    {
        try {
            $this->query_lib("SELECT", $tableName, $colName, $params, $filters);
        } catch (PDOException $exc) {
            exit($exc->getMessage());
        }

        return $this->QR->fetch();
    }

    public function select_col(string $tableName, string $filters, array $params, string $colName = "*")
    {
        try {
            $this->query_lib("SELECT", $tableName, $colName, $params, $filters);
        } catch (PDOException $exc) {
            exit($exc->getMessage());
        }

        return $this->QR->fetchColumn();
    }
}
<?php
namespace sifirdanphp\db;
class Database 
{
  private $MYSQL_HOST='localhost'; 
  private $MYSQL_USER='root'; // mysql kullan??c?? ad??n??z  
  private $MYSQL_PASS='';  // mysql ??ifreniz
  private $MYSQL_DB=''; //kendi database ad??n??z?? yaz??n
  private $CHARSET='UTF8';
  private $COLLATION='utf8_general_ci';
  private $pdo=null;
  private $stmt=null;

  private function ConnectDB(){
    //database ba??lant??s??
    $SQL="mysql:host=".$this->MYSQL_HOST.";dbname=".$this->MYSQL_DB; 
    try{
      $this->pdo=new \PDO($SQL,$this->MYSQL_USER,$this->MYSQL_PASS);
      $this->pdo->exec("SET NAMES '".$this->CHARSET."' COLLATE '".$this->COLLATION."'");
      $this->pdo->exec("SET CHARACTER SET '".$this->CHARSET."'");
      $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }catch(PDOException $e){
      die("PDO ile veritaban??na ula????lamad??".$e->getMessage());
    }
  }

  public function __construct(){ 
    //ba??lant??y?? a??
    $this->ConnectDB();
  }

  private function myQuery($query,$params=null){
    //di??er metodlardaki tekrarl?? verileri bitirmek i??in kullan??lan metod
    if(is_null($params)){
      $this->stmt=$this->pdo->query($query);
    }else{
      $this->stmt=$this->pdo->prepare($query);
      $this->stmt->execute($params);
    }
    return $this->stmt;
  }

  public function Update($query,$params=null){
    //kay??t g??ncellemek i??in
    try{
      return $this->myQuery($query,$params)->rowCount();
    }catch(PDOException $e){
    die($e->getMessage());
    }
  }

  public function Delete($query,$params=null){
    //kay??t Silmek i??in
      return $this->Update($query,$params);
  }
	
   public function Limit($query,$p1=1,$p2=null){
	   //limit kay??tlar??n?? pdo ile ??ekmek i??in
      $this->stmt=$this->pdo->prepare($query);
      $this->stmt->bindValue(1, $p1, \PDO::PARAM_INT);
      if(!is_null($p2))
      $this->stmt->bindValue(2, $p2, \PDO::PARAM_INT);
      
      $this->stmt->execute();
    return $this->stmt->fetchAll();
  }
  public function __destruct(){
    //ba??lant??y?? kapat
    $this->pdo=NULL;
  }

  public function CreateDB($query){ 
    //veritaban?? olu??turmak i??in
    $myDB=$this->pdo->query($query.' CHARACTER SET '.$this->CHARSET.' COLLATE '.$this->COLLATION);
    return $myDB;
 }

 public function TableOperations($query){ 
   //tablo operasyonlar?? i??in
   $myTable=$this->pdo->query($query);
   return $myTable;
 }

 public function Maintenance(){ 
   //tablolar??n bak??m?? i??in
  $myTable=$this->pdo->query("SHOW TABLES");
  $myTable->setFetchMode(\PDO::FETCH_NUM);
  if($myTable){
    foreach($myTable as $items){ 
    $check=$this->pdo->query("CHECK TABLE ".$items[0]);
    $analyze=$this->pdo->query("ANALYZE TABLE ".$items[0]);
    $repair=$this->pdo->query("REPAIR TABLE ".$items[0]);
    $optimize=$this->pdo->query("OPTIMIZE TABLE ".$items[0]);
      if($check == true && $analyze == true && $repair == true && $optimize == true){
        echo $items[0].' adl?? Tablonuzun bak??m?? yap??ld??<br>';
      }else{
        echo 'Bir hata olu??tu';
      }
    }
  }
}

}	
?>