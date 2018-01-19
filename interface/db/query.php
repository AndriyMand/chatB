<?php

    function selectQuery($table, $fields, $filter = '', $order = '', $limit = null)
    {
        if ($fields !== '*' && is_array($fields)) {
            $cols = '';
            
            foreach($fields as $key => $value) {
                $cols .= is_numeric($key) ? "`$value`," : "`$value` as `$key`,";
            }
            
            $fields = trim($cols, ',');
        }

        $sql = "SELECT $fields FROM `$table` ";

        if ($filter) {
            $sql .= " WHERE $filter ";
        }

        if ($order) {
            $sql .= " order by $order ";
        }

        if ($limit) {
            $limit = (int)$limit;
            $sql .= " limit $limit";
        }

        return $sql;
    }

    function selectAll($table, $fields, $filter = '', $order = '', $limit = '')
    {
        return selectQuery($table, $fields, $filter, $order, $limit);
    }

    function update($table, $fields, $filter, $order = '', $limit = '')
    {
        $sql = "UPDATE $table
                SET $fields
                WHERE $filter";
        
        if ($order) {
            $sql .= " order by $order ";
        }

        if ($limit) {
            $limit = (int)$limit;
            $sql .= " limit $limit";
        }
        return $sql . ";";
    }
    
    function delete($table, $filter)
    {
        return "DELETE FROM $table
                WHERE $filter";
    }

    function selectRow($table, $fields, $filter = '', $order = '')
    {
        return ($res = selectQuery($table, $fields, $filter, $order, 1)) ? $res : false;
    }

    function insert($table, $value = '')
    {
        if (!empty($value)) {
            $fields = implode('`,`', array_keys($value));
            $values = implode("','", $value);

            $sql = "INSERT INTO `$table` (`$fields`) VALUES ('$values')";
        } else {
            $sql = "INSERT INTO `$table` VALUES ()";
        }
        return $sql;
    }