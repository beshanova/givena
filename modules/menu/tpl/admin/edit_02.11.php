<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?> / ID: <?= $this->tm_id ?></div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close-no-action" onclick="f_popup_window_close();" /></div>
</div>

<div class="admin-popup-window-form">

<form action="" method="post">
<input type="hidden" name="admin_action" value="save">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="cl" value="<?= $cl ?>">
<input type="hidden" name="tm" value="<?= $tm_id ?>">

<div>
<span class="ui-tabs-anchor-const">Редактирование навигации</span>
<br>

<table class="menu_edit">
	<tr class="menu_edit_name">
		<td style="width: 290px;">Название пункта</td>
		<td></td>
		<td></td>
		<td>Ссылка</td>
		<td></td>
		<td>Порядок</td>
		<td>Скрыть</td>
		<td>Удалить</td>
	</tr>

  <?$glob_max_id=$k=0; $cnt_m=sizeof($menu);
    $urov = array();
    $is_last = false;

  foreach ($menu as $m) :

    $k++;
    $glob_max_id = max($glob_max_id, $m['module_menu_id']);
    #-- ищем на уровне пункты со вложенностью
    if (!isset($urov[$m['level']]))
    {
        foreach ($menu as $m2)
        {
            if ($m['level']==$m2['level'] && $m2['is_childs'])
            {
                $urov[$m['level']] = 1;
                break;
            }
        }
    }
    #-- находим скрытые и ссылочные пункты меню
    $tr_cl = '';
    if ($m['module_menu_is_hidden']==1)
        $tr_cl = 'menu_edit_hide_item';
    elseif ($m['module_menu_url_type']==1)
        $tr_cl = 'menu_edit_link_item';
  ?>

  <tr id="menu-item-<?= $m['module_menu_id'] ?>" mi="<?= $m['module_menu_id'] ?>" parent="<?= $m['module_menu_parent_id'] ?>"<?= ($m['level']>0 ? ' style="display:none;"' : '') ?> class="menu-item-00 <?= $tr_cl ?>" level="<?= $m['level'] ?>">
		<td style="padding-left:<?= ($m['level']*40 + 20) ?>px;"><?= ($m['is_childs'] ? '<div class="menu_edit_close" onclick="hide_show_parent(this);"></div>' : '') ?>
			<a href="<?= ($m['module_menu_url_type']!=2?'/':'') . $m['urls'] . ($m['module_menu_url']!=""&&$m['module_menu_url_type']!=2?'/':'') ?>" target="_blank" id="menu-item-text-title-<?= $m['module_menu_id'] ?>"><?= $m['module_menu_title'] ?></a>
      <input type="hidden" name="menu[<?= $m['module_menu_id'] ?>][title]" value="<?= htmlspecialchars($m['module_menu_title']) ?>" />
		</td>
		<td>
			<div class="menu_edit_edit tooltip" onclick="f_edit_title(this);"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td>
      <input type="text" name="menu[<?= $m['module_menu_id'] ?>][url]" value="<?= ($m['module_menu_url_type']!=2?'/':'') . $m['urls'] . ($m['module_menu_url']!=""&&$m['module_menu_url_type']!=2?'/':'') ?>" />
      <input type="hidden" name="menu[<?= $m['module_menu_id'] ?>][url_parent_hidden]" value="<?= str_replace($m['module_menu_url'], '', $m['urls']) ?>" />
    </td>
		<td> </td>
		<td>
			<div class="<?= ($m['is_last_level'] ? 'menu_edit_sort_down_not_activ' : 'menu_edit_sort_down') ?>" onclick="f_edit_sort_down(this);"></div>
			<div class="<?= ($k!=1 ? 'menu_edit_sort_up' : 'menu_edit_sort_up_not_activ') ?>" onclick="f_edit_sort_up(this);"></div>
      <input type="hidden" name="menu[<?= $m['module_menu_id'] ?>][sort]" value="<?= $m['module_menu_sortby'] ?>" id="menu-item-b-sort-<?= $m['module_menu_id'] ?>" />

			<div class="<?= ($k==1 || $m['level']>$pm['level'] ? 'menu_edit_sort_dr_not_activ' : 'menu_edit_sort_dr') ?>" onclick="f_edit_parent(this);"></div>
      <input type="hidden" name="menu[<?= $m['module_menu_id'] ?>][parent_id]" value="<?= $m['module_menu_parent_id'] ?>" id="menu-item-b-parent-<?= $m['module_menu_id'] ?>" />

      <input type="hidden" name="menu[<?= $m['module_menu_id'] ?>][level]" value="<?= $m['level'] ?>" id="menu-item-level-<?= $m['module_menu_id'] ?>" />
		</td>
		<td>
			<div class="menu_edit_hide" onclick="f_edit_hide(this);"></div>
      <input type="hidden" name="menu[<?= $m['module_menu_id'] ?>][hide]" value="<?= $m['module_menu_is_hidden'] ?>" id="menu-item-b-hide-<?= $m['module_menu_id'] ?>" />
		</td>
		<td>
    <? if ($m['module_menu_url_type']==0&&$m['module_menu_url']=="") : ?>
      <div class="menu_edit_delete_not_activ" onclick="f_edit_delete(this);"></div>
    <? else : ?>
      <div class="menu_edit_delete" onclick="f_edit_delete(this);"></div>
      <input type="hidden" name="menu[<?= $m['module_menu_id'] ?>][delete]" value="0" id="menu-item-b-delete-<?= $m['module_menu_id'] ?>" />
    <? endif ; ?>
		</td>
	</tr>

  <?
  $pm = $m;

  endforeach ; ?>

  <tr id="menu-item-xx" style="display:none;">
		<td style="padding-left:20px;">
			<input value="" name="menu[xx][title]">
		</td>
		<td> </td>
		<td> </td>
		<td>
      <input type="text" name="menu[xx][url]" value="" />
    </td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down_not_activ" onclick="f_edit_sort_down(this);"></div>
			<div class="menu_edit_sort_up" onclick="f_edit_sort_up(this);"></div>
      <input type="hidden" name="menu[xx][sort]" value="0" id="menu-item-b-sort-xx" />

			<div class="menu_edit_sort_dr" onclick="f_edit_parent(this);"></div>
      <input type="hidden" name="menu[xx][parent_id]" value="0" id="menu-item-b-parent-xx" />

      <input type="hidden" name="menu[xx][level]" value="0" id="menu-item-level-xx" />
		</td>
		<td>
			<div class="menu_edit_hide" onclick="f_edit_hide(this);"></div>
      <input type="hidden" name="menu[xx][hide]" value="0" id="menu-item-b-hide-xx" />
		</td>
		<td>
      <div class="menu_edit_delete" onclick="f_edit_delete(this);"></div>
      <input type="hidden" name="menu[xx][delete]" value="0" id="menu-item-b-delete-xx" />
		</td>
	</tr>

