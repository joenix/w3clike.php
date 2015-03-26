<?php
/**
 * PDO Oracle extend method
 +-----------------------------------------
 * @category    Pt
 * @package     db
 * @author      page7 <zhounan0120@gmail.com>
 * @version     $Id$
 */
class db_pdo_oci extends db_pdo
{

    public function __construct($config = array()){}


    /**
     * get last insert id
     +-----------------------------------------
     * @access public
     * @return void
     */
    public function getLastInsertId()
    {
        $query = $this -> _statement -> queryString;
        if( preg_match("/^INSERT[\t\n ]+INTO[\t\n ]+\"?([a-z0-9\_\-]+)\"?/is", $query, $tablename) )
        {
            // Gets this table's last sequence value
            $query = 'SELECT "' . $tablename[1] . '_ID".currval AS "last_value" FROM "' . $tablename[1] . '"';
            $temp = $this -> _pdo -> prepare($query);
            $temp -> @execute();

            if($temp)
            {
                $rs = $temp -> fetch(PDO::FETCH_ASSOC);
                return ( $rs ) ? $rs['last_value'] : false;
            }

            return $this -> _statement -> rowCount();
        }
        return false;
    }



    /**
     * get columns
     +-----------------------------------------
     * @access public
     * @param string $table
     * @param string $prefix
     * @return void
     */
    public function getColumns($table, $prefix='')
    {
        $tablename = ($prefix ? $prefix : $this -> prefix).$table;
        $result = $this -> query("DESCRIBE `{$tablename}`");

        $columns = array();
        foreach ($result as $key => $val)
        {
            $val = array_change_key_case($val);

            // debug. I dont check in mysql for low version
            $name = $val['field'];

            $columns[$name]    =   array(
                'name'    => $name,
                'type'    => $this -> _columnsType($val['type']),
                'notnull' => (bool) ($val['null'] == 'NO'),
                'primary' => (bool) ($val['key'] == 'PRI'),
                'autoinc' => (bool) ($val['extra'] == 'auto_increment'),
            );
        }

        return $columns;
    }



    /**
     * get column type
     +-----------------------------------------
     * @access public
     * @param mixed $type
     * @return void
     */
    private function _columnsType($type)
    {
        if ( strpos($type, 'int') !== false || strpos($type, 'float') !== false || strpos($type, 'double') !== false )
            return PDO::PARAM_INT;
        else if ( strpos($type, 'bool') !== false )
            return PDO::PARAM_BOOL;
        else if ( strpos($type, 'blob') !== false )
            return PDO::PARAM_LOB;

        return PDO::PARAM_STR;
    }



    /**
     * get tables info
     +-----------------------------------------
     * @access public
     * @return void
     */
    public function getTables()
    {
        $result = $this -> query('SHOW TABLES');
        $tables = array();
        foreach ($result as $key => $val)
        {
            $tables[$key] = current($val);
        }
        return $tables;
    }


}

?>