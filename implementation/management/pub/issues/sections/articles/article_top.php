<?php
require_once($_SERVER['DOCUMENT_ROOT']."/classes/common.php");
require_once($_SERVER['DOCUMENT_ROOT']."/$ADMIN_DIR/lib_campsite.php");

/**
 * Common header for all article screens.
 *
 * @param Article p_articleObj
 *		The article that is being displayed.
 *
 * @param int p_interfaceLanguageId
 *		The language for the Issue that that article is contained within.
 *
 * @param string p_title
 *		The title of the page.  This should have a translation in the language files.
 *
 * @param boolean p_includeLinks
 *		Whether to include the links underneath the title or not.  Default TRUE.
 *
 * @return void
 */
function ArticleTop($p_articleObj, $p_interfaceLanguageId, $p_title, $p_includeLinks = true, $p_fValidate = false) {
	global $Campsite;
	global $ADMIN;
	
    // Fetch section
    $sectionObj =& new Section($p_articleObj->getPublicationId(), 
    	$p_articleObj->getIssueId(), 
    	$p_interfaceLanguageId,
    	$p_articleObj->getSectionId());
    	
    // Fetch issue
    $issueObj =& new Issue($p_articleObj->getPublicationId(), 
    	$p_interfaceLanguageId, 
    	$p_articleObj->getIssueId());

    // Fetch publication
    $publicationObj =& new Publication($p_articleObj->getPublicationId());
    
    $articleLanguageObj =& new Language($p_articleObj->getLanguageId());
    $interfaceLanguageObj =& new Language($p_interfaceLanguageId);

	?>
<HEAD>
	<LINK rel="stylesheet" type="text/css" href="<?php echo $Campsite["website_url"] ?>/css/admin_stylesheet.css">
	<?php if ($p_fValidate) { ?>
	<script type="text/javascript" src="<?php echo $Campsite["website_url"] ?>/javascript/fValidate/fValidate.config.js"></script>
    <script type="text/javascript" src="<?php echo $Campsite["website_url"] ?>/javascript/fValidate/fValidate.core.js"></script>
    <script type="text/javascript" src="<?php echo $Campsite["website_url"] ?>/javascript/fValidate/fValidate.lang-enUS.js"></script>
    <script type="text/javascript" src="<?php echo $Campsite["website_url"] ?>/javascript/fValidate/fValidate.validators.js"></script>	
	<?php } ?>
	<TITLE><?php putGS($p_title); ?></TITLE>
</HEAD>

<BODY BGCOLOR="WHITE" TEXT="BLACK" LINK="DARKBLUE" ALINK="RED" VLINK="DARKBLUE">

<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="1" WIDTH="100%" class="page_title_container">
<TR>
	<TD class="page_title">
	    <?php putGS($p_title); ?>
	</TD>
<?php 
if ($p_includeLinks) {
?>
	<TD ALIGN="right" style="padding-right: 10px; padding-top: 0px;">
		<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="0">
		<TR>
			<!-- "Articles" Link -->
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/sections/articles/?Pub=<?php p($p_articleObj->getPublicationId()); ?>&Issue=<?php p($p_articleObj->getIssueId()); ?>&Language=<?php p($p_interfaceLanguageId); ?>&Section=<?php p($p_articleObj->getSectionId()); ?>" ><IMG SRC="/<?php echo $ADMIN; ?>/img/tol.gif" BORDER="0" ALT="<?php putGS("Articles"); ?>"></A></TD>
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/sections/articles/?Pub=<?php p($p_articleObj->getPublicationId()); ?>&Issue=<?php p($p_articleObj->getIssueId()); ?>&Language=<?php p($p_interfaceLanguageId); ?>&Section=<?php p($p_articleObj->getSectionId()); ?>" ><B><?php putGS("Articles");  ?></B></A></TD>
			
			<!-- "Sections" link -->
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/sections/?Pub=<?php p($p_articleObj->getPublicationId()); ?>&Issue=<?php p($p_articleObj->getIssueId()); ?>&Language=<?php p($p_interfaceLanguageId); ?>"><IMG SRC="/<?php echo $ADMIN; ?>/img/tol.gif" BORDER="0" ALT="<?php putGS("Sections"); ?>"></A></TD>
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/sections/?Pub=<?php p($p_articleObj->getPublicationId()); ?>&Issue=<?php p($p_articleObj->getIssueId()); ?>&Language=<?php p($p_interfaceLanguageId); ?>"><B><?php putGS("Sections"); ?></B></A></TD>
			
			<!-- "Issues" Link -->
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/?Pub=<?php p($p_articleObj->getPublicationId()); ?>" ><IMG SRC="/<?php echo $ADMIN; ?>/img/tol.gif" BORDER="0" ALT="<?php putGS("Issues"); ?>"></A></TD>
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/issues/?Pub=<?php p($p_articleObj->getPublicationId()); ?>"><B><?php putGS("Issues"); ?></B></A></TD>
			
			<!-- "Publications" Link -->
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/" ><IMG SRC="/<?php echo $ADMIN; ?>/img/tol.gif" BORDER="0" ALT="<?php  putGS("Publications"); ?>"></A></TD>
			<TD><A HREF="/<?php echo $ADMIN; ?>/pub/" ><B><?php  putGS("Publications");  ?></B></A></TD>
			
		</TR>
		</TABLE>
	</TD>
<?php
} // if ($p_includeLinks)
?>
</TR>
</TABLE>

<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="1" WIDTH="100%">
<TR>
	<TD ALIGN="RIGHT" NOWRAP VALIGN="TOP" width="1%">&nbsp;<?php putGS("Publication"); ?>:</TD>
	<TD BGCOLOR="#D0D0B0" VALIGN="TOP"><B><?php print htmlspecialchars($publicationObj->getName()); ?></B></TD>

	<TD ALIGN="RIGHT" NOWRAP VALIGN="TOP" width="1%">&nbsp;<?php putGS("Issue"); ?>:</TD>
	<TD BGCOLOR="#D0D0B0" VALIGN="TOP"><B><?php print htmlspecialchars($issueObj->getIssueId()); ?>. <?php  print htmlspecialchars($issueObj->getName()); ?> (<?php print htmlspecialchars($interfaceLanguageObj->getName()) ?>)</B></TD>

	<TD ALIGN="RIGHT" NOWRAP VALIGN="TOP" width="1%">&nbsp;<?php putGS("Section"); ?>:</TD>
	<TD BGCOLOR="#D0D0B0" VALIGN="TOP"><B><?php print $sectionObj->getSectionId(); ?>. <?php  print htmlspecialchars($sectionObj->getName()); ?></B></TD>

	<TD ALIGN="RIGHT" NOWRAP VALIGN="TOP" width="1%">&nbsp;<?php putGS("Article"); ?>:</TD>
	<TD BGCOLOR="#D0D0B0" VALIGN="TOP"><B><?php print htmlspecialchars($p_articleObj->getTitle()); ?> (<?php print htmlspecialchars($articleLanguageObj->getName()); ?>)</B></TD>
</TR>
</TABLE>
	<?php
} // fn article_top
?>
