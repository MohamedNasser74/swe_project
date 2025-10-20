<?php
/**
 * Database Class using PDO
 */

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        
        // Set options
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        ];

        // Create PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            
            // Show helpful error message
            $errorMsg = "<h2>Database Connection Error</h2>";
            $errorMsg .= "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            $errorMsg .= "<h3>Possible Solutions:</h3>";
            $errorMsg .= "<ol>";
            $errorMsg .= "<li><strong>Start MySQL:</strong> Open XAMPP Control Panel and start MySQL</li>";
            $errorMsg .= "<li><strong>Check Database:</strong> Ensure database '" . $this->dbname . "' exists</li>";
            $errorMsg .= "<li><strong>Import Schema:</strong> Import database/schema.sql via phpMyAdmin</li>";
            $errorMsg .= "<li><strong>Check Credentials:</strong> Verify DB_USER and DB_PASS in app/config/config.php</li>";
            $errorMsg .= "</ol>";
            $errorMsg .= "<p><a href='" . APP_URL . "/diagnostic.php'>Run Diagnostic Tool</a></p>";
            
            die($errorMsg);
        }
    }

    // Prepare statement with query
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute()
    {
        return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    // Get last insert ID
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    // Begin transaction
    public function beginTransaction()
    {
        return $this->dbh->beginTransaction();
    }

    // Commit transaction
    public function commit()
    {
        return $this->dbh->commit();
    }

    // Rollback transaction
    public function rollback()
    {
        return $this->dbh->rollback();
    }
}
?>