  <form action="edit.php" method="post" onsubmit="return rsv.validate(this, rules)">
    <input type="hidden" name="fb_id" value="{$config_info.fb_id}" />

    <div class="margin_bottom_large margin_top">
      {$L.text_validation_intro}
    </div>

      {foreach from=$grouped_fields key=k item=curr_group}
        {assign var=group value=$curr_group.group}
        {assign var=fields value=$curr_group.fields}

        {if $group.group_name}
          <div class="subtitle margin_bottom_large underline">{$group.group_name|upper}</div>
        {/if}

        {if $fields|@count > 0}
          <table class="list_table margin_bottom_large check_areas" cellpadding="1" cellspacing="1" border="0" width="100%">
          <tr>
            <th width="180">{$LANG.word_field}</th>
            <th width="80">{$L.word_required_q}</th>
            <th>{$L.phrase_error_string}</th>
          </tr>
        {/if}

        {foreach from=$fields item=curr_field}
          {assign var=field_id value=$curr_field.field_id}
          <tr>
            <td width="150" class="pad_left_small" valign="top">{$curr_field.field_title}</td>
            <td valign="top" align="center" class="check_area">
              <input type="checkbox" name="field_ids[]" class="field_ids" value="{$field_id}" {if $field_id|in_array:$error_field_ids}checked{/if} />
            </td>
            <td valign="top">
              {assign var=error_string value=""}
              {assign var=error_key value="error_`$field_id`"}
              {if $error_key|array_key_exists:$error_strings}
                {assign var=error_string value=$error_strings.$error_key}
              {/if}
              <input type="text" name="field_{$field_id}_error_string" class="full" value="{$error_string|escape}" />
            </td>
          </tr>
        {/foreach}

        {if $fields|@count > 0}
          </table>
        {/if}

      {/foreach}
    <p>
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </p>

  </form>
