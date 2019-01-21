<?php
class DBConfig {

    var $host;
    var $user;
    var $pass;
    var $db;
    var $db_link;
    var $conn = false;
    var $persistant = false;
    
    public $error = false;

    public function config(){ // class config
        $this->error = true;
        $this->persistant = false;
    }
    
    function open($host='localhost',$user='root',$pass='pass',$db='database',$charset='utf8'){ // connection function
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        
        // Establish the connection.
        $this->db_link = mysqli_connect($this->host, $this->user, $this->pass);

		mysqli_set_charset($this->db_link, $charset);

        if (!$this->db_link) {
            if ($this->error) {
                $this->error($type=1);
            }
            return false;
        }
        else {
			if (empty($db)) {
				if ($this->error) {
					$this->error($type=2);
				}
			}
			else {
				$db = mysqli_select_db($this->db_link, $this->db); // select db
				if (!$db) {
					if ($this->error) {
						$this->error($type=2);
					}
				return false;
				}
				$this -> conn = true;
			}
			
            return $this->db_link;
        }
    }

    function close() { // close connection
        if ($this -> conn){ // check connection
                mysqli_close($this->db_link);
		$this -> conn = false;
        }
        else {
            if ($this->error) {
                return $this->error($type=4);
            }
        }
    }
    
    public function error($type=''){ //Choose error type
        if (empty($type)) {
            return false;
        }
        else {
            if ($type==1)
                echo "<strong>Database could not connect</strong> ";
            else if ($type==2)
                echo "<strong>mysql error</strong> " . mysqli_error($this->db_link);
            else if ($type==3)
                echo "<strong>error </strong>, Proses has been stopped";
            else
                echo "<strong>error </strong>, no connection !!!";
        }
    }

	public function _mysql_real_escape_string_($value) {
		return mysqli_real_escape_string($this->db_link,$value);
	}

	public function _mysql_query($q){
		return mysqli_query($this->db_link, $q);
	}

	public function _mysql_error_(){
		return mysqli_error($this->db_link);
	}
}

$_GLOBAL_DB = new DBConfig();

function mysql_open_($json_data, $id = 'ubuntu', $pass = 'gruut', $db = 'tracker'){
	global $_GLOBAL_DB;
	$_GLOBAL_DB->config();
	$_GLOBAL_DB->open('localhost', $id, $pass, $db ,'utf8');

	foreach ($json_data as $key => $value)
		$json_data[$key] = mysql_real_escape_string_($value);  
	
	return $json_data;
}

function mysql_real_escape_string_($value){
	global $_GLOBAL_DB;
	return $_GLOBAL_DB->_mysql_real_escape_string_($value);
}

function mysql_query_($q){
	global $_GLOBAL_DB;
	return $_GLOBAL_DB->_mysql_query($q);
}

function mysql_error_(){
	global $_GLOBAL_DB;
	return $_GLOBAL_DB->_mysql_error_();
}

function mysql_close_(){
	global $_GLOBAL_DB;
	$_GLOBAL_DB->close();
}

function mysql_insert_($tbl_name, $toAdd){

   $fields = implode(array_keys($toAdd), ',');
   $values = "'".implode((array_values($toAdd)), "','")."'"; # better

   $q = 'INSERT INTO `'.$tbl_name.'` ('.$fields.') VALUES ('. $values.')';
   $res = mysql_query_($q);

   if(!$res){
	return $q;
   }

   return true;
   
   //-- Example of usage
   //$tToAdd = array('id'=>3, 'name'=>'Yo', 'salary' => 5000);
   //insertIntoDB('myTable', $tToAdd)
}

function mysql_read_all_($tbl_name){
	$q = 'SELECT * FROM `'.$tbl_name.'`';
	$res = mysql_query_($q) OR die(mysql_error_());

	if(!$res || mysqli_num_rows($res) == 0){
		return false;
	}

	$return_array = array();
	while($row = mysqli_fetch_assoc($res)){
		$return_array[] = $row;
	}
	return $return_array;
}

function mysql_read_($tbl_name, $toSearch){
	$where_condition = '';
	foreach ($toSearch as $key => $value) {
		$where_condition .= " `" . $key . "` = '" . $value . "'";
	}
	
	$q = 'SELECT * FROM `' . $tbl_name . '` WHERE' . $where_condition;
	
	$res = mysql_query_($q) OR die(mysql_error_());
	
	if (!$res || mysqli_num_rows($res) == 0) {
		return false;
	}
	
	$return_array = array();
	
	while ($row = mysqli_fetch_assoc($res)) {
		$return_array[] = $row;
	}
	
	return $return_array;
}

function mysql_read_urecord_($tbl_name, $key, $value){

	$q = 'SELECT * FROM `' . $tbl_name . '` WHERE' . " `" . $key . "` <=> '" . $value . "'";
	
	$res = mysql_query_($q) OR die(mysql_error_());
	
	if (!$res || mysqli_num_rows($res) == 0) {
		return false;
	}
	
	return mysqli_fetch_assoc($res);
}

function mysql_update_urecord_($tbl_name,$attr_name,$attr_value,$unique_key,$unique_value){
	mysql_query_("UPDATE " . $tbl_name . " SET " . $attr_name . " = '" . $attr_value . "' WHERE " . $unique_key  . " = '" . $unique_value . "'");
}

function mysql_update_urecordm_($tbl_name, $toUpdate, $ukey, $uvalue){

	$QUERY = "UPDATE " . $tbl_name . " SET ";
	
	foreach ($toUpdate as $key => $value) {
		$QUERY .= "`" . $key . "` = '" . $value . "', ";
	}
	
	$QUERY = substr($QUERY, 0, strlen($QUERY) - 2); // 뒤의 (, ) 삭제
		
	$QUERY .= " WHERE `" . $ukey  . "` <=> '" . $uvalue . "'";

	mysql_query_($QUERY);

	return true;
}
