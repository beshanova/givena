<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- Класс работы с Отзывами

class Reviews extends _List
{
    protected $msql = null;
    private $_LIST = null;
    public $tm_id=0;
    public $item_id=0;
    public $action='';
    public $func='';
    public $tpl = '';
    const C_NAME = 'Reviews';
    const C_TITLE = 'Отзывы';
    const C_DESC = 'Отзывы';
    #-- в этом модуле данная переменная не должна быть константой, т.к. в зависимости от размещения на странице может меняться
    public $C_ISPUB = 1;


    // printarray($_SESSION);
    
    public function __construct()
    {
        $this->msql = new ReviewsSql();
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

    public function f_short_news($cnt=3)
    {
        $this->C_ISPUB = 0;
        $data = $this->_LIST->sql->getModuleData($this->tm_id);
        $catalog = $this->_LIST->sql->getModuleItemsNews($this->tm_id, $cnt);
        return $this->ApplyTemplate($this->tpl, array('catalog'=>$catalog, 'data'=>$data, 'is_active'=>$data['topics_module_is_active']), $this->getClassName());
    }

}