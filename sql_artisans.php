<?php
/**
 * @author Sikander <zaidi8480@gmail.com>
 * @version 1.0
 */
class Sqlartisans {

	private $database_types = array("sqlite","sqlsrv","mssql","mysql","pg","odbc","oracle","fbd");
	private $host;
	private $database;
	private $user;
	private $password;
	private $port;
	private $database_type;
	private $root_mdb;
	private $sql;
	private $con;
	private $error_msg = "Error: Connection to database lost.";
	private $Username;
    private $Pass;
    private $oldpass;
    private $Email;

    /**
    * Connect To DataBase Server
    * 
    * @param string  $database_type Database Server Type
    * @param string  $host          Database Host
    * @param string  $database      Name Of the Database
    * @param string  $user          Username of the database
    * @param string  $password      Password of the Database
    * @param integer $port          Database Server Port Number
    */
	public function __construct($database_type,$host,$database,$user,$password,$port) {
		$this->database_type = strtolower($database_type);
		$this->host = $host;
		$this->database = $database;
		$this->user = $user;
		$this->password = $password;
		$this->port = $port;
	}

	/*
	|--------------------------------------------------------------------------
	|	Multi-Database Connection Method
	|--------------------------------------------------------------------------
	*/
	public function connect() {
		if(in_array($this->database_type, $this->database_types)) {
			try {
				switch($this->database_type) {
					case "mssql":
						$this->con = new PDO("mssql:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);
						break;
					case "sqlsrv":
						$this->con = new PDO("sqlsrv:server=".$this->host.";database=".$this->database, $this->user, $this->password);
						break;
					case "odbc":
						$this->con = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb)};Dbq=C:\accounts.mdb;Uid=".$this->user);
						break;
					case "oracle":
						$this->con = new PDO("OCI:dbname=".$this->database.";charset=UTF-8", $this->user, $this->password);
						break;
					case "fbd":
						$this->con = new PDO("firebird:dbname=".$this->host.":".$this->database, $this->user, $this->password);
						break;
					case "mysql":
						$this->con = (is_numeric($this->port)) ? new PDO("mysql:host=".$this->host.";port=".$this->port.";dbname=".$this->database, $this->user, $this->password) : new PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);
						break;
					case "sqlite":
						$this->con = new PDO("sqlite:".$this->host);
						break;
					case "pg":
						$this->con = (is_numeric($this->port)) ? new PDO("pgsql:dbname=".$this->database.";port=".$this->port.";host=".$this->host, $this->user, $this->password) : new PDO("pgsql:dbname=".$this->database.";host=".$this->host, $this->user, $this->password);
						break;
					default:
						$this->con = null;
						break;
				}

				$this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				return $this->con;
			} catch(PDOException $e) {
				$this->error_msg = "Error: ". $e->getMessage();
				return false;
			}
		} else {
			$this->error_msg = "Error Establishing a database connection.";
			return false;
		}
	}

	/**
    * Query and bind the Values
    * 
    * @param string $query   SQL Statement
    * @param string $binding Bind SQL Values to Variable
    */
	public function bindQuery($query, $binding) {
		if($this->con!=null) {
			$stmt = $this->con->prepare($query);
			$stmt->execute($binding);
			return ($stmt->rowCount() > 0) ? $stmt : false;
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

	/**
    * Get the the record from table, and limit the record
    * 
    * @param  string  $tableName Name Of the Table
    * @param  string  $con       Connection
    * @param  integer $limit     Limit The Records
    * @return boolean
    */
	public function get($tableName, $limit = 10) {
		if($this->con!=null){
			try {
				$sql_statement = "SELECT * FROM $tableName limit $limit";
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);
			} catch(PDOException $e){
				$this->error_msg = "Error: ". $e->getMessage();
				return false;
			}
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

    /**
    * Get the Record From Table and filter it with field $id
    * 
    * @param  string $table Table Name
    * @param  string $uk 	Column Name
    * @param  string $id    Row id
    * @return string
    */
	public function getById($table, $uk, $id) {
		if($this->con != null) {
	        $stmt = $this->con->prepare("SELECT * FROM $table WHERE $uk = :id LIMIT 1");
	        $stmt->bindParam(':id', $id);
	        $stmt->execute();
	        return $stmt;
    	} else {
    		$this->error_msg = "Error: Lost Connection to Database.";
    		return false;
    	}
	}

	/**
	 * Get the Records From Table Using URL
	 * 
	 * @param  string $table Table Name
	 * @param  string $uk    Column Name
	 * @param  string $url   URL id
	 */
	public function getByUrl($table, $uk , $url) {
		$id = $_GET[$url];
		if($this->con != null) {
	        $stmt = $this->con->prepare("SELECT * FROM $table WHERE $uk = :id LIMIT 1");
	        $stmt->bindParam(':id', $id);
	        $stmt->execute();
	        return $stmt;
    	} else {
    		$this->error_msg = "Error: Lost Connection to Database.";
    		return false;
    	}
	}
	
    /**
    * Execute SQL Queries
    * 
    * @param  string $sql_statement SQL Satatement
    * @return object
    */
	public function query($sql_statement) {
		$this->error_msg = "";
		if($this->con!=null){
			try {
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);
			} catch(PDOException $e){
				$this->error_msg = "Error: ". $e->getMessage();
				return false;
			}
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

    /**
    * Show All Rows From Table
    * 
    * @param  string $table Table Name
    * @return string
    */
	public function rows($table) {
		$this->error_msg = "";
		$this->sql="SELECT * FROM $table";
		if($this->con!=null) {
			try {
					$q = $this->con->query($this->sql);
					$column = array();
					foreach($q->fetch(PDO::FETCH_ASSOC) as $key=>$val) {
						$column[] = $key;
					}
					return $column;

				}	catch(PDOException $e) {
						$this->error_msg = "Error: ". $e->getMessage();
						return false;
			}
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

	/**
	 * Show All Databases From Server
	 * 
	 * @return string Databases Names
	 */
	public function showDB() {
		$this->error_msg = "";
		$sql_statement = "";
		$dbtype = $this->database_type;

		if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="odbc" || $dbtype=="sqlite") {
			$sql_statement = "SELECT name FROM sys.Databases;";
		}elseif($dbtype=="oracle") {
			$sql_statement = 'SELECT * FROM v$database;';
		}elseif($dbtype=="fbd") {
			$sql_statement = "";
		}elseif($dbtype=="mysql") {
			$sql_statement = "SHOW DATABASES;";
		}elseif($dbtype=="pg") {
			$sql_statement = "SELECT datname AS name FROM pg_database;";
		}

		if($this->con!=null) {
			try {
				$this->sql=$sql_statement;
				return $this->con->query($this->sql);
			} catch(PDOException $e) {
				$this->error_msg = "Error: ". $e->getMessage();
				return false;
			}
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

	/**
	 * Show Tables From Database
	 * 
	 * @param string $database Name of the Database
	 */
	public function showTables($database) {
        $this->error_msg = "";
        $complete = "";
        $sql_statement = "";
        $dbtype = $this->database_type;

        if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="ibm" || $dbtype=="dblib" || $dbtype=="odbc" || $dbtype=="sqlite2" || $dbtype=="sqlite3"){
            $sql_statement = "SELECT name FROM sysobjects WHERE xtype='U';";
        } elseif($dbtype=="oracle") {
            $sql_statement = "SELECT table_name FROM tabs;";
        } elseif($dbtype=="ifmx" || $dbtype=="fbd") {
            $sql_statement = 'SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE RDB$SYSTEM_FLAG = 0 AND RDB$VIEW_BLR IS NULL ORDER BY RDB$RELATION_NAME;';
        } elseif($dbtype=="mysql") {
            if($database!="") $complete=" FROM $database";
            $sql_statement = "SHOW tables ".$complete.";";
        } elseif($dbtype=="pg") {
            $sql_statement = "SELECT relname AS name FROM pg_stat_user_tables ORDER BY relname;";
        }

        if($this->con!=null) {
            try {
                $this->sql=$sql_statement;
                return $this->con->query($this->sql);
            } catch(PDOException $e) {
                $this->error_msg = "Error: ". $e->getMessage();
                return false;
            }
        } else {
            $this->error_msg = "Error: Lost Connection to Database.";
            return false;
        }
    }
    
    /**
     * Get the Latest Record From the Table
     * 
     * @param  string $table Name of the Table
     * @param  string $field Name of the field
     * @return string
     */
    public function getLatestId($table, $field) {
        $this->error_msg = "";
        $sql_statement = "";
        $dbtype = $this->database_type;

        if($dbtype=="sqlsrv" || $dbtype=="mssql" || $dbtype=="odbc") {
            $sql_statement = "SELECT TOP 1 ".$field." FROM ".$table." ORDER BY ".$field." DESC;";
        } elseif($dbtype=="oracle") {
            $sql_statement = "SELECT ".$field." FROM ".$table." WHERE ROWNUM<=1 ORDER BY ".$field." DESC;";
        } elseif($dbtype=="fbd") {
            $sql_statement = "SELECT FIRST 1 ".$field." FROM ".$table." ORDER BY ".$field." DESC;";
        } elseif($dbtype=="mysql" || $dbtype=="sqlite") {
            $sql_statement = "SELECT ".$field." FROM ".$table." ORDER BY ".$field." DESC LIMIT 1;";
        } elseif($dbtype=="pg") {
            $sql_statement = "SELECT ".$field." FROM ".$table." ORDER BY ".$field." DESC LIMIT 1 OFFSET 0;";
        }

        if($this->con!=null) {
            try {
                $sttmnt = $this->con->prepare($sql_statement);
                $sttmnt->execute();
                return $sttmnt->fetchColumn();
            } catch(PDOException $e) {
                $this->error_msg = "Error: ". $e->getMessage();
                return false;
            }
        } else {
            $this->error_msg = "Error: Lost Connection to Database.";
            return false;
        }
    }

    /**
     * Close Database Connection
     * 
     * @return bool
     */
    public function disconnect() {
		$this->error_msg = "";

		if($this->con) {
			$this->con = null;
			return true;
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

	/**
	 * Insert Data Into Table
	 * 
	 * @param  string $table Name of the Table
	 * @param  string $data  Data to Insert
	 * @return integer
	 */
	public function insert($table, $data) {
		$this->error_msg = "";
		if($this->con!=null) {
			try {
				$txt_fields = "";
				$txt_values = "";
				$data_column = explode(",", $data);
				for($x=0;$x<count($data_column);$x++){
					list($field, $value) = explode("=", $data_column[$x]);
					$txt_fields.= ($x==0) ? $field : ",".$field;
					$txt_values.= ($x==0) ? $value : ",".$value;
				}
				$this->con->exec("INSERT INTO ".$table." (".$txt_fields.") VALUES(".$txt_values.");");
				return $this->con->lastInsertId();
			} catch(PDOException $e) {
				$this->error_msg = "Error: ". $e->getMessage();
				return false;
			}
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

	/**
	 * Update the Records in the Table
	 * 
	 * @param  string $table     Name of the Table
	 * @param  string $data      Data to Update
	 * @param  string $condition 
	 */
	public function update($table, $data, $condition="") {
		$this->error_msg = "";
		if($this->con!=null) {
			try {
				return (trim($condition)!="") ? $this->con->exec("UPDATE ".$table." SET ".$data." WHERE ".$condition.";") : $this->con->exec("UPDATE ".$table." SET ".$data.";");
			} catch(PDOException $e) {
				$this->error_msg = "Error: ". $e->getMessage();
				return false;
			}
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

	/**
	 * Delete Data from the Table
	 * @param  string $table     Name of the Table
	 * @param  string $condition
	 */
	public function delete($table, $condition="") {
		$this->error_msg = "";
		if($this->con!=null) {
			try {
				return (trim($condition)!="") ? $this->con->exec("DELETE FROM ".$table." WHERE ".$condition.";") : $this->con->exec("DELETE FROM ".$table.";");
			} catch(PDOException $e) {
				$this->error_msg = "Error: ". $e->getMessage();
				return false;
			}
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

	/*
	|--------------------------------------------------------------------------
	|	User Registration
	|--------------------------------------------------------------------------
	|
	*/
    
    /**
     * Set The UserName (Must be at least 3 charecters)
     * 
     * @param string $username Enter UserName
     * @return string UserName
     */
	function setUsername($username)
    {
        return $this->Username = htmlspecialchars($username);
    }
    
    /**
     * Get the UserName
     * 
     * @return string UserName
     */
	function getUsername()
    {
        return $this->Username;
    }
    
    /**
     * Set User Password (Must be at least 5 characters)
     * 
     * @param string $password User Password
     * @return string Password
     */
    function SetPassword($password)
    {
        return $this->Password = hash('sha256', $password);
    }
    
    /**
     * Set User Email Address
     * 
     * @param  string $email User Email
     * @return string Email
     */
    function SetEmail($email)
    {
        return $this->Email = htmlspecialchars($email);
    }

    /**
     * Get User Email Address
     * 
     * @return string Email
     */
    function GetEmail()
    {
        return $this->Email;
    }

    /**
     * Validate User Credentials
     * 
     * @return string Error
     */
    function validate()
    {
        $errors =  array();
        
        // username must be at list 3 charecters
        if((strlen($this->Username)) < 3 )
        {
            $errors[] = "Username must be at list 3 characters";
        }
        // end username check
        // valid mail
        
        if(false === filter_var($this->Email, FILTER_VALIDATE_EMAIL))
        {
            $errors[] = "Email not valid";
        } 
        
        if((strlen($this->Password)) < 5 )
        {
            $errors[] = "Password must be at list 5 characters";
        }
        
        return $errors;   
    }

    /**
     * Create the User
     * 
     * @return true if Everthing is Good;
     */
    function createUser() {
    	if($this->con!=null) {
	        $error = $this->Validate();
	        if(count($error) > 0 )
	        {
	            return $error;    
	        }
	        else {
	        $stmt = $this->con->prepare("INSERT INTO users (username, password, email) VALUES (:username,    
	        :password, :email)");
	        
	        $stmt->bindParam(':username', $this->Username);
	        $stmt->bindParam(':password', $this->Password);
	        $stmt->bindParam(':email', $this->Email);
	        //$stmt->bindParam(':regdate', time());
	        $stmt->execute();
	        $arr = array();
	        $arr = $stmt->errorInfo();
	        return $arr;
	        }
    	} else {
    		$this->error_msg = "Error: Lost Connection to Database.";
			return false;
    	}
    }
    
    /**
     * Login in the User
     * 
     * @return boolean
     */
	function login() {
		if($this->con!=null) {
			$stmt = $this->con->prepare("SELECT username, password FROM users
		    WHERE username = :username AND password =  :password
		    ");
		    $stmt->bindParam(':username', $this->Username);
		    $stmt->bindParam(':password', $this->Password);
		    $stmt->execute();
		    
		    if($stmt->fetchColumn() === $this->Username)
		    {
		    	$_SESSION['username'] = $this->Username;     
		    }
		    else
		    {
		        return false;
		    }
		} else {
			$this->error_msg = "Error: Lost Connection to Database.";
			return false;
		}
	}

    /**
     * Logout the User
     */
	function logout() {
        unset($_SESSION['username']);
        $_SESSION['username'] = array();
    }

    /**
     * Change the Email Address if the User is logged in
     * 
     * @return return True on Succsess
     */
	function changeMail() {
		if($this->con!=null) {
	         $EmailPattren = '/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])' .
	         '(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i';
	        
	        $IsEmailValid = preg_match($EmailPattren, $this->Email);
	        if($IsEmailValid > 0) {
	            $stmt = $this->con->prepare("UPDATE users 
	            SET email = :email WHERE username = :username
	            ");
	            $stmt->bindParam(':email', $this->Email);
	            $stmt->bindParam(':username', $_SESSION['username']);
	            $stmt->execute();
	            return true;
	        } else {
	            return false;
	        }
	    } else {
	    	$this->error_msg = "Error: Lost Connection to Database.";
			return false;
	    }
    }

    /**
     * User Current Password
     * 
     * @param string $oldpass
     * @return string
     */
    function oldPass($oldpass) {
        return $this->oldpass = sha1($oldpass);
    }

    /**
     * Change The User Password if the User is logged in
     */
    function changePass() {
    	if($this->con!=null){
	        $stmt = $this->con->prepare("UPDATE users 
	        SET password = :password  WHERE username = :username AND password = :oldpass
	        ");
	        $stmt->bindParam(':password', $this->Password);
	        $stmt->bindParam(':oldpass', $this->oldpass);
	        $stmt->bindParam(':username', $_SESSION['username']);
	        $stmt->execute();
    	} else {
    		$this->error_msg = "Error: Lost Connection to Database.";
			return false;	
    	}
    }
}