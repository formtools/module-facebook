  <form action="edit.php" method="post" onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="fb_id" value="{$config_info.fb_id}" />

    <table cellspacing="1" cellpadding="0" class="list_table margin_top margin_bottom_large">
    <tr>
      <td class="pad_left_small" width="180">{$LANG.word_status}</td>
      <td>
        <input type="radio" name="status" id="s1" value="online" {if $config_info.status == "online"}checked{/if} />
          <label class="green" for="s1">{$LANG.word_online}</label>
        <input type="radio" name="status" id="s2" value="offline" {if $config_info.status == "offline"}checked{/if} />
          <label class="red" for="s2">{$LANG.word_offline}</label>
      </td>
    </tr>
    <tr>
      <td class="pad_left_small">{$LANG.word_form}</td>
      <td>
        {forms_dropdown name_id="form_id" include_blank_option=true default=$config_info.form_id}
      </td>
    </tr>
    <tr>
      <td class="pad_left_small">{$LANG.word_view}</td>
      <td>
        {views_dropdown name_id="view_id" form_id=$config_info.form_id include_blank_option=true selected=$config_info.view_id}
        <div class="hint">
          {$L.text_configure_form_view_hint}
        </div>
      </td>
    </tr>
    </table>

    <div>
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </div>
  </form>
