<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- Класс работы с Галереей

class Gallery extends _List
{
    protected $msql = null;
    private $_LIST = null;
    public $tm_id=0;
    public $item_id=0;
    public $action='';
    public $func='';
    public $tpl = '';
    const C_NAME = 'Gallery';
    const C_TITLE = 'Галерея';
    const C_DESC = 'Галерея';
    #-- в этом модуле данная переменная не должна быть константой, т.к. в зависимости от размещения на странице может меняться
    public $C_ISPUB = 1;

    public function __construct()
    {
        $this->msql = new GallerySql();
        $this->_LIST = new _List( trim($this->getClassName()) );
    }

    public function initVars()
    {
        if ( ! $this->_LIST )
            self::__construct();

        $this->_LIST->tm_id = $this->tm_id;
        $this->_LIST->item_id = $this->item_id;
        $this->_LIST->func = $this->func;
        $this->_LIST->C_ISPUB = $this->getClassIsPub();
    }

    public function __toString()
    {
        if ($this->func && is_callable(self::C_NAME, $this->func))
        {
            eval('$cont = $this->'.$this->func.';');
            return $cont;
        }
        else
            return $this->_LIST->getContent();
    }

    public function __destruct() {}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return $this->C_ISPUB; }


    #-----------------------------------------------------------------------------
    #-- Дополнительные функции класса, которые могут быть вызваны прямо из шаблона
    #-----------------------------------------------------------------------------

    public function f_short_catalog($cnt=3)
    {
        $this->C_ISPUB = 0;
        $data = $this->_LIST->sql->getModuleData($this->tm_id);
        $catalog = $this->_LIST->sql->getModuleItems($this->tm_id, $cnt);
        return $this->ApplyTemplate($this->tpl, array('catalog'=>$catalog, 'data'=>$data, 'is_active'=>$data['topics_module_is_active']), $this->getClassName());
    }

}