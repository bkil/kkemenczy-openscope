<?php

require_once ('functions/misc.php');
require_once ('config.inc.php');

putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
bindtextdomain("tm", $localedir);
textdomain("tm");

header("Content-Type: text/html; charset=UTF-8");
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

$split_keyword = split(' = ',$_GET['keyword']);
$keyword = $split_keyword['0'];

?>
<html>
<head profile="http://a9.com/-/spec/opensearch/1.1/">
<title><?php print $prod_name; ?></title>
<meta http-equiv="generator" content="glosar-0.2">
<script type='text/javascript' src='js/server.php?client'></script>
<script type='text/javascript' src='js/glossary.js'></script>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="alternate" type="application/rss+xml" title="<?php echo _("Recent changes - RSS Feed"); ?>" href="<?php print $base_url; ?>recent.rss2.php" />
<link rel="alternate" type="application/rss+xml" title="<?php echo _("Unfixed terms - RSS Feed"); ?>" href="<?php print $base_url; ?>unfixed.rss2.php" />
<link rel="search" type="application/opensearchdescription+xml" href="<?php echo _("http://localhost/glosar/glosar-search.xml"); ?>" title="<?php echo _("Terminology search") ?>" />
</head>
<body>
<center> 
<style>
body, td, th { font-family: arial; font-size: 13px; }
#content { width:740px; text-align:left }
h1 { color: #aaa; font-weight: normal; font-size: 26px; text-align: center}
h2 { color: #aaa; font-weight: normal; font-size: 18px; margin-bottom: 5px}
ul { margin-top: 1px }
table { background: #ccc }
td { background: #eee; padding: 3px; font-size: 12px;}
a, a:visited { color: #00a }
</style>
<div id="content">

<h1><?php print $prod_name.' '.$prod_ver?></h1>

<?php

if(!$username) {
    if ($permit_anon_edit){
	echo "<p style='color: green;'>" . _("You are free to modify the content. Please login, for a better team communication.") . "</p>";
    } else {
        echo "<p style='color: red;'>" . _("You need to log in in order to modify the content.") . "</p>";
    }
} else {
    echo "<p style='color: green;'>" . _("Changes are made under the username: <i>".$username."</i>.") . "</p>";
}

?>


<div id="count"></div>

<?php echo _("Term:");?> <input type="text" id="keywordField" value="<?php print $keyword; ?>" autocomplete="off">

<input type="button" id="editButton" value="<?php echo _("Edit") ?>" onClick="showDefinitionDiv()">

<div id="definitionDiv" style="display:none">
<table>
<tr><th><?php echo _("Edit translation");?></th></tr>
<tr><td class="hint"><?php echo _("Please use this order: <i>verb inf.</i>, <i>noun</i> (ex: to edit, edit)");?></td></tr>
<tr><td><input type="text" id="definitionField"></td></tr>
<tr><td class="hint">
<?php echo _("Meta information:"); ?> 
<ul>
<?php 
echo _("<li><tt>[[WikiWord]]</tt> create a link to a wiki page, used for discussions</li>");
echo _("<li><tt  class='term_locked'>!</tt> - lock a <span class='term_locked'>stable</span> translation</li>");
echo _("<li><tt class='term_debate'>?</tt> - tag the translation as fuzzy <span class='term_debate'>debate</span></li>");
?>
</ul>
</td></tr>
<tr><td><input type="text" id="metaField"></td></tr>
<tr>
<td align="right">
<input type="button" value="<?php echo _("Cancel"); ?>" onClick="cancel()">

<?php
/*
 * Show save button only when allowed
 */
if (($username)||($permit_anon_edit)) { 
?>
<input type="button" value="<?php echo _("Save");?>" onClick="save()">
<?php 
} 


/*
 * Show delete button only when authenticated users
 */
if (($username)) { 
?>
<input type="button" value="<?php echo _("Delete");?>" onClick="save()">
<?php 
} 
?>

</td></tr>
</table>

</div>

<div id="results"></div>

<br/>
<div id="content">

<h1><?php echo _("About this dictionary"); ?></h1>
<br/>
<?php echo _("This glosary is used for translationg „free” and „open source” software by the localization teams of our language. ");?>
<?php printf(_("Everybody can contribute, the system is based on the <a href=%s>Wiki</a> idea. (each change is saved)."), "\"http://wiki.org/wiki.cgi?WhatIsWiki\""); ?>
<p>
<?php echo _("This page requires JavaScript suport."); ?> 

<h2><?php echo _("Downloading raw data"); ?></h2>
<?php printf(_("The content is licenced under <a href=%s>GPL</a> and can be downloaded from here:"), "\"http://www.gnu.org/copyleft/gpl.html\""); ?> <a href="data/glosar.txt">glosar.txt</a>.

<h2><?php echo _("Translation teams for our language"); ?> </h2>
<!--fixme: add moar -->
<a href="<?php echo _("http://wiki.services.openoffice.org/wiki/Languages"); ?>"><?php echo _("OpenOffice.org");?></a>,
<a href="<?php echo _("https://wiki.mozilla.org/Category:L10n_Teams"); ?>"><?php echo _("Mozilla");?></a>,
<a href="<?php echo _("http://l10n.gnome.org/teams/"); ?>"><?php echo _("GNOME");?></a>,
<a href="<?php echo _("http://i18n.kde.org/teams-list.php"); ?>"><?php echo _("KDE");?></a>,
<a href="<?php echo _("http://i18n.xfce.org/wiki/language_maintainers"); ?>"><?php echo _("Xfce");?></a>,
<a href="<?php echo _("https://translations.launchpad.net/+groups/ubuntu-translators"); ?>"><?php echo _("Ubuntu"); ?></a>
<a href="<?php echo _("http://en.opensuse.org/OpenSUSE_Localization_Teams"); ?>"><?php echo _("openSUSE");?></a>,
<a href="<?php echo _("https://translate.fedoraproject.org/teams/"); ?>"><?php echo _("Fedora");?></a>,
<a href="<?php echo _("http://debian.org/international/"); ?>"><?php echo _("Debian");?></a>

<h2><?php echo _("Source code"); ?></h2>
<?php printf(_("Source code is licensed under <a href=%s>GPL</a>, you can dowload the latest version from <a href=%s>here</a>."), 
"\"http://www.gnu.org/copyleft/gpl.html\"", "\"https://code.launchpad.net/~ubuntu-l10n-hu/ubuntu-hu-web/glossary\""); ?>

<hr noshade size=1>
</div>

<div id="help">
    <a href="recent.php"><?php echo _("Recent changes");?></a> [ <a href="recent.rss2.php">RSS2</a> ] &middot; 
    <a href="report.php"><?php echo _("unfixed terms");?></a> [<a href="unfixed.rss2.php">RSS2</a>] &middot;
    <a href="report.php?type=fixed"><?php echo _("fixed terms");?></a> &middot;
    <a href="report.php?type=unsorted"><?php echo _("unsorted terms");?></a> 
</br><a href="changelog.php"><?php echo _("Changelog");?></a> &middot;
    <a href="export.php"><?php echo _("Export to po");?></a></div>

</div>

<script>
setup();
</script>
</center>
</body>
</html>
