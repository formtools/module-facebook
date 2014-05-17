  <form action="edit.php" method="post">
    <input type="hidden" name="fb_id" value="{$config_info.fb_id}" />

    <div class="subtitle underline margin_top margin_bottom_large">{$L.phrase_facebook_form}</div>

    <table cellspacing="1" cellpadding="0" class="list_table margin_bottom_large">
    <tr>
      <td class="pad_left_small" width="180">{$L.phrase_form_title}</td>
      <td>
        <input type="text" name="form_title" value="{$config_info.form_title|escape}" class="full" maxlength="255" />
      </td>
    </tr>
    <tr>
      <td class="pad_left_small">{$L.phrase_opening_text}</td>
      <td>
        <textarea name="opening_text" class="opening_text">{$config_info.opening_text}</textarea>
        <div class="hint">{$L.phrase_opening_text_hint}</div>
      </td>
    </tr>
    <tr>
      <td class="pad_left_small">{$L.phrase_submit_button_label}</td>
      <td>
        <input type="text" name="submit_button_label" value="{$config_info.submit_button_label|escape}" class="full" maxlength="255" />
        <div class="hint">{$L.phrase_submit_button_label_hint}</div>
      </td>
    </tr>
    </table>

    <div class="subtitle underline margin_bottom_large">{$L.phrase_offline_form}</div>

    <table cellspacing="1" cellpadding="0" class="list_table margin_bottom_large">
    <tr>
      <td class="pad_left_small" width="180">{$L.phrase_offline_form_page_title}</td>
      <td>
        <input type="text" name="offline_form_page_title" class="full" value="{$config_info.offline_form_page_title|escape}" />
      </td>
    </tr>
    <tr>
      <td class="pad_left_small">{$L.phrase_offline_form_page_content}</td>
      <td>
        <textarea name="offline_form_page_content" class="page_content">{$config_info.offline_form_page_content}</textarea>
      </td>
    </tr>
    </table>

    <div class="subtitle underline margin_bottom_large">{$L.phrase_thankyou_page}</div>

    <table cellspacing="1" cellpadding="0" class="list_table margin_bottom_large">
    <tr>
      <td class="pad_left_small" width="180">{$L.phrase_thankyou_page_title}</td>
      <td>
        <input type="text" name="thankyou_page_title" class="full" value="{$config_info.thankyou_page_title|escape}" />
      </td>
    </tr>
    </tr>
    <tr>
      <td class="pad_left_small">{$L.phrase_thankyou_page_content}</td>
      <td>
        <textarea name="thankyou_page" class="page_content">{$config_info.thankyou_page}</textarea>
        <div class="hint">{$L.text_thankyou_page_content_hint}</div>
      </td>
    </tr>
    </table>

    <div>
      <input type="submit" name="update" value="{$LANG.word_update}" />
    </div>
  </form>
