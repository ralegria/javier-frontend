<?php
class ORM extends DB{
    protected $queryType = 'select';
    protected $joins = '';
    protected $cols = '';
    protected $conditions = '';
    protected $limit = 0;
    protected $offset = 0;
    protected $groupBy = '';
    protected $orderBy = '';
    protected $query;
    protected $relationships = null;

    public function findAll(){
        $stmt = $this->getConn()->prepare("SELECT * FROM " . $this::getTable());
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $collection = array();
        if(!is_null($result)){
            foreach($result as $k){
                $this->mapping($k, $this);
                $collection[] = $this;
            }
        }
        return $collection;
    }

    /**
    * Find by id
    * @param int $id
    * @return Object
    */
    public function findById($id){
        $stmt = $this->getConn()->prepare("SELECT * FROM ".$this::getTable()." WHERE ".$this::getPk()." = :id ");
        $stmt->execute(array(":id" => $id));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!is_null($result)){
            if(isset($result[0])){
                $this->mapping($result[0], $this);
            }
        }
        return $this;
    }

    /**
    * Convertir tabla a objeto
    * @param Array $data
    * @param Object $obj
    * @return ORM
    */
    private function mapping($data, $obj){
        foreach($this->getColumns($obj) as $prop){
            $method = 'set' . ucfirst($prop->getName());
            $obj->$method($data[$prop->getName()]);
        }
        return $obj;
    }

    /**
    * Obtener propiedades de un objeto
    * @param Object $obj
    * @return Array properties
    */
    private function getColumns($obj, $list = false){
        $reflection = new ReflectionClass($obj);
        if(!$list){
            return $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
        }else{
            $cols = array();
            foreach($this->getColumns($obj) as $prop){
                $cols[] = $this::getTable() . '.' . $prop->getName();
            }
            return implode(',', $cols);
        }
    }

    /**
    ***********************************************************************************************
    **************************************** QUERY BUILDER ****************************************
    ***********************************************************************************************
    **/
    public function createQueryBuilder(){
        $this->relationships = null;
        $this->cols = '';
        $this->joins = '';
        $this->conditions = '';
        $this->limit = 0;
        $this->offset = 0;
        $this->groupBy = '';
        $this->orderBy = '';
        return $this;
    }

    public function relatedClasses($classes = []){
        $this->relationships = $classes;
        return $this;
    }

    public function columns($cols = ''){
        //Alias
        $cols = str_replace(get_class($this), $this::getTable(), $cols);

        if(isset($this->relationships) && !empty($this->relationships)){
            foreach ($this->relationships as $c){
                $cols = str_replace($c, $c::getTable(), $cols);
            }
        }

        $this->cols = $cols;
        return $this;
    }

    public function where($conditions = '', $values = array()){
        //Alias
        $conditions = str_replace(get_class($this), $this::getTable(), $conditions);
        
        if(isset($this->relationships) && !empty($this->relationships)){
            foreach ($this->relationships as $c){
                $conditions = str_replace($c, $c::getTable(), $conditions);
            }
        }
        
        $this->conditions .= $conditions;
        $this->params = $values;
        return $this;
    }
    
    public function join($type = 'inner', $class){
        $j = '';
        
        if(method_exists($this, 'getFk')){

            if($type == 'inner'){
                $j = ' INNER JOIN ';
            }else if($type == 'left'){
                $j = ' LEFT JOIN ';
            }else if($type == 'right'){
                $j = ' RIGHT JOIN ';
            }else{
                $j = ' INNER JOIN ';
            }
            
            $j .= $class::getTable() . ' ON ' . $this::getTable() . '.' . $this::getFk()[$class] . ' = ' . $class::getTable() . '.' . $class::getPk();
        }

        $this->joins .= $j;

        return $this;
    }
    
    public function take($limit = 0, $offset = 0){
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    public function groupBy($g)
    {
        $this->groupBy = $g;
        return $this;
    }

    public function orderBy($o)
    {
        $this->orderBy = $o;
        return $this;
    }

    public function records(){
        $this->cols = 'COUNT(*) records';
        $stmt = $this->getConn()->prepare($this->getQuery());
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['records'];
    }
    
    public function getQuery(){
        if(empty($this->cols)){
            $this->cols = $this->getColumns(get_class($this), true);
        }

        switch($this->queryType){
            case 'select':
                $this->query = 'SELECT '.$this->cols.' FROM '.$this::getTable();
                #JOIN CONDITIONS
                $this->query .= (!empty($this->joins)) ? ' '.$this->joins : '';
                #WHERE CONDITIONS
                $this->query .= (!empty($this->conditions)) ? ' WHERE '.$this->conditions : '';
                #GROUP BY
                $this->query .= (!empty($this->groupBy)) ? ' GROUP BY '.$this->groupBy : '';
                #ORDER BY
                $this->query .= (!empty($this->orderBy)) ? ' ORDER BY '.$this->orderBy : '';

                #LIMIT
                $limit = ($this->limit > 0) ? ($this->offset > 0) ? $this->offset . ', ' . $this->limit : $this->limit : '';

                $this->query .= (!empty($limit)) ? ' LIMIT '.$limit : '';
                break;
        }

        return $this->query;
    }

    public function getRawResult(){
        $stmt = $this->getConn()->prepare($this->getQuery());
        /*echo $this->getQuery();die();*/
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSingleRawResult(){
        $this->take(1);
        $stmt = $this->getConn()->prepare($this->getQuery());
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
    * CREATE
    */
    public function create(){
        $hostvars = $stmt = $vars = '';
        $params = array();
        foreach($this->getColumns($this) as $prop){
            $method = 'get' . ucfirst($prop->getName());
            if(!empty($this->$method())){
                $params[':'.$prop->getName()] = $this->$method();
                $hostvars .= ':' . $prop->getName() . ' ';
                $vars .= $prop->getName() . ' ';
            }
        }
        $hostvars = str_replace(' ', ',', trim($hostvars));
        $vars = str_replace(' ', ',', trim($vars));
        
        try{
            $stmt = 'INSERT INTO ' . $this::getTable() . ' (' . $vars . ') VALUES (' . $hostvars . ')';
            $stmt = $this->getConn()->prepare($stmt);
            $stmt->execute($params);

            return $stmt->rowCount();
        }catch(Exception $e){
            echo $e->getMessage();die();
            return 0;
        }
    }

    /**
     * UPDATE
     */
    public function update(){
        $hostvars = $stmt = $vars = '';
        $params = array();
        foreach($this->getColumns($this) as $prop){
            $method = 'get' . ucfirst($prop->getName());
            if(!empty($this->$method()) && $prop->getName() != $this->getPk()){
                $params[':'.$prop->getName()] = $this->$method();
                $vars .= $prop->getName() . ' = :' . $prop->getName() . ', ';
            }
        }
        $vars = substr($vars, 0, (strlen($vars)-2));

        try{
            $pk = 'get'.ucfirst($this->getPk());
            $params[':id'] = $this->$pk();
            $stmt = 'UPDATE ' . $this::getTable() . ' SET ' . $vars . ' WHERE ' . $this->getPk() . ' = :id';
            $stmt = $this->getConn()->prepare($stmt);

            $stmt->execute($params);
            return $stmt->rowCount();
        }catch(Exception $e){
            echo $e->getMessage();die();
            return 0;
        }
    }
}
