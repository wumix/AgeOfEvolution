<?php
/**
 * village
 * 
 * @author pagliaccio
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';
class Model_village extends Zend_Db_Table_Abstract
{
    /**
     * The default table name 
     */
    protected $_primary = "id";
    private $id;
    public $data = array();
    private $cid;
    public $busy=array();
    /**
     * costruzioni
     * @var array|Model_building
     */
    public $building;
    function __construct ($cid, $order = null)
    {
    	$this->_name=SERVER.'_map';
        $this->cid = $cid;
        parent::__construct();
        //$this->getAdapter()->setFetchMode(Zend_Db::FETCH_ASSOC); not work
        $rows= $this->fetchAll("`civ_id`='$cid'", $order);
        foreach ($rows->toArray() as $value) {
        	$this->data[$value['id']]=$value;
        	$this->building[$value['id']] = new Model_building($this->cid, $value['id']);
        	$busy=0;
        	foreach ($this->building[$value['id']]->data as $v) {
        		$busy+=$v['pop'];
        		
        	}
        	//Zend_Registry::get('log')->debug($busy,'busy');
        	$this->data[$value['id']]['busy_pop']=$busy;
        	$this->busy[$value['id']]=$busy;
        	//Zend_Registry::get('log')->debug($this->data[$value['id']],'data village creatin');
        }
    }
    /**
     * @return array
     */
    function getList ()
    {
        return $this->data;//->toArray();
    }
    function setCurrentVillage ($vid = "all")
    {
    	$this->id = $vid;
    	if (!$this->building[$vid]) $this->building[$vid] = new Model_building($this->cid, $vid);  
    }
    /**
     * restituisce le risorse
     * @return array
     */
    function getResource ()
    {
        return array($this->data[$this->id]['resource_1'], 
        $this->data[$this->id]['resource_2'], 
        $this->data[$this->id]['resource_3'], $this->data[$this->id]['pop'], 
        $this->data[$this->id]['busy_pop']);
    }
    /**
     * modifica il nome dei villaggi.
     * @param <Array> $vect [id]=>nome
     * @return <bool>
     */
    function modNameVillage ($vect)
    {
        foreach ($vect as $key => $value) {
            $this->update(array('name' => $value), 
            "`id`='" . $key . "'");
        }
        return true;
    }
    /**
     * da la produzione
     * @return Array
     */
    function getProd ()
    {
        return array($this->data[$this->id]['production_1'], 
        $this->data[$this->id]['production_2'], 
        $this->data[$this->id]['production_3']);
    }
    function setProd ($prod)
    {
        $data = array('production_1' => $prod[0], 'production_2' => $prod[1], 
        'production_3' => $prod[2]);
        foreach ($data as $key => $value) {
            $this->data[$this->id][$key] = $value;
        }
        $where = "id='$this->id'";
        $this->update($data, $where);
    }
    function setResource ($res)
    {
        $data = array('resource_1' => $res[0], 'resource_2' => $res[1], 
        'resource_3' => $res[2]);
        foreach ($data as $key => $value) {
            $this->data[$this->id][$key] = $value;
        }
        $where = "id='$this->id'";
        $this->update($data, $where);
    }
    function write ($id = false)
    {
        if (! $id)
            $id = $this->id;
        unset($this->data[$id]['busy_pop']);
        $this->update($this->data[$id],"`id`='$id'");
    }
}
