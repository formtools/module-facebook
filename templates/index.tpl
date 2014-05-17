{include file='modules_header.tpl'}

  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><a href="index.php"><img src="images/icon_facebook.png" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="../../admin/modules">{$LANG.word_modules}</a>
      <span class="joiner">&raquo;</span>
      {$L.module_name}
    </td>
  </tr>
  </table>

  {include file='messages.tpl'}

  <div class="margin_bottom_large">
    {$L.text_module_intro}
  </div>

  {if $num_results == 0}

    <div class="notify" class="margin_bottom_large">
      <div style="padding:8px">
        {$L.text_no_configured_forms}
      </div>
    </div>

  {else}

    {$pagination}

    <table class="list_table" style="width:100&" cellpadding="1" cellspacing="1">
    <tr style="height: 20px;">
      <th width="110">{$L.phrase_configuration_id}</th>
      <th>{$LANG.word_form}</th>
      <th>{$LANG.word_view}</th>
      <th>{$L.phrase_facebook_canvas_url}</th>
      <th class="edit"></th>
      <th class="del"></th>
    </tr>
    {foreach from=$results item=result name=row}
      <tr>
        <td class="medium_grey fb_id">{$result.fb_id}</td>
        <td><a href=""></a>{display_form_name form_id=$result.form_id}</td>
        <td>{display_view_name view_id=$result.view_id}</td>
        <td align="center">
          <a href="#" class="canvas_url_link">{$L.phrase_view_url}</a>
        </td>
        <td class="edit"><a href="edit.php?fb_id={$result.fb_id}"></a></td>
        <td class="del"><a href="#" onclick="return page_ns.delete_configuration({$result.fb_id})"></a></td>
      </tr>
    {/foreach}
    </table>

  {/if}

  <p>
    <input type="button" id="configure_form_button" value="{$L.phrase_configure_form}" />
  </p>

  <div id="canvas_url" class="hidden">
    <input type="text" id="canvas_url_field" value="" style="width:100%" />
  </div>

  <div id="configure_form_dialog" class="hidden">
    <div class="margin_bottom_large">
      {$L.text_configure_form}
    </div>

    <table cellspacing="1" cellpadding="0">
    <tr>
      <td class="pad_left_small" width="120">{$LANG.word_form}</td>
      <td>
        {forms_dropdown name_id="form_id" omit_forms=$omit_forms include_blank_option=true}
      </td>
    </tr>
    <tr>
      <td valign="top" class="pad_left_small">{$LANG.word_view}</td>
      <td valign="top">
        <select name="view_id" id="view_id" disabled>
          <option value="">{$LANG.phrase_please_select_form}</option>
        </select>
        <div class="hint">
          {$L.text_configure_form_view_hint}
        </div>
      </td>
    </tr>
    </table>
  </div>

{include file='modules_footer.tpl'}
