<?php class Database_model extends CI_Model {

	public function __construct()
	{
		/*$this->lang->load("form_validation","English");*/
        $CI = &get_instance();
    }

    //SELECT DATA WITH JOIN
    public function get_joins($table,$value,$joins,$where='',$order_by,$order,$limit='',$offset=0,$distinct='',$likearray='',$where_in='',$wherincoumn='',$groupby='', $total='')
    {
        $this->db->start_cache();
        $this->db->select($value,false);
        if (is_array($joins) && count($joins) > 0)
        {
            foreach($joins as $k => $v)
            {
                $this->db->join($v['table'], $v['condition'], $v['jointype']);
            }
        }
        $this->db->order_by($order_by,$order);
        if($where!='')
            $this->db->where($where);
        if($likearray!='')
            $this->db->where($likearray);
        if($distinct!=='')
            $this->db->distinct();
        if(!empty($where_in))
            $this->db->where_in($wherincoumn, $where_in);
        if($groupby!==''){
            $this->db->group_by($groupby);
        }
        $this->db->stop_cache();

        if($total==''){
            if(strlen($limit))
                $this->db->limit($limit,$offset);
            $this->db->flush_cache();
            return $this->db->get($table);
        }
        else
        {
            $query['total_records']=$this->db->get($table)->num_rows();
            if(strlen($limit))
                $this->db->limit($limit,$offset);
            $query['results']=$this->db->get($table)->result();
            $this->db->flush_cache();
            return $query;
        }
    }

    //SIMPLE SELECT ALL RECORD
    public function get_all_records($table, $select=' * ', $where='', $orderby='',$order='', $limit='',$offset=0,$groupby='',$whereinvalue='',$whereinarray='',$totalCount = 'No')
    {
        $this->db->select($select,false);

        if(strlen($orderby)){$this->db->order_by($orderby,$order);}  // ORDER BY  e.g 'Id desc, name asc'

        if(!empty($where)){	$this->db->where($where);}  // WHERE CONDITION r.g array('id'=>10, 'status !=' => 'Delete')
        if($groupby!='')
            $this->db->group_by($groupby);
        if(!empty($whereinarray) && $whereinvalue!=''){
            $this->db->where_in($whereinvalue,$whereinarray);
        }

        /*if(strlen($limit)){
            return $this->db->get($table, $limit, $offset);
        }else {
            return $this->db->get($table);
        }*/
        if($limit !== '' && $offset !== '') {
            if ($totalCount != 'No') {
                //here we use the clone command to create a shallow copy of the object
                $tempdb = clone $this->db;
                //now we run the count method on this copy
                $total_records = $tempdb->from($table)->count_all_results();
                $results = $this->db->get($table, $limit, $offset)->result();
                return compact('total_records','results');
            } else {
                return $this->db->get($table, $limit, $offset);
            }
        }
        else{
            return $this->db->get($table);
        }
    }

    //SELECT DATA WITH JOINLIST
    public function get_joinlist($table,$value,$joins,$where,$order_by,$order,$limit,$offset)
    {
        $this->db->start_cache();
        $this->db->select($value);
        if (is_array($joins) && count($joins) > 0)
        {
            foreach($joins as $k => $v)
            {
                $this->db->join($v['table'], $v['condition'], $v['jointype']);
            }
        }
        $this->db->order_by($order_by,$order);
        $this->db->where($where);
        $this->db->stop_cache();

        $query_result['total_records']=$this->db->get($table)->num_rows();
        $query_result['results']=$this->db->get($table, $limit, $offset)->result();
        $this->db->flush_cache();
        return $query_result;
    }

    //INSERT MODIFIED DATA
	public function insert_modified($values,$ModifiedBy,$table='modified_log')
	{	
		$this->ip_date = $this->common_model->get_date_ip();
		$ModifiedDate=$this->ip_date->cur_date;
		$ModifiedIp=$this->ip_date->ip;
		 //echo count($values);
		if (is_array(@$values[0]))
		{
			$i=0;
			foreach($values as $val)
			{
				$values[$i]['modified_by']=$ModifiedBy;
				$values[$i]['modified_date']=$ModifiedDate;
				$values[$i]['modified_ip']=$ModifiedIp;
				$i++;
			}
			$this->db->insert_batch($table,$values);
		}
		else{
			$values['modified_by']=$ModifiedBy;
			$values['modified_date']=$ModifiedDate;
			$values['modified_ip']=$ModifiedIp;
			$this->db->insert($table, $values);
		}
		return $this->db->insert_id();
	}

	//INSERT DATA IN TABLE
    public function save($table,$admin){
		$this->db->insert($table, $admin);
		return $this->db->insert_id();
	}

	//INSERT MULTIPLE RECORDS	
    public function multiple_insert($table,$data)
	{
		$this->db->insert_batch($table, $data); 
		return $this->db->insert_id();
	}

	//MULTIPLE UPDATE
    public function multiple_update($table,$data,$index)
	{
		$this->db->update_batch($table, $data,$index); 
	}

	//UPDATE ON EXIST
    public function updateOnExist($table, $data)
    {
        $columns    = array();
        $values     = array();
        $upd_values = array();
        foreach ($data as $key => $val){
            $columns[]    = $key;
            $val = $this->db->escape($val);
            $values[]     = $val;
            $upd_values[] = $key.'='.$val;
        }
        $sql = "INSERT INTO ". $this->db->dbprefix($table) ."(".implode(",", $columns).")values(".implode(', ', $values).") ON DUPLICATE KEY UPDATE ".implode(",", $upd_values);
        return $this->db->query($sql);
    }

    //DELETE THE RECORD
    function delete($table,$where, $in_key='',$in_array=array())
    {
        $this->db->where($where);
        if($in_key!='' && !empty($in_array))
            $this->db->where_in($in_key,$in_array);
        $this->db->delete($table);
        return $this->db->affected_rows();
    }

    //COUNT ALL RECORD FROM TABLE
    public function count_all($table,$where,$likearray='')
	{   
	   if($likearray!='')
	   		$this->db->where($likearray);
	   return $this->db->where($where)->count_all_results($table);
	}

    //INSERT /REPLACE ON KEY
    public function replace($table,$value){
		$this->db->replace($table, $value);
		return $this->db->insert_id();
	}

	//UPDATE
	function update($table,$array,$where)
	{
		$this->db->where($where);
		$this->db->update($table,$array);
	}

	//UPDATE WITH JOIN
    function updateWithJoin($table,$array,$where,$joins='')
    {
        if (is_array($joins) && count($joins) > 0)
        {
            foreach($joins as $k => $v)
            {
                $this->db->join($v['table'], $v['condition'], $v['jointype']);
            }
        }
        $this->db->where($where);
        $this->db->update($table,$array);
        /*var_dump($this->db->last_query());*/
    }

    //CUSTOM QUERY
	public function custom_query($query)
	{
		return $this->db->query($query);
	}

	//UPDATE SET
	function update_set($table,$coulmn,$value,$where)
	{
		$this->db->where($where);
		$this->db->set($coulmn, $value, FALSE);
		$this->db->update($table);
	}

	//CHECK RECORD EXIST
	function check_record_exist($table,$select_value,$array)
	{
		$query=$this->db->select($select_value);
		$query=$this->db->limit(1);
		$query=$this->db->get_where($table,$array);
		return $query->row_array();	
	}

	//UPDATE WHERE IN
    function update_where_in($table,$where='',$whereinvalue='',$whereinarray='',$column,$value=''){
        if(!empty($where)){	$this->db->where($where);}
        if(!empty($whereinvalue) && $whereinvalue!=''){
            $this->db->where_in($whereinvalue,$whereinarray);
        }
        $this->db->set($column, $value);
        $this->db->update($table);
        return true;
    }

    /*public function get_joins($table,$value,$joins,$where,$order_by,$order,$limit='',$offset='',$distinct='',$likearray='',$groupby='',$whereinvalue='',$whereinarray='',$totalCount = 'No')
	{
		$this->db->select($value);
		if (is_array($joins) && count($joins) > 0)
		{
		 foreach($joins as $k => $v)
		   {
			$this->db->join($v['table'], $v['condition'], $v['jointype']);
		   }
		}

		$this->db->where($where);
		if($distinct!=='')
			$this->db->distinct();
		if($likearray!='')
			$this->db->where($likearray);

		if($groupby!='')
			$this->db->group_by($groupby);
		if(!empty($whereinvalue) && $whereinvalue!='')
			$this->db->where_in($whereinvalue,$whereinarray);

		if($limit !== '' && $offset !== '') {
            if ($totalCount != 'No') {
                //here we use the clone command to create a shallow copy of the object
                $tempdb = clone $this->db;
                //now we run the count method on this copy
                $num_rows = $tempdb->from($table)->count_all_results();
	            $this->db->order_by($order_by,$order);
                $result = $this->db->get($table, $limit, $offset)->result();
                return compact('num_rows','result');
            } else {
                return $this->db->get($table, $limit, $offset);
            }
        }
		else{
            return $this->db->get($table);
        }

	}*/
}