</table>

</div>
<br>
<input type="button" value="Добавить раздел" class="admin-popup-window-button-save" onclick="f_add_new_tr();">
<br><br><br>

<input type="submit" value="Сохранить" class="admin-popup-window-button-save">
<input type="button" value="Отменить" class="admin-popup-window-button-close-no-action" onclick="f_popup_window_close();" />
</form>
</div>

<script>
$(document).ready(function(){
  var glob_max_id = <?= $glob_max_id ?>;
  var is_changed = false;

  f_popup_window_close = function()
  {
		if (is_changed)
    {
      if (confirm('Точно закрыть окно без сохранения?'))
  	    closePopup();
    }
    else
      closePopup();
  }

  f_add_new_tr = function()
  {
    glob_max_id++;
    var tr_text = $('#menu-item-xx').html();
    $('tr.menu-item-00:last').after('<tr id="menu-item-'+glob_max_id+'" mi="'+glob_max_id+'" parent="0" class="menu-item-00 menu_edit_new_item" level="0">' + tr_text.replace(/xx/ig, glob_max_id) + '</tr>');

    test_down();
    change_sort_all_menu();
    is_changed = true;
  }

  //-- удаление пункта меню
  f_edit_delete = function(t)
  {
    if ($(t).hasClass('menu_edit_delete'))
    {
      var m_id = parseInt($(t).parent().parent().attr('mi'), 10);
      var m_val = parseInt($('#menu-item-b-delete-'+m_id).val(), 10);
      m_val = (m_val==1) ? 0 : 1;
      if (m_val>0)
        $('#menu-item-'+m_id).addClass('menu_edit_delete_item');
      else
        $('#menu-item-'+m_id).removeClass('menu_edit_delete_item');
      $('#menu-item-b-delete-'+m_id).val(m_val);
      is_changed = true;
    }
  }

  //-- скрытие пункта меню
  f_edit_hide = function(t)
  {
    if ($(t).hasClass('menu_edit_hide'))
    {
      var m_id = parseInt($(t).parent().parent().attr('mi'), 10);
      var m_val = parseInt($('#menu-item-b-hide-'+m_id).val(), 10);
      m_val = (m_val==1) ? 0 : 1;
      if (m_val>0)
        $('#menu-item-'+m_id).addClass('menu_edit_hide_item');
      else
        $('#menu-item-'+m_id).removeClass('menu_edit_hide_item');
      $('#menu-item-b-hide-'+m_id).val(m_val);
      is_changed = true;
    }
  }

  //-- редактирование заголовка пункта меню
  f_edit_title = function(t)
  {
    if ($(t).hasClass('menu_edit_edit'))
    {
      var m_id = parseInt($(t).parent().parent().attr('mi'), 10);
      var m_val = $('#menu-item-text-title-'+m_id).text();
      $('#menu-item-text-title-'+m_id).parent().append('<input name="menu['+m_id+'][title]" value="'+m_val+'" />');
      $('#menu-item-text-title-'+m_id).remove();

      is_changed = true;
    }
  }

  //-- раскрытие подуровней
  hide_show_parent = function(t)
  {
    $(t).each(function(){
      var m_id = parseInt($(this).parent().parent().attr('mi'), 10);
      if ($(this).hasClass('menu_edit_close'))
      {
        $('tr[parent="'+m_id+'"]').show();
        $(this).removeClass('menu_edit_close');
        $(this).addClass('menu_edit_open');
      }
      else
      {
        $('tr[parent="'+m_id+'"]').hide();
        $(this).addClass('menu_edit_close');
        $(this).removeClass('menu_edit_open');
        hide_show_parent_r('tr[parent="'+m_id+'"] td:first div');
      }
    });
  }
  hide_show_parent_r = function(t)
  {
    $(t).each(function(){
      var m_id = parseInt($(this).parent().parent().attr('mi'), 10);
      $('tr[parent="'+m_id+'"]').hide();
      $(this).addClass('menu_edit_close');
      $(this).removeClass('menu_edit_open');
      hide_show_parent_r('tr[parent="'+m_id+'"] td:first div');
    });
  }

  f_edit_sort_down = function(t)
  {
    if ($(t).hasClass('menu_edit_sort_down'))
    {
      var m_id = parseInt($(t).parent().parent().attr('mi'), 10);
      var m_parent = parseInt($('#menu-item-'+m_id).attr('parent'), 10);
      var m_level = parseInt($('#menu-item-'+m_id).attr('level'), 10);

      var m_n_id = parseInt($(t).parent().parent().next().attr('mi'), 10);
      var m_n_parent = parseInt($('#menu-item-'+m_n_id).attr('parent'), 10);
      var m_n_level = parseInt($('#menu-item-'+m_n_id).attr('level'), 10);

//      alert(m_id+'/'+m_parent+'/'+m_level+'/'+m_n_id+'/'+m_n_parent+'/'+m_n_level)

      if (m_level<m_n_level || ! m_n_id)
      {
        while (m_n_parent!=m_parent && m_n_id>0)
        {
          var m_n_id = parseInt($('#menu-item-'+m_n_id).next().attr('mi'), 10);
          var m_n_parent = parseInt($('#menu-item-'+m_n_id).attr('parent'), 10);
        }
        if (!m_n_id)
        {
          var pad_left = parseInt($('#menu-item-'+m_id+' td:first').css('padding-left'), 10);
          $('#menu-item-'+m_id+' td:first').css('padding-left', (pad_left-40)+'px');
          $('#menu-item-'+m_id).attr('level', m_level-1);
          $('#menu-item-level-'+m_id).val(m_level-1);

          var m0_n_parent = parseInt($('#menu-item-'+m_parent).attr('parent'), 10);
          $('#menu-item-b-parent-'+m_id).val(m0_n_parent);
          $('#menu-item-'+m_id).attr('parent', m0_n_parent);

          if ($('tr[parent="'+m_parent+'"]').length==0)
          {
            $('#menu-item-'+m_parent+' td:first div').remove();
          }
          test_parent(m0_n_parent);
        }
      }
      else if ($('tr[parent="'+m_n_id+'"]').length>0 && m_n_parent==m_parent)
      {
        m0_n_id = m_n_id;
        do
        {
          var m_n_id = parseInt($('tr[parent="'+m_n_id+'"]:last').attr('mi'), 10);
          if (m_n_id>0)
            m0_n_id = m_n_id;
        }
        while (m_n_id>0);
        m_n_id = m0_n_id;
        var m_n_parent = parseInt($('#menu-item-'+m_n_id).attr('parent'), 10);
        var m_n_level = parseInt($('#menu-item-'+m_n_id).attr('level'), 10);
      }

      if (m_n_parent==m_parent || m_level<m_n_level)
      {
        $('#menu-item-'+m_n_id).after($('#menu-item-'+m_id));

        cur_moved_id=0;
        find_and_move_childs_down(m_id, (m_n_parent!=m_parent?1:0));
      }
      else if (m_level>m_n_level)
      {
        var pad_left = parseInt($('#menu-item-'+m_id+' td:first').css('padding-left'), 10);
        $('#menu-item-'+m_id+' td:first').css('padding-left', (pad_left-40)+'px');

        var m0_n_parent = parseInt($('#menu-item-'+m_parent).attr('parent'), 10);
        $('#menu-item-b-parent-'+m_id).val(m0_n_parent);
        $('#menu-item-'+m_id).attr('parent', m0_n_parent);
        $('#menu-item-'+m_id).attr('level', m_level-1);
        $('#menu-item-level-'+m_id).val(m_level-1);

        if ($('tr[parent="'+m_parent+'"]').length==0)
        {
          $('#menu-item-'+m_parent+' td:first div').remove();
          $('#menu-item-'+m_parent+' td:first').css('padding-left', parseInt($('#menu-item-'+m_parent+' td:first').css('padding-left'), 10)+'px');
        }
      }


      test_parent(m_parent);
      change_sort_all_menu();
      test_down();
      test_up();
      is_changed = true;
    }
  }

  f_edit_sort_up = function(t)
  {
    if ($(t).hasClass('menu_edit_sort_up'))
    {
      var m_id = parseInt($(t).parent().parent().attr('mi'), 10);
      var m_parent = parseInt($('#menu-item-'+m_id).attr('parent'), 10);
      var m_level = parseInt($('#menu-item-'+m_id).attr('level'), 10);

      var m_n_id = parseInt($(t).parent().parent().prev().attr('mi'), 10);
      var m_n_parent = parseInt($('#menu-item-'+m_n_id).attr('parent'), 10);
      var m_n_level = parseInt($('#menu-item-'+m_n_id).attr('level'), 10);

      if (m_level<m_n_level)
        while (m_n_parent!=m_parent)
        {
          var m_n_id = parseInt($('#menu-item-'+m_n_id).prev().attr('mi'), 10);
          var m_n_parent = parseInt($('#menu-item-'+m_n_id).attr('parent'), 10);
        }

      if (m_n_parent==m_parent || m_level<m_n_level)
      {
        $('#menu-item-'+m_n_id).before($('#menu-item-'+m_id));

        cur_moved_id=0;
        find_and_move_childs_up(m_id, 0);
      }
      else if (m_level>m_n_level)
      {
        $('#menu-item-'+m_n_id).before($('#menu-item-'+m_id));
        var pad_left = parseInt($('#menu-item-'+m_id+' td:first').css('padding-left'), 10);
        $('#menu-item-'+m_id+' td:first').css('padding-left', (pad_left-40)+'px');

        $('#menu-item-b-parent-'+m_id).val(m_n_parent);
        $('#menu-item-'+m_id).attr('parent', m_n_parent);
        $('#menu-item-'+m_id).attr('level', m_level-1);
        $('#menu-item-level-'+m_id).val(m_level-1);
        if ($('tr[parent="'+m_n_id+'"]').length==0)
        {
          $('#menu-item-'+m_n_id+' td:first div').remove();
          $('#menu-item-'+m_n_id+' td:first').css('padding-left', parseInt($('#menu-item-'+m_id+' td:first').css('padding-left'), 10)+'px');
        }
        find_and_move_childs_up(m_id, 1);

        test_parent(m_n_parent);
      }


      test_parent(m_parent);
      change_sort_all_menu();
      test_down();
      test_up();
      is_changed = true;
    }
  }

  var cur_moved_id=0;
  find_and_move_childs_up = function(m_id, lev)
  {
    $('tr[parent="'+m_id+'"]').each(function(){
      var p_id = parseInt($(this).attr('mi'), 10);
      var p_level = parseInt($(this).attr('level'), 10);

      $('#menu-item-'+(cur_moved_id>0?cur_moved_id:m_id)).after($(this));

      if (lev==1)
      {
        $(this).attr('level', p_level-1);
        $(this).css('level', p_level-1);
        var pad_left = parseInt($('#menu-item-'+p_id+' td:first').css('padding-left'), 10);
        $('#menu-item-'+p_id+' td:first').css('padding-left', pad_left-40+'px');
      }

      cur_moved_id = p_id;

      find_and_move_childs_up(p_id, lev);
    });
  }

  find_and_move_childs_down = function(m_id, lev)
  {
    $('tr[parent="'+m_id+'"]').each(function(){
      var p_id = parseInt($(this).attr('mi'), 10);
      var p_level = parseInt($(this).attr('level'), 10);

      $('#menu-item-'+(cur_moved_id>0?cur_moved_id:m_id)).after($(this));

      if (lev==1)
      {
        $(this).attr('level', p_level-1);
        $(this).css('level', p_level-1);
        var pad_left = parseInt($('#menu-item-'+p_id+' td:first').css('padding-left'), 10);
        $('#menu-item-'+p_id+' td:first').css('padding-left', pad_left-40+'px');
      }

      cur_moved_id = p_id;

      find_and_move_childs_down(p_id, lev);
    });
  }

  f_edit_parent = function(t)
  {
    if ($(t).hasClass('menu_edit_sort_dr'))
    {
      var m_id = parseInt($(t).parent().parent().attr('mi'), 10);
      var m_pid = parseInt($('#menu-item-'+m_id).attr('parent'), 10);
      var m_level = parseInt($('#menu-item-'+m_id).attr('level'), 10);
      m_n_id = m_id;
      do
      {
        var m_n_id = parseInt($('#menu-item-'+m_n_id).prev().attr('mi'), 10);
      }
      while ( parseInt($('#menu-item-'+m_n_id).attr('parent'), 10) != m_pid );

      $(t).parent().parent().attr('parent', m_n_id);
      if ($('#menu-item-'+m_n_id+' td:first div').hasClass('menu_edit_close'))
        $('#menu-item-'+m_n_id+' td:first div').click();

      $('#menu-item-b-parent-'+m_id).val(m_n_id);
      $(t).parent().parent().attr('level', m_level+1);
      $('#menu-item-level-'+m_id).val(m_level+1);

      if ( ! $('#menu-item-'+m_n_id+' td:first div').hasClass('menu_edit_open') && ! $('#menu-item-'+m_n_id+' td:first div').hasClass('menu_edit_close') )
      {
        var pad_n_left = parseInt($('#menu-item-'+m_n_id+' td:first').css('padding-left'), 10);
        $('#menu-item-'+m_n_id+' td:first').prepend('<div class="menu_edit_open" onclick="hide_show_parent(this);"></div>').css('padding-left', (pad_n_left)+'px');
      }

      var pad_left = parseInt($('#menu-item-'+m_n_id+' td:first').css('padding-left'), 10);
      $('#menu-item-'+m_id+' td:first').css('padding-left', (pad_left+40)+'px');

      $('#menu-item-'+m_id+' td:eq(5) div:eq(0)').removeClass('menu_edit_sort_down_not_activ').addClass('menu_edit_sort_down');

      find_and_move_parent(m_id);
      test_parent(m_n_id);
      change_sort_all_menu();
      test_down();
      test_up();
      is_changed = true;
    }
  }

  find_and_move_parent = function(m_id)
  {
    $('tr[parent="'+m_id+'"]').each(function(){
      var p_id = parseInt($(this).attr('mi'), 10);
      var p_level = parseInt($(this).attr('level'), 10);
      var p_left = parseInt($(this).find('td:first').css('padding-left'), 10);
      $('#menu-item-'+p_id+' td:first').css('padding-left', (p_left+40)+'px');

      $(this).attr('level', p_level+1);

      find_and_move_parent(p_id);
    });
  }

  //-- функция проверяет и выставляет правильную сортировку во всем меню
  change_sort_all_menu = function()
  {
    var level = [];
    $('tr.menu-item-00').each(function(){
      var m_id = parseInt($(this).attr('mi'), 10);
      var m_level = parseInt($(this).attr('level'), 10);
      if (!level[m_level])
        level[m_level] = 0
      level[m_level] += 10;
      $('#menu-item-b-sort-'+m_id).val(level[m_level]);
    });
  }

  //-- функция проверки на возможность делать подразделы верхнего пункта
  test_parent = function(m_pid)
  {
    $('tr[parent="'+m_pid+'"]').each(function(){
      $(this).find('td:eq(5) div:eq(2)').removeClass('menu_edit_sort_dr_not_activ').addClass('menu_edit_sort_dr');
    });
    $('tr[parent="'+m_pid+'"]:first td:eq(5) div:eq(2)').removeClass('menu_edit_sort_dr').addClass('menu_edit_sort_dr_not_activ');
  }

  //-- функция проверки на возможность перемещать раздел вниз
  test_down = function()
  {
    $('tr[level="0"]').each(function(){
      $(this).find('td:eq(5) div:eq(0)').removeClass('menu_edit_sort_down_not_activ').addClass('menu_edit_sort_down');
    });
    $('tr[level="0"]:last td:eq(5) div:eq(0)').removeClass('menu_edit_sort_down').addClass('menu_edit_sort_down_not_activ');
  }

  //-- функция проверки на возможность перемещать раздел вверх
  test_up = function()
  {
    $('tr[level="0"]').each(function(){
      $(this).find('td:eq(5) div:eq(1)').removeClass('menu_edit_sort_up_not_activ').addClass('menu_edit_sort_up');
    });
    $('tr[level="0"]:first td:eq(5) div:eq(1)').removeClass('menu_edit_sort_up').addClass('menu_edit_sort_up_not_activ');
  }

});
</script>