  <form action="edit.php" method="post">
    <input type="hidden" name="fb_id" value="{$config_info.fb_id}" />

    <table cellspacing="1" cellpadding="0" class="list_table margin_bottom_large margin_top">
    <tr>
      <td class="pad_left_small" width="180">{$L.phrase_show_like_button}</td>
      <td>
        <input type="radio" name="show_like_button" id="show_like_button1" value="yes" {if $config_info.show_like_button == "yes"}checked="checked"{/if} />
          <label for="show_like_button1">{$LANG.word_yes}</label>
        <input type="radio" name="show_like_button" id="show_like_button2" value="no" {if $config_info.show_like_button == "no"}checked="checked"{/if} />
          <label for="show_like_button2">{$LANG.word_no}</label>
      </td>
    </tr>
    <tr>
      <td class="pad_left_small">{$L.phrase_facebook_canvas_page}</td>
      <td>
        <input type="text" name="facebook_form_url" value="{$config_info.facebook_form_url|escape}" class="full" />
        <div class="hint">
          {$L.text_canvas_page_hint}
        </div>
      </td>
    </tr>
    <tr>
      <td class="pad_left_small">{$L.phrase_left_column_width}</td>
      <td>
        <input type="text" name="questions_column_width" size="3" maxlength="3" value="{$config_info.questions_column_width|escape}" />
      </td>
    </tr>
    </table>

    <div>
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </div>
  </form>
