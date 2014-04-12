<?php
/*
 * known bugs: 
 * change insertion query to PDOs
 * need to disable (or not create) input for ID field if field is auto-incremen--partly solved
 * 
 * 
 */
class TableLoaderUpdater {

    private $fields;
    public $table;
    public $IDField; 
    public $dbName;
    public $dbUser;
    public $password;
    public $dbConn;
    public $makeTable;
    public $newTableID;
    public $addColumnHeaders;
    public $dbServer;
   
    function __construct($IDField = "", $tblName = "", $dbName = "", $dbServer = "", $dbUser = "", $dpPassword = "", $createNewTable = false, $newTableID = "", $addColumnHeaders = false) {
        $this->IDField = $IDField;
        $this->table = $tblName;
        $this->dbName = $dbName;
        $this->dbServer = $dbServer;
        $this->dbUser = $dbUser;
        $this->password = $dpPassword;
        $this->dbConn = mysqli_connect($this->dbServer, $this->dbUser, $this->password, $this->dbName);
        $this->makeTable = $createNewTable;
        $this->addColumnHeaders = $addColumnHeaders;
        $this->newTableID = $newTableID;
        $this->fields = $this->getFieldNames();
    }

    function fillTable() {
        if ($this->makeTable)
            echo "<table id='$this->newTableID' class='editable' id-column='$this->IDField'>";
        if ($this->addColumnHeaders) {
            echo '<tr>';
            foreach ($this->fields as $fieldName) {
                echo '<th>' . $fieldName . '</th>';
            }
            echo '</tr>';
        }
        $fillQuery = "SELECT * FROM " . $this->table;
        $fillResult = mysqli_query($this->dbConn, $fillQuery, MYSQLI_STORE_RESULT);
        //echo $fillResult;
        while ($row = mysqli_fetch_array($fillResult, MYSQLI_ASSOC)) {
            echo '<tr>';
            foreach ($row as $field) {
                echo '<td>';
                echo $field;
                echo '</td>';
            }
            echo '</tr>';
        }
        if ($this->makeTable)
            echo '</table>';
    }

    function processRequest() {
        $this->deleteRows();
        $this->updateTable();
        $this->addRows();
    }

    function deleteRows() {

        if (isset($_POST['deleted'])) {
            foreach ($_POST['deleted'] as $rowID) {
                $deleteQuery .= "DELETE FROM $this->table WHERE $this->IDField = '$rowID';";
            }
            mysqli_multi_query($this->dbConn, $deleteQuery);
        }
    }

    function updateTable() { // current implementation is such that all fields are updated in every record that has input 'edited'. 
                             //Better would be to only update those fields that were actually changed, through javascript labeling of the specific fields
        if (isset($_POST['edited'])) {
            
            $idIndex = array_search($this->IDField, $this->fields);
            
            
                       
            foreach ($_POST['edited'] as $rec) {
                $query = "UPDATE $this->table SET ";
                for($i=0;$i<count($rec);$i++){
                    if($i!=$idIndex){
                        if($i!=  count($rec)-1)
                            $query .= $this->fields[$i]."='".$rec[$i]."', ";
                        else
                            $query .= $this->fields[$i]."='".$rec[$i]."' ";
                    }
                }
             
                $query .= " WHERE $this->IDField = '$rec[$idIndex]'";
                echo var_dump($query);
          //      $query =  "UPDATE mezuza_orders SET Name='sssssssss', ProductID='ad', Quantity='22', OrderDate='', TargetDate='' WHERE OrderID = '90'";
                mysqli_query($this->dbConn, $query);
            }
            
        }
    }

    function addRows() { // need to take into account possibility of auto-increment, or not
        if (isset($_POST['newRow'])) {
            $IdFieldObj = mysqli_fetch_field(mysqli_query($this->dbConn, "SELECT $this->IDField FROM $this->dbName"));
            $IdFieldType = $IdFieldObj->flags;
            $fieldArray = $this->fields;
            foreach ($_POST['newRow'] as $addedRow) {

                $table = $this->table;

                $addedFields = "";
                $addedValues = "";
           
                for ($i = 0; $i < count($fieldArray); $i++) {
                    if($IdFieldType==512){  // if table ID is auto-increment
                        if ($fieldArray[$i] != $this->IDField) { 
                            $addedFields = $addedFields . $fieldArray[$i];
                            if ($i != count($fieldArray) - 1)
                                $addedFields = $addedFields . ", ";
                            $addedValues = $addedValues . "'" . $addedRow[$i] . "'"; // bug: all values inserted as strings. acceptable? maybe...
                            if ($i != count($fieldArray) - 1)
                                $addedValues = $addedValues . ", ";
                        }
                    }else{
                        $addedFields = $addedFields . $fieldArray[$i];
                        if ($i != count($fieldArray) - 1)
                            $addedFields = $addedFields . ", ";
                        $addedValues = $addedValues . "'" . $addedRow[$i] . "'"; // bug: all values inserted as strings. acceptable? maybe...
                        if ($i != count($fieldArray) - 1)
                            $addedValues = $addedValues . ", ";
                    }
                }
                //    echo var_dump($addedFields);
                $addedQuery = "INSERT INTO $table (" . $addedFields . ") VALUES (" . $addedValues . ");";
              //  echo $addedQuery;
                mysqli_query($this->dbConn, $addedQuery);
            }
        }
    }

    function getFieldNames() {
        if(!empty($this->dbConn)){
            $fieldNames = array();
            $fieldObjs = mysqli_fetch_fields(mysqli_query($this->dbConn, "SELECT * FROM $this->table"));
            foreach ($fieldObjs as $field) {
                $fieldNames[] = $field->name;
            }
            return $fieldNames;
         }
    }

}

?>
