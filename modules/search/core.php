<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class Search extends ExtController
{

    protected $sql=null;
    public $tm_id=0;
    public $tpl='';
    public $func='';
    public $action='';
    protected $app=null;
    const C_NAME = 'Search';
    const C_TITLE = 'Поиск';
    const C_DESC = 'Поиск';
    const C_ISPUB = 0;

    private $Stem_Caching = 0;
    private $Stem_Cache = array();
    private $VOWEL = '/аеиоуыэюя/u';
    private $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/u';
    private $REFLEXIVE = '/(с[яь])$/u';
    private $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|ая|яя|ою|ею)$/u';
    private $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/u';
    private $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|ят|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/u';
    private $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/u';
    private $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/u';
    private $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/u';

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new SearchSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';
        $this->page = (isset($_REQUEST['p']) && $_REQUEST['p']>0) ? intval($_REQUEST['p']) : 1;
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        if ($this->action=='search_res')
        {
            $data = $this->sql->getSearchData($this->tm_id);
            
            if ($_REQUEST['dev'])
                    printarray($data);
            
            $tpl = $data['module_search_tpl_results'];

            $data['query'] = trim($_REQUEST['q']);

            $q_r = array();

            if (mb_strlen($data['query'], 'utf-8')>2)
            {
                $q_arr = preg_split('~[^0-9^а-я^a-z]+~ui', $data['query'], -1, PREG_SPLIT_NO_EMPTY);

                if (sizeof($q_arr)>1)
                    $q_r[0] = trim($data['query']);
                else
                    foreach ($q_arr as $a)
                        $q_r[] = $this->stem_word(trim($a));
            }

            #-- пробегаемся по БД и находим все топики (и страницы), где есть данное сочетание
            if (sizeof($q_r)>0)
            {
                $data['results'] = $this->sql->searchTextInModules($q_r, $data['module_search_cnt_literals']);
                
                $this->app->topic[0]['module_menu_url'] = preg_replace('~\&p=\d+~', '', $_SERVER['REQUEST_URI']);

                $this->extc_getPagerList(sizeof($data['results']), $data['module_search_cnt_results']);
                $data['pager_list'] = $this->ext_page_text;

if ($_REQUEST['dev'])
echo($this->page);
                $k = 0;
                foreach ($data['results'] as $t=>$d)
                {
                    $k++;
                    if ($k <= ($this->page-1)*$data['module_search_cnt_results'] || $k > $this->page*$data['module_search_cnt_results'])
                        unset($data['results'][$t]);
                }
            }
            else
                $data['results'] = array();
        }
        else
        {
            $this->sql->getModuleData($this->tm_id, $this->tpl, $this->func);
            $data = $this->sql->data;
            $tpl = $this->sql->data['module_search_tpl_form'];
        }
//printarray($data);
        return $this->ApplyTemplate($tpl, array('data'=>$data, 'is_active'=>1), $this->getClassName());
    }

    public function __toString()
    {
        $this->actionPopup();
        return $this->getContent();
    }

    public function loadPopupAdminBlockEdit()
    {
        $this->actionPopup();

        $data = $this->sql->getSearchData($this->tm_id);

        print $this->ApplyTemplateAdmin('edit.php', array('search'=>$data, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'save':
                $this->sql->saveSearchData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение контента блока');
                headerTo($_SESSION['_SITE_']['back_url']);
            default:
        }
    }





    private function s(&$s, $re, $to)
    {
        $orig = $s;
        $s = preg_replace($re, $to, $s);
        return $orig !== $s;
    }

    private function m($s, $re)
    {
        return preg_match($re, $s);
    }

    private function stem_word($word)
    {
        $word = mb_strtolower($word, 'utf-8');
        $word = mb_strtr($word, 'ё', 'е', 'utf-8');
        # Check against cache of stemmed words
        if ($this->Stem_Caching && isset($this->Stem_Cache[$word])) {
            return $this->Stem_Cache[$word];
        }
        $stem = $word;
        do {
          if (!preg_match($this->RVRE, $word, $p)) break;
          $start = $p[1];
          $RV = $p[2];
          if (!$RV) break;

          # Step 1
          if (!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
              $this->s($RV, $this->REFLEXIVE, '');

              if ($this->s($RV, $this->ADJECTIVE, '')) {
                  $this->s($RV, $this->PARTICIPLE, '');
              } else {
                  if (!$this->s($RV, $this->VERB, ''))
                      $this->s($RV, $this->NOUN, '');
              }
          }
          # Step 2
          $this->s($RV, '/и$/u', '');

          # Step 3
          if ($this->m($RV, $this->DERIVATIONAL))
              $this->s($RV, '/ость?$/u', '');

          # Step 4
          if (!$this->s($RV, '/ь$/u', '')) {
              $this->s($RV, '/ейше?/u', '');
              $this->s($RV, '/нн$/u', 'н');
          }

          $stem = $start.$RV;
        } while(false);
        if ($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;
        return $stem;
    }

    private function stem_caching($parm_ref)
    {
        $caching_level = @$parm_ref['-level'];
        if ($caching_level) {
            if (!$this->m($caching_level, '/^[012]$/u')) {
                die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
            }
            $this->Stem_Caching = $caching_level;
        }
        return $this->Stem_Caching;
    }

    private function clear_stem_cache()
    {
        $this->Stem_Cache = array();
    }




    private function __clone() {}

    public function __destruct() {}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

}