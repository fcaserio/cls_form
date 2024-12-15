<?
if (function_exists("sqlsrv_connect")) {
	class connect {
		function __construct($db = "") {
			include "connect.cfg.php";
			if (!$this->connect = sqlsrv_connect($server, array("Database"=>$db, "UID"=>$user_db, "PWD"=>$pass_db))) {
				foreach (sqlsrv_errors(SQLSRV_ERR_ERRORS) as $val) {
					echo "<div class=\"erro\"><div>Erro " . $val["code"] . "</div><div>" . $val["message"] . "</div></div>\n";
				}
				die ("Não foi possível conectar-se ao banco de dados ($user_db@$db on $server)");
			}
		}
		/* function enableOptions() {
			$this->query("SET CONCAT_NULL_YIELDS_NULL OFF", "NONQUERY", false);
		} */
		function setTimeLimit($t) {
			$this->t = $t;
		}
		function query($qry, $method="", $ck_log="auto") {
			// $ck_log true/false/(str)auto
			if ($method == "NONQUERY" && strlen($qry) > 65536 && strpos($qry,"@") === false) {
				$prefix = "";
				foreach (preg_split("/;[\r|\n]{1,2}(insert|update|delete)+/i", $qry, 0, PREG_SPLIT_DELIM_CAPTURE) as $part) {
					if (in_array(strtolower($part), array("insert","update","delete"))) {
						$prefix = $part;
					} else if (trim($part) != "") {
						$part = $prefix . $part;
						$this->query(trim($part), "NONQUERY", false);
						$prefix = "";
					}
				}
				if ($ck_log !== false) $this->log($qry);
				return false;
			}
			$ck_return = substr($qry, 0, 6) == "SELECT" || $method == "QUERY";
			/* Disabled 26/11/2019
			if ($ck_return && strpos($qry, "SCOPE_IDENTITY()") === false) 
				$this->query("SET CONCAT_NULL_YIELDS_NULL OFF", "NONQUERY", false);
			else if (substr($qry,0,6) == "DELETE") 
				$this->query("SET CONCAT_NULL_YIELDS_NULL ON", "NONQUERY", false);
			*/
			if (substr($qry, 0, 4) == "EXEC") sqlsrv_configure("WarningsReturnAsErrors", 0);
			$params = [];
			$option = [];
			if (!empty($this->t)) $option["QueryTimeout"] = $this->t;
			if ($stmt = sqlsrv_query($this->connect, $qry, $params, $option)) {
				if ($ck_return) {
					$i = 0;
					$res = array();
					while (1 == 1) {
						while ($result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
							foreach ($result as $field => $val) {
								$res[$i][$field] = $val;
							}
							$i++;
						}
						if (!sqlsrv_next_result($stmt)) break;
					}
				} else {
					if ($ck_log == "auto") $ck_log = true;
				}
			}
			if (($errors = sqlsrv_errors(SQLSRV_ERR_ERRORS)) != null) {
				if (function_exists("buildErrReport")) {
					if (is_array($errors)) {
						$str = "";
						foreach ($errors as $part) {
							$str .= $part["message"] . "\n";
						}
						$errors = $str;
					}
					if (isset($_SESSION["id_verpro"])) {
						buildErrReport("SQL", $errors, $qry);
					} else {
						echo $errors;
					}
					if (!$ck_return) exit();
				} else { // not on user interface
					foreach ($errors as $val) {
						echo "Erro " . $val["code"] . ": " . $val["message"] . "\n";
					}
				}
			}
			if ($ck_log === true) $this->log($qry);
			if (isset($res)) {
				return $res;
			} else if (isset($msg) && $msg != "") {
				return $msg;
			} else {
				return array();
			}
		}
		function log($qry) {
			if (isset($_SESSION["user_verpro"])) {
				$user = $_SESSION["user_verpro"];
			} else {
				$user = "USUARIO NÃO LOGADO";
			}
			// log
			$op = strtoupper(substr(trim($qry), 0, 6));
			if ($op == "UPDATE") {
				$initable = 7;
			} else {
				$initable = 12;
			}
			$universo = "abcdefghijklmnopqrstuvwxyz_";
			for ($i=$initable; $i<strlen($qry); $i++) {
				$ck = 0;
				for ($j=0; $j<strlen($universo); $j++) {
					if (substr($qry, $i, 1) == substr($universo, $j, 1)) $ck = 1;
				}
				if ($ck == 0) break;
			}
			$endtable = $i; 
			$table = substr($qry, $initable, $endtable-$initable);
			if ($table != "log_session") {
				if (count($_POST) > 2000) {
					$post_str = "too long: " . count($_POST);
				} else {
					$info = $_POST;
					if (!empty($_FILES)) $info["_FILES"] = $_FILES;
					$post_str = serialize($info);
				}
				$qry_log = "INSERT INTO logs (usuario, tabela, operacao, query, post, script_name, query_string, user_agent, remote_addr, data)
						VALUES ('$user', '$table', '$op', '".str_replace("'","''",$qry)."', '" . str_replace("'","''",$post_str) . "', '" . $_SERVER["SCRIPT_NAME"] . "', '" . (isset($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : "")."', '" . (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "") . "', '" . (isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "") . "', GETDATE())";
				//echo "<pre>$qry_log</pre>";
				sqlsrv_query($this->connect, $qry_log);
			}
		}
		function close() {
			sqlsrv_close($this->connect);
		}
	}
} else if (function_exists("mssql_connect")) {
	class connect {
		function __construct($db = "") {
			include "connect.cfg.php";
			$this->connect = mssql_connect("$server","$user_db","$pass_db") or die ("Não foi possível conectar-se ao banco de dados");
			$seleciona = mssql_select_db($db) or die ("Base de dados <b>$db</b> não encontrada");
		}
		function enableOptions() {
			$qry = "SET ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON";
			$this->query($qry, "NONQUERY", false);
			// SET NUMERIC_ROUNDABORT OFF
			// SET ARITHABORT ON
		}
		function query($qry, $method="", $ck_log="auto") {
			// $ck_log true/false/(str)auto
			if (substr($qry, 0, 6) == "SELECT" || $method == "QUERY") {
				if ($ck_log === true) $this->enableOptions(); // On Linux installation INSERT/UPDATE on tables with index on computed column (ex: nf) require ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON
				$resultado = mssql_query($qry) or (function_exists("buildErrReport") ? buildErrReport("SQL", mssql_get_last_message(), $qry) : print($qry));
				$i = 0;
				$res = array();
				while ($result = mssql_fetch_array($resultado, MSSQL_ASSOC)) {
					$vet = array_keys($result);
					for ($j=0; $j<count($vet); $j++) {
						$field = $vet[$j];
						$res[$i][$field] = (isset($result[$field]) ?  trim($result[$field]) : "");
					}
					$i++;
				}
			} else {
				if ($ck_log !== false) $this->enableOptions(); // On Linux installation INSERT/UPDATE on tables with index on computed column (ex: nf) require ANSI_NULLS, QUOTED_IDENTIFIER, CONCAT_NULL_YIELDS_NULL, ANSI_WARNINGS, ANSI_PADDING ON
				if (!mssql_query($qry)) {
					if (function_exists("buildErrReport")) {
						buildErrReport("SQL", mssql_get_last_message(), $qry);
						exit;
					} else {
						print($qry);
					}
				}
				if ($ck_log == "auto") $ck_log = true;
			}
			if ($ck_log === true) {
				if (isset($_SESSION["user_verpro"])) {
					$user = $_SESSION["user_verpro"];
				} else {
					$user = "USUARIO NÃO LOGADO";
				}
				// log
				$op = strtoupper(substr(trim($qry), 0, 6));
				if ($op == "UPDATE") {
					$initable = 7;
				} else {
					$initable = 12;
				}
				$universo = "abcdefghijklmnopqrstuvwxyz_";
				for ($i=$initable; $i<strlen($qry); $i++) {
					$ck = 0;
					for ($j=0; $j<strlen($universo); $j++) {
						if (substr($qry, $i, 1) == substr($universo, $j, 1)) $ck = 1;
					}
					if ($ck == 0) break;
				}
				$endtable = $i; 
				$table = substr($qry, $initable, $endtable-$initable);
				if ($table != "log_session") {
					$qry_log = "INSERT INTO logs (usuario, tabela, operacao, query, post, script_name, query_string, user_agent, remote_addr, data)
							VALUES ('$user', '$table', '$op', '".str_replace("'","''",$qry)."', '".str_replace("'","''",serialize($_POST))."', '".$_SERVER["SCRIPT_NAME"]."', '".(isset($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : "")."', '".(isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "")."', '".(isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "")."', GETDATE())";
					//echo "<pre>$qry_log</pre>";
					mssql_query($qry_log);
				}
			}
			//$t[3] = getmicrotime(); // start
			//echo "connect:".($t[3]-$t[2])."; ";
			//echo "select db:".($t[2]-$t[1])."; ";
			//echo "query:".($t[1]-$t[0])."<br>";
			if (isset($res)) {
				return $res;
			} else if (isset($msg) && $msg != "") {
				return $msg;
			} else {
				return array();
			}
		}
		function close() {
			mssql_close($this->connect);
		}
	}
} else {
	echo "<link rel=\"STYLESHEET\" type=\"text/css\" href=\"../css/admin.css\">\n";
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		$bib = "sqlsrv";
	else
		$bib = "mssql";
	exit("<div class=erro>Biblioteca <b>$bib</b> não encontrada</div>");
}
// new connection query
function nc_query($qry, $method="", $db = "") {
	$connect = new connect($db);
	$res = $connect->query($qry, $method);
	$connect->close();
	return $res;
}
?>