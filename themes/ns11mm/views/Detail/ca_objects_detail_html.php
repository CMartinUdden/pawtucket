<?php
/* ----------------------------------------------------------------------
 * pawtucket2/themes/default/views/Detail/ca_objects_detail_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2009-2011 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
	$t_object = 						$this->getVar('t_item');
	$vn_object_id = 					$t_object->get('object_id');
	$vs_title = 						$this->getVar('label');
	
	$va_access_values = 				$this->getVar('access_values');
	$t_rep = 							$this->getVar('t_primary_rep');
	$vn_num_reps = 						$t_object->getRepresentationCount(array("return_with_access" => $va_access_values));
	$vs_display_version =				$this->getVar('primary_rep_display_version');
	$va_display_options =				$this->getVar('primary_rep_display_options');

?>	
	<div id="detailBody">
		<div id="pageNav">
<?php
			if (($this->getVar('is_in_result_list')) && ($vs_back_link = ResultContext::getResultsLinkForLastFind($this->request, 'ca_objects', _t("Back"), ''))) {
				if ($this->getVar('previous_id')) {
					print caNavLink($this->request, "&lsaquo; "._t("Previous"), '', 'Detail', 'Object', 'Show', array('object_id' => $this->getVar('previous_id')), array('id' => 'previous'));
				}else{
					print "&lsaquo; "._t("Previous");
				}
				print "&nbsp;&nbsp;&nbsp;{$vs_back_link}&nbsp;&nbsp;&nbsp;";
				if ($this->getVar('next_id') > 0) {
					print caNavLink($this->request, _t("Next")." &rsaquo;", '', 'Detail', 'Object', 'Show', array('object_id' => $this->getVar('next_id')), array('id' => 'next'));
				}else{
					print _t("Next")." &rsaquo;";
				}
			}
?>
		</div><!-- end nav -->
		<h1><?php print $vs_title; ?></h1>
		<div id="rightCol">
<?php
		if ($t_rep && $t_rep->getPrimaryKey()) {
?>
			<div id="objDetailImage">
<?php
			if($va_display_options['no_overlay']){
				print $t_rep->getMediaTag('media', $vs_display_version, $this->getVar('primary_rep_display_options'));
			}else{
				$va_opts = array('display' => 'detail', 'object_id' => $vn_object_id, 'containerID' => 'cont');
				print "<div id='cont'>".$t_rep->getRepresentationViewerHTMLBundle($this->request, $va_opts)."</div>";
			}
?>
			</div><!-- end objDetailImage -->
			<div id="objDetailImageNav">
				<div style="float:right;">
					<!-- bookmark link BEGIN -->
<?php
					if($this->request->isLoggedIn()){
						print caNavLink($this->request, _t("+ Bookmark item"), '', '', 'Bookmarks', 'addBookmark', array('row_id' => $vn_object_id, 'tablename' => 'ca_objects'));
					}else{
						print caNavLink($this->request, _t("+ Bookmark item"), '', '', 'LoginReg', 'form', array('site_last_page' => 'Bookmarks', 'row_id' => $vn_object_id, 'tablename' => 'ca_objects'));
					}
?>					
					<!-- bookmark link END -->
<?php
					if ((!$this->request->config->get('dont_allow_registration_and_login')) && (!$this->request->config->get('disable_my_collections'))) {
						if($this->request->isLoggedIn()){
							print caNavLink($this->request, _t("+ Add to Collection"), '', '', 'Sets', 'addItem', array('object_id' => $vn_object_id));
						}else{
							print caNavLink($this->request, _t("+ Add to Collection"), '', '', 'LoginReg', 'form', array('site_last_page' => 'Sets', 'object_id' => $vn_object_id));
						}
					}

					if (($this->request->isLoggedIn()) && ($this->request->user->canDoAction('can_download_media'))) {
						print caNavLink($this->request, _t("+ Download Media"), '', 'Detail', 'Object', 'DownloadRepresentation', array('representation_id' => $t_rep->getPrimaryKey(), "object_id" => $vn_object_id), array('style' => "margin-right:20px;"));
					}

					print "<a href='#' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, 'Detail', 'Object', 'GetObjectMediaOverlay', array('object_id' => $t_object->get("object_id"), 'representation_id' => $t_rep->getPrimaryKey()))."\"); return false;' >+ ".(($vn_num_reps > 1) ? _t("Zoom/more media") : _t("Zoom"))."</a>";
?>
				</div>			
			</div><!-- end objDetailImageNav -->
<?php
		}
if (!$this->request->config->get('dont_allow_registration_and_login')) {
		# --- user data --- comments - ranking - tagging
?>			
		<div id="objUserData">
<?php
			if($this->getVar("ranking")){
?>
				<h2 id="ranking"><?php print _t("Average User Ranking"); ?> <img src="<?php print $this->request->getThemeUrlPath(); ?>/graphics/user_ranking_<?php print $this->getVar("ranking"); ?>.gif" width="104" height="15" border="0" style="margin-left: 20px;"></h2>
<?php
			}
			$va_tags = $this->getVar("tags_array");
			if(is_array($va_tags) && sizeof($va_tags) > 0){
				$va_tag_links = array();
				foreach($va_tags as $vs_tag){
					$va_tag_links[] = caNavLink($this->request, $vs_tag, '', '', 'Search', 'Index', array('search' => $vs_tag));
				}
?>
				<h2><?php print _t("Tags"); ?></h2>
				<div id="tags">
					<?php print implode($va_tag_links, ", "); ?>
				</div>
<?php
			}
			$va_comments = $this->getVar("comments");
			if(is_array($va_comments) && (sizeof($va_comments) > 0)){
?>
				<h2><div id="numComments">(<?php print sizeof($va_comments)." ".((sizeof($va_comments) > 1) ? _t("comments") : _t("comment")); ?>)</div><?php print _t("User Comments"); ?></h2>
<?php
				foreach($va_comments as $va_comment){
?>
					<div class="comment">
						<?php print $va_comment["comment"]; ?>
					</div>
					<div class="byLine">
						<?php print $va_comment["author"].", ".$va_comment["date"]; ?>
					</div>
<?php
				}
			}else{
				if(!$vs_tags && !$this->getVar("ranking")){
					$vs_login_message = _t("Login/register to be the first to rank, tag and comment on this object!");
				}
			}
			if($this->getVar("ranking") || (is_array($va_tags) && (sizeof($va_tags) > 0)) || (is_array($va_comments) && (sizeof($va_comments) > 0))){
?>
				<div class="divide" style="margin:12px 0px 10px 0px;"><!-- empty --></div>
<?php			
			}
		if($this->request->isLoggedIn()){
?>
			<h2><?php print _t("Add your rank, tags and comment"); ?></h2>
			<form method="post" action="<?php print caNavUrl($this->request, 'Detail', 'Object', 'saveCommentRanking', array('object_id' => $vn_object_id)); ?>" name="comment">
				<div class="formLabel">Rank
					<select name="rank">
						<option value="">-</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>
				<div class="formLabel"><?php print _t("Tags (separated by commas)"); ?></div>
				<input type="text" name="tags">
				<div class="formLabel"><?php print _t("Comment"); ?></div>
				<textarea name="comment" rows="5"></textarea>
				<br><a href="#" name="commentSubmit" onclick="document.forms.comment.submit(); return false;"><?php print _t("Save"); ?></a>
			</form>
<?php
		}else{
			if (!$this->request->config->get('dont_allow_registration_and_login')) {
				print "<p>".caNavLink($this->request, (($vs_login_message) ? $vs_login_message : _t("Please login/register to rank, tag and comment on this item.")), "", "", "LoginReg", "form", array('site_last_page' => 'ObjectDetail', 'object_id' => $vn_object_id))."</p>";
			}
		}
?>		
		</div><!-- end objUserData-->
<?php
	}
?>
		</div><!-- end rightCol -->		
		<div id="leftCol">
<?php
			if($this->request->config->get('show_add_this')){
?>
				<!-- AddThis Button BEGIN -->
				<div class="unit" style="margin-bottom:20px;"><a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4baa59d57fc36521"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0;"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4baa59d57fc36521"></script></div><!-- end unit -->
				<!-- AddThis Button END -->
<?php
			}
			# --- identifier
			if($t_object->get('idno')){
				print "<div class='unit'><div class='unitHeader'>"._t("Identifier")."</div> ".$t_object->get('idno')."</div><!-- end unit -->";
			}
			if($va_format = $t_object->get('ca_objects.dc_format')) {
				print "<div class='unit'><div class='unitHeader'>"._t("Format").": </div>".$va_format."</div>";
			}
			if($va_credit_line = $t_object->get('ca_objects.credit_line')) {
				print "<div class='unit'><div class='unitHeader'>"._t("Credit Line")." </div>".$va_credit_line."</div>";
			}
			# --- description
			if($this->request->config->get('ca_objects_description_attribute')){
				if($vs_description_text = $t_object->get("ca_objects.".$this->request->config->get('ca_objects_description_attribute'))){
					print "<div class='unit' id='description'><div class='unitHeader'>Description </div> {$vs_description_text}</div><!-- end unit -->";				
?>
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('#description').expander({
								slicePoint: 300,
								expandText: '<?php print _t('[more]'); ?>',
								userCollapseText: '<?php print _t('[less]'); ?>'
							});
							jQuery('#historical').expander({
								slicePoint: 300,
								expandText: '<?php print _t('[more]'); ?>',
								userCollapseText: '<?php print _t('[less]'); ?>'
							});
						});
					</script>
<?php
				}
			}
			if($va_historical_notes = $t_object->get('ca_objects.public_historical_notes')) {
				print "<div class='unit' id='historical'><div class='unitHeader'>"._t("Historical Notes")." </div>".$va_historical_notes."</div>";
			}	
			if($va_date = $t_object->get('ca_objects.dates_value')) {
				print "<div class='unit'><div class='unitHeader'>"._t("Date")." </div>".$va_date."</div>";
			}		
			if($va_dimensions = $t_object->get('ca_objects.dimensions_as_text')) {
				print "<div class='unit'><div class='unitHeader'>"._t("Dimensions")." </div>".$va_dimensions."</div>";
			}
			# --- parent hierarchy info
			if($t_object->get('parent_id')){
				print "<div class='unit'><b>"._t("Part Of")."</b>: ".caNavLink($this->request, $t_object->get("ca_objects.parent.preferred_labels.name"), '', 'Detail', 'Object', 'Show', array('object_id' => $t_object->get('parent_id')))."</div>";
			}
			# --- attributes
			$va_attributes = $this->request->config->get('ca_objects_detail_display_attributes');
			if(is_array($va_attributes) && (sizeof($va_attributes) > 0)){
				foreach($va_attributes as $vs_attribute_code){
					if($vs_value = $t_object->get("ca_objects.{$vs_attribute_code}")){
						print "<div class='unit'><b>".$t_object->getDisplayLabel("ca_objects.{$vs_attribute_code}").":</b> {$vs_value}</div><!-- end unit -->";
					}
				}
			}
			# --- child hierarchy info
			$va_children = $t_object->get("ca_objects.children.preferred_labels", array('returnAsArray' => 1, 'checkAccess' => $va_access_values));
			if(sizeof($va_children) > 0){
				print "<div class='unit'><h2>"._t("Part%1", ((sizeof($va_children) > 1) ? "s" : ""))."</h2> ";
				$i = 0;
				foreach($va_children as $va_child){
					# only show the first 5 and have a more link
					if($i == 5){
						print "<div id='moreChildrenLink'><a href='#' onclick='$(\"#moreChildren\").slideDown(250); $(\"#moreChildrenLink\").hide(1); return false;'>["._t("More")."]</a></div><!-- end moreChildrenLink -->";
						print "<div id='moreChildren' style='display:none;'>";
					}
					print "<div>".caNavLink($this->request, $va_child['name'], '', 'Detail', 'Object', 'Show', array('object_id' => $va_child['object_id']))."</div>";
					$i++;
					if($i == sizeof($va_children)){
						print "</div><!-- end moreChildren -->";
					}
				}
				print "</div><!-- end unit -->";
			}
			# --- entities
			$va_entities = $t_object->get("ca_entities", array("returnAsArray" => 1, 'checkAccess' => $va_access_values, 'sort' => 'surname'));
			if(sizeof($va_entities) > 0){	
?>
				<div class="unit"><div class='unitHeader'><?php print _t("Related")." ".((sizeof($va_entities) > 1) ? _t("Entities") : _t("Entity")); ?></div>
<?php
				foreach($va_entities as $va_entity) {
					print "<div id='item".$va_entity["idno"]."'>".caNavLink($this->request, $va_entity["label"], '', '', 'Search', 'Index', array('search' => $va_entity["label"]))."</div>";
				
					if($va_entity['displayname'] || $va_entity['relationship_typename']){
						// set view vars for tooltip
						if ($va_entity['displayname']) {
							$this->setVar('tooltip_displayname', $va_entity['displayname']);
						}
						if ($va_entity['relationship_typename'] == "donor - web") {
							$this->setVar('tooltip_relationship', "Related Person");
						} else if ($va_entity['relationship_typename'] == "victim - web") {
							$this->setVar('tooltip_relationship', "Victim");
						} else if ($va_entity['relationship_typename'] == "creator - web") {
							$this->setVar('tooltip_relationship', "Creator");
						}
						
						if ($t_object->get("ca_entities.lifespan")) {
							$this->setVar('tooltip_lifespan', $t_object->get("ca_entities.lifespan"));
						}
						if ($t_object->get("ca_entities.residence")) {
							$this->setVar('tooltip_residence', $t_object->get("ca_entities.residence"));
						}
						if ($t_object->get("ca_entities.birthplace")) {
							$this->setVar('tooltip_birthplace', $t_object->get("ca_entities.birthplace"));
						}
						$t_entity = new ca_entities($va_entity["entity_id"]);
						if ($t_entity->get("ca_entities", array('restrict_to_relationship_types' => array('employer')))) {
							$this->setVar('tooltip_employer', $t_entity->get("ca_entities", array('restrict_to_relationship_types' => array('employer'))));
						}						
						if ($t_object->get("ca_entities.occupation")) {
							$this->setVar('tooltip_occupation', $t_object->get("ca_entities.occupation"));
						}
						if ($t_entity->get("ca_places.preferred_labels.name", array('restrict_to_relationship_types' => array('residence')))) {
							$this->setVar('tooltip_floor', $t_entity->get("ca_places.preferred_labels.name", array('restrict_to_relationship_types' => array('workplace'))));
						}						
						
						TooltipManager::add(
							"#item{$va_entity['idno']}", $this->render('tooltip_html.php')
						);
					}
				}
?>
				</div><!-- end unit -->
<?php
			}
			
			# --- places
			$va_places = $t_object->get("ca_places", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
			
			if(sizeof($va_places) > 0){
				print "<div class='unit'><div class='unitHeader'>"._t("Related Place").((sizeof($va_places) > 1) ? "s" : "")."</div>";
				foreach($va_places as $va_place_info){
					print "<div>".caNavLink($this->request, $va_place_info['label'], '', '', 'Search', 'Index', array('search' => $va_place_info['label']))."</div>";
				}
				print "</div><!-- end unit -->";
			}
			# --- collections
			$va_collections = $t_object->get("ca_collections", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
			if(sizeof($va_collections) > 0){
				print "<div class='unit'><h2>"._t("Related Collection").((sizeof($va_collections) > 1) ? "s" : "")."</h2>";
				foreach($va_collections as $va_collection_info){
					print "<div>".(($this->request->config->get('allow_detail_for_ca_collections')) ? caNavLink($this->request, $va_collection_info['label'], '', 'Detail', 'Collection', 'Show', array('collection_id' => $va_collection_info['collection_id'])) : $va_collection_info['label'])." (".$va_collection_info['relationship_typename'].")</div>";
				}
				print "</div><!-- end unit -->";
			}
			# --- vocabulary terms
			$va_terms = $t_object->get("ca_list_items", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
			if(sizeof($va_terms) > 0){
				print "<div class='unit'><div class='unitHeader'>"._t("Subject").((sizeof($va_terms) > 1) ? "s" : "")."</div>";
				foreach($va_terms as $va_term_info){
					print "<div>".caNavLink($this->request, ucwords($va_term_info['label']), '', '', 'Search', 'Index', array('search' => $va_term_info['label']))."</div>";
				}
				print "</div><!-- end unit -->";
			}
			# --- map
			if($this->request->config->get('ca_objects_map_attribute') && $t_object->get($this->request->config->get('ca_objects_map_attribute'))){
				$o_map = new GeographicMap(300, 200, 'map');
				$o_map->mapFrom($t_object, $this->request->config->get('ca_objects_map_attribute'));
				print "<div class='unit'>".$o_map->render('HTML')."</div>";
			}			
			# --- output related object images as links
			$va_related_objects = $t_object->get("ca_objects", array("returnAsArray" => 1, 'checkAccess' => $va_access_values));
			if (sizeof($va_related_objects)) {
				print "<div class='unit'><div class='unitHeader'>"._t("Related Objects")."</div>";
				print "<table border='0' cellspacing='0' cellpadding='0' width='100%' id='objDetailRelObjects'>";
				$col = 0;
				$vn_numCols = 4;
				foreach($va_related_objects as $vn_rel_id => $va_info){
					$t_rel_object = new ca_objects($va_info["object_id"]);
					$va_reps = $t_rel_object->getPrimaryRepresentation(array('icon', 'small'), null, array('return_with_access' => $va_access_values));
					if($col == 0){
						print "<tr>";
					}
					print "<td align='center' valign='middle' class='imageIcon icon".$va_info["object_id"]."'>";
					print caNavLink($this->request, $va_reps['tags']['icon'], '', 'Detail', 'Object', 'Show', array('object_id' => $va_info["object_id"]));
					
					// set view vars for tooltip
					$this->setVar('tooltip_representation', $va_reps['tags']['small']);
					$this->setVar('tooltip_title', $va_info['label']);
					$this->setVar('tooltip_idno', $va_info["idno"]);
					TooltipManager::add(
						".icon".$va_info["object_id"], $this->render('../Results/ca_objects_result_tooltip_html.php')
					);
					
					print "</td>";
					$col++;
					if($col < $vn_numCols){
						print "<td align='center'><!-- empty --></td>";
					}
					if($col == $vn_numCols){
						print "</tr>";
						$col = 0;
					}
				}
				if(($col != 0) && ($col < $vn_numCols)){
					while($col <= $vn_numCols){
						if($col < $vn_numCols){
							print "<td><!-- empty --></td>";
						}
						$col++;
						if($col < $vn_numCols){
							print "<td align='center'><!-- empty --></td>";
						}
					}
				}
				print "</table></div><!-- end unit -->";
			}
?>
		</div><!-- end leftCol-->

	</div><!-- end detailBody -->
<?php
	require_once(__CA_LIB_DIR__.'/core/Parsers/COinS.php');
	
	print COinS::getTags($t_object);
	
	
?>
