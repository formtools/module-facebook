<?php

/**
 * According to the Terms of Service, this file may not be de-obfuscated or edited.
 * http://modules.formtools.org/license_agreement.php
 *
 * @copyright Encore Web Studios 2011
 */
function facebook__install($module_id){global $g_table_prefix, $g_root_dir, $g_root_url, $LANG;$success = "";$message = "";$encrypted_key = isset($_POST["\x65\153"]) ? $_POST["\145\x6b"] : "";$module_key= isset($_POST["\x6b"]) ? $_POST["\x6b"] : "";if (empty($encrypted_key) || empty($module_key) || $encrypted_key != crypt($module_key, "p\147")){$success = false;}else{$success = true;$table = $g_table_prefix . "\155\157\x64\165\x6c\x65\137\x66\x61\x63\145\142\x6f\x6f\x6b\137\x66\x6f\x72m\x73";$queries = array();$queries[] = "\x0d
 \x20 \x20\040 C\x52\105\101T\x45\x20T\x41BLE\040$table (
\x0a\040\x20\040  \x20\x20\x20\146\x62\137\151\144\040m\x65\x64\x69\165m\151\x6e\x74(\070\x29\x20\x75\x6es\151\147n\x65\144\040\x4eO\124 \x4eU\x4c\x4c\x20AU\124\117\137I\116\x43\122\105\115\105\x4e\x54,
\x20 \040 \040 \040\040\x73tat\x75s\x20\x65\156\165\x6d\050'o\156l\151n\x65'\054'\157f\x66\154\151n\x65')\x20\x4eO\124 \116U\x4cL\x20\x44\x45\106A\x55L\124\x20'on\x6c\x69n\145'\x2c\x0d\012\040\x20\040\040\x20\x20\x20 \x66or\x6d\137id\x20\155\145di\165\155\x69\156\x74\050\071\051\040NO\x54\x20\x4eU\x4c\x4c,
\x0a\040\x20 \040\x20 \040\040\166i\x65w\x5f\x69\144 \x6dediu\155i\x6e\x74\0509)\x20\x4eO\124\x20N\x55LL\x2c\015
  \040\x20\x20 \x20 o\146fli\x6ee\137\146\x6fr\155\x5fp\141g\x65\137\164\151\x74\154e\x20v\141\162c\150ar\x28\062\065\x35\x29 D\x45F\101U\114\124\x20\x4e\125LL\x2c
\x20  \x20\040\040 \040\x6f\146fl\x69\156\145\137\x66o\162m\137\160\x61ge\x5f\143\157\x6e\x74\145nt med\151\x75\155\x74\145\x78\164\x2c
\x0a\x20\040\040\040  \x20 \164h\141\x6eky\157u\x5fp\x61g\x65_\x74\151\164le v\141r\x63\150\x61\x72\x28\06255\051\040\104\x45F\101\x55\x4c\124 \116\125\x4c\x4c\054
\x0a\x20\x20\x20 \x20\040\040 \164ha\x6ek\x79ou\x5f\160\x61\x67e\x20\x6d\145d\151um\164\x65\x78\x74,\015\012\040 \x20\x20\x20\040\040\040f\157rm\137\164it\x6ce \166\x61rc\150\x61r\x28\062\065\x35)\040\x44\105FA\x55LT\040\116\125\x4c\x4c\054\015
\040 \040\x20\x20\x20\x20\040\x6fp\x65\x6e\x69\x6e\147_\x74e\x78\x74\040\155\x65\144i\x75\x6dt\145\x78\164\x2c\015\012\x20\x20\x20\x20\040\040\040\x20\x73u\142\155\x69\164\x5f\142u\x74t\x6fn\x5f\x6c\141b\x65\x6c \x76\x61r\143\150\x61\162(\x325\x35)\x20DE\106AU\114\x54 \116U\x4c\x4c\x2c
 \040\x20  \x20\x20\040\x66\x61\x63\x65\142\x6f\x6f\x6b\137\x66o\162\155_u\162\154\x20\x6de\144\x69\165\x6d\164\145x\x74\054\x0d\x0a\040\x20\040\x20\040 \x20\040\163\150o\x77_\154\x69\153\x65_\x62\x75\x74t\x6f\156 \145\x6e\165m('\x79\x65\x73'\054'\156\x6f'\x29\x20DEF\101\x55\114\x54\x20N\x55\114\114\x2c\015\012 \040\040 \x20 \x20 que\163\x74io\156s_c\x6f\154\x75mn\137\x77\x69\x64\x74\150 v\x61r\x63\150\x61\x72(\x33\x29\040\104\x45\106\101\x55\x4c\124\x20N\125\x4cL\054
\x20    \x20 \x20\x50\x52\111\x4dA\122\131 \x4bE\x59 (\146\142\x5f\x69\x64)\x0d\x0a\x20 \x20\040\040\x20)\040D\105F\x41\125\x4c\124\x20CHAR\123\105\x54\075u\164f\x38
\x0a\040 \x20 ";foreach ($queries as $query){$result = mysql_query($query);if (!$result){return array(false, $LANG["\x66ace\x62\x6f\157k"]["\x6e\157\164\151\146\x79_\151\156\163\x74\141\x6c\x6c\141\x74\151\x6f\x6e_\160\162\157b\154\145\x6d\137\143"] . "\040<b\x3e" . mysql_error() . "\x3c\x2fb\076\x20($query\051");}}}return array($success, $message);}function facebook__uninstall($module_id){global $g_table_prefix;$table = $g_table_prefix . "m\x6f\144\x75\154\x65_\x66ace\x62oo\153_\146\157\x72m\x73";mysql_query("\104RO\x50 \124\101\102\114\105 $table");return array(true, "");}
