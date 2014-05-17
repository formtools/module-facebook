{include file='modules_header.tpl'}

  <table cellpadding="0" cellspacing="0">
  <tr>
    <td width="45"><a href="index.php"><img src="images/icon_facebook.png" border="0" width="34" height="34" /></a></td>
    <td class="title">
      <a href="../../admin/modules">{$LANG.word_modules}</a>
      <span class="joiner">&raquo;</span>
      <a href="./">{$L.module_name}</a>
      <span class="joiner">&raquo;</span>
      {$form_name}
    </td>
  </tr>
  </table>

  {include file="messages.tpl"}

  {ft_include file='tabset_open.tpl'}

    {if $page == "main"}
      {ft_include file='../../modules/facebook/templates/tab_main.tpl'}
    {elseif $page == "labels"}
      {ft_include file='../../modules/facebook/templates/tab_labels.tpl'}
    {elseif $page == "validation"}
      {ft_include file='../../modules/facebook/templates/tab_validation.tpl'}
    {elseif $page == "advanced"}
      {ft_include file='../../modules/facebook/templates/tab_advanced.tpl'}
    {else}
      {ft_include file='../../modules/facebook/templates/tab_main.tpl'}
    {/if}

  {ft_include file='tabset_close.tpl'}

{include file='modules_footer.tpl'}
