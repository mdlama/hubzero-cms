<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Christopher Smoak <csmoak@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

$this->css()
     ->js()
     ->js('jquery.cycle2', 'system');

//tag editor
JPluginHelper::importPlugin( 'hubzero' );
$dispatcher = JDispatcher::getInstance();
$tf = $dispatcher->trigger( 'onGetMultiEntry', array(array('tags', 'tags', 'actags','', $this->tags)) );

//are we using the email gateway for group forum
$params =  JComponentHelper::getParams('com_groups');
$allowEmailResponses = $params->get('email_comment_processing');
$autoEmailResponses  = $params->get('email_member_groupsidcussionemail_autosignup');

//default logo
$default_logo = DS.'components'.DS.$this->option.DS.'assets'.DS.'img'.DS.'group_default_logo.png';

//access levels
$levels = array(
	//'anyone' => 'Enabled/On',
	'anyone' => 'Any HUB Visitor',
	'registered' => 'Only Registered User of the HUB',
	'members' => 'Only Group Members',
	'nobody' => 'Disabled/Off'
);

//build back link
$host = JRequest::getVar("HTTP_HOST", "", "SERVER");
$referrer = JRequest::getVar("HTTP_REFERER", "", "SERVER");

//check to make sure referrer is a valid url
//check to make sure the referrer is a link within the HUB
if (filter_var($referrer, FILTER_VALIDATE_URL) === false || $referrer == "" || strpos($referrer, $host) === false)
{
	$link = JRoute::_('index.php?option='.$this->option);
}
else
{
	$link = $referrer;
}

//if we are in edit mode we want to redirect back to group
if ($this->task == "edit")
{
	$link = JRoute::_('index.php?option='.$this->option.'&cn='.$this->group->get('cn'));
	$title = "Back to Group";
}
else
{
	$title = "Back";
}
?>
<header id="content-header">
	<h2><?php echo $this->title; ?></h2>

	<div id="content-header-extra">
		<ul id="useroptions">
			<li class="last">
				<a class="btn icon-group" href="<?php echo $link; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
			</li>
		</ul>
	</div><!-- / #content-header-extra -->
</header>

<section class="main section">
	<?php
		foreach ($this->notifications as $notification) {
			echo "<p class=\"{$notification['type']}\">{$notification['message']}</p>";
		}
	?>

	<?php if ($this->task != 'new' && !$this->group->get('published')) : ?>
		<p class="warning">
			<?php echo JText::_('COM_GROUPS_PENDING_APPROVAL_WARNING'); ?>
		</p>
	<?php endif; ?>

	<form action="index.php" method="post" id="hubForm" class="full stepper">
		<div class="grid">
			<div class="col span8">
				<fieldset>
					<legend><?php echo JText::_('COM_GROUPS_DETAILS_FIELD_TITLE'); ?></legend>

					<?php if ($this->task != 'new') : ?>
						<input name="cn" type="hidden" value="<?php echo $this->group->get('cn'); ?>" />
					<?php else : ?>
						<label class="group_cn_label">
							<?php echo JText::_('COM_GROUPS_DETAILS_FIELD_CN'); ?> <span class="required"><?php echo JText::_('COM_GROUPS_REQUIRED'); ?></span>
							<input name="cn" id="group_cn_field" type="text" size="35" value="<?php echo $this->group->get('cn'); ?>" autocomplete="off" />
							<span class="hint"><?php echo JText::_('COM_GROUPS_DETAILS_FIELD_CN_HINT'); ?></span>
						</label>
					<?php endif; ?>
					<label>
						<?php echo JText::_('COM_GROUPS_DETAILS_FIELD_DESCRIPTION'); ?> <span class="required"><?php echo JText::_('COM_GROUPS_REQUIRED'); ?></span>
						<input type="text" name="description" size="35" value="<?php echo stripslashes($this->group->get('description')); ?>" />
					</label>
					<label>
						<?php echo JText::_('COM_GROUPS_DETAILS_FIELD_TAGS'); ?> <span class="optional"><?php echo JText::_('COM_GROUPS_OPTIONAL'); ?></span>
						<?php if (count($tf) > 0) {
							echo $tf[0];
						} else { ?>
							<input type="text" name="tags" value="<?php echo $this->tags; ?>" />
						<?php } ?>

						<span class="hint"><?php echo JText::_('COM_GROUPS_DETAILS_FIELD_TAGS_HINT'); ?></span>
					</label>

					<label for="public_desc">
						<?php echo JText::_('COM_GROUPS_DETAILS_FIELD_PUBLIC'); ?> <span class="optional"><?php echo JText::_('COM_GROUPS_OPTIONAL'); ?></span>

						<?php
							$editor = \JFactory::getEditor();
							echo $editor->display('public_desc', $this->escape($this->group->getDescription('raw', 0, 'public')), '', '', 35, 8, false, 'public_desc', null, null, array('class' => 'minimal no-footer macros'));
						?>
					</label>
					<label for="private_desc">
						<?php echo JText::_('COM_GROUPS_DETAILS_FIELD_PRIVATE'); ?> <span class="optional"><?php echo JText::_('COM_GROUPS_OPTIONAL'); ?></span>
						<?php
							echo $editor->display('private_desc', $this->escape($this->group->getDescription('raw', 0, 'private')), '', '', 35, 8, false, 'private_desc', null, null, array('class' => 'minimal no-footer macros'));
						?>
					</label>
				</fieldset>
				
				<?php if ($this->task != 'new') : ?>
					<fieldset>
						<legend>Logo</legend>
						<p>Upload your logo using the file upload browser to the right then select it in the drop down below.</p>
						<?php if ($this->group->isSuperGroup()) : ?>
							<p class="info">Setting a group logo for a super group will not update the logo in the template (seen when viewing the group). This logo is used primarily for branding purposes in resources, courses, &amp; other areas on the hub.</p>
						<?php endif; ?>
						<label id="group-logo-label">
							<select name="group[logo]" id="group_logo" rel="<?php echo $this->group->get('gidNumber'); ?>">
								<option value="">Select a group logo...</option>
								<?php foreach ($this->logos as $logo) { ?>
									<?php
										$remove = JPATH_SITE . DS . 'site' . DS . 'groups' . DS . $this->group->get('gidNumber') . DS . 'uploads' . DS;
										$sel = (str_replace($remove,"",$logo) == $this->group->get('logo')) ? 'selected' : '';
									?>
									<option <?php echo $sel; ?> value="<?php echo str_replace(JPATH_SITE,"",$logo); ?>"><?php echo str_replace($remove,"",$logo); ?></option>
								<?php } ?>
							</select>
						</label>
						<label>
							<div class="preview" id="logo">
								<div id="logo_picked">
									<?php if ($this->group->get('logo')) { ?>
										<img src="/site/groups/<?php echo $this->group->get('gidNumber'); ?>/uploads/<?php echo $this->group->get('logo'); ?>" alt="<?php echo $this->group->get('cn') ?>" />
									<?php } else { ?>
										<img src="<?php echo $default_logo; ?>" alt="<?php echo $this->group->get('cn') ?>" >
									<?php } ?>
								</div>
							</div>
						</label>
					</fieldset>
				<?php endif; ?>

				<fieldset>
					<legend><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_TITLE'); ?></legend>
					<p><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_DESC'); ?></p>
					<fieldset>
						<legend><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_LEGEND'); ?> <span class="required"><?php echo JText::_('COM_GROUPS_REQUIRED'); ?></span></legend>
						<label>
							<input type="radio" class="option" name="join_policy" value="0"<?php if ($this->group->get('join_policy') == 0) { echo ' checked="checked"'; } ?> />
							<strong><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_OPEN_SETTING'); ?></strong>
							<br /><span class="indent"><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_OPEN_SETTING_DESC'); ?></span>
						</label>
						<label>
							<input type="radio" class="option" name="join_policy" value="1"<?php if ($this->group->get('join_policy') == 1) { echo ' checked="checked"'; } ?> />
							<strong><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_RESTRICTED_SETTING'); ?></strong>
							<br /><span class="indent"><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_RESTRICTED_SETTING_DESC'); ?></span>
						</label>
						<label class="indent">
							<strong><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_RESTRICTED_SETTING_CREDENTIALS'); ?></strong>
							(<?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_RESTRICTED_SETTING_CREDENTIALS_DESC'); ?>) <span class="optional"><?php echo JText::_('COM_GROUPS_OPTIONAL'); ?></span>
							<textarea name="restrict_msg" rows="5" cols="50"><?php echo htmlentities(stripslashes($this->group->get('restrict_msg'))); ?></textarea>
						</label>
						<label>
							<input type="radio" class="option" name="join_policy" value="2"<?php if ($this->group->get('join_policy') == 2) { echo ' checked="checked"'; } ?> />
							<strong><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_INVITE_SETTING'); ?></strong>
							<br /><span class="indent"><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_INVITE_SETTING_DESC'); ?></span>
						</label>
						<label>
							<input type="radio" class="option" name="join_policy" value="3"<?php if ($this->group->get('join_policy') == 3) { echo ' checked="checked"'; } ?> />
							<strong><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_CLOSED_SETTING'); ?></strong>
							<br /><span class="indent"><?php echo JText::_('COM_GROUPS_MEMBERSHIP_SETTINGS_CLOSED_SETTING_DESC'); ?></span>
						</label>
					</fieldset>
				</fieldset>

				<fieldset>
					<legend><?php echo JText::_('COM_GROUPS_PRIVACY_SETTINGS_TITLE'); ?></legend>
					<p><?php echo JText::_('COM_GROUPS_PRIVACY_SETTINGS_DESC'); ?></p>
					<fieldset>
						<legend><?php echo JText::_('COM_GROUPS_DISCOVERABILITY_SETTINGS_LEGEND'); ?> <span class="required"><?php echo JText::_('COM_GROUPS_REQUIRED'); ?></span></legend>
						<label>
							<input type="radio" class="option" name="discoverability" value="0"<?php if ($this->group->get('discoverability') == 0) { echo ' checked="checked"'; } ?> />
							<strong><?php echo JText::_('COM_GROUPS_DISCOVERABILITY_SETTINGS_VISIBLE_SETTING'); ?></strong>
							<br /><span class="indent"><?php echo JText::_('COM_GROUPS_DISCOVERABILITY_SETTINGS_VISIBLE_SETTING_DESC'); ?></span>
						</label>
						<label>
							<input type="radio" class="option" name="discoverability" value="1"<?php if ($this->group->get('discoverability') == 1) { echo ' checked="checked"'; } ?> />
							<strong><?php echo JText::_('COM_GROUPS_DISCOVERABILITY_SETTINGS_HIDDEN_SETTING'); ?></strong>
							<br /><span class="indent"><?php echo JText::_('COM_GROUPS_DISCOVERABILITY_SETTINGS_HIDDEN_SETTING_DESC'); ?></span>
						</label>
					</fieldset>

					<fieldset>
						<legend><?php echo JText::_('COM_GROUPS_ACCESS_SETTINGS_TITLE'); ?></legend>
						<p><?php echo JText::_('COM_GROUPS_ACCESS_SETTINGS_DESC'); ?></p>

						<fieldset class="preview">
							<legend>Set Permissions for each Tab</legend>
							<ul id="access">
								<img src="<?php echo $default_logo; ?>" alt="<?php echo $this->group->get('cn') ?>" >
								<?php for ($i=0; $i<count($this->hub_group_plugins); $i++) { ?>
									<?php if ($this->hub_group_plugins[$i]['display_menu_tab']) { ?>
										<li class="group_access_control_<?php echo strtolower($this->hub_group_plugins[$i]['title']); ?>">
											<input type="hidden" name="group_plugin[<?php echo $i; ?>][name]" value="<?php echo $this->hub_group_plugins[$i]['name']; ?>">
											<span class="menu_item_title"><?php echo $this->hub_group_plugins[$i]['title']; ?></span>
											<select name="group_plugin[<?php echo $i; ?>][access]">
												<?php foreach ($levels as $level => $name) { ?>
													<?php $sel = ($this->group_plugin_access[$this->hub_group_plugins[$i]['name']] == $level) ? 'selected' : ''; ?>
													<?php if (($this->hub_group_plugins[$i]['name'] == 'overview' && $level != 'nobody') || $this->hub_group_plugins[$i]['name'] != 'overview') { ?>
														<option <?php echo $sel; ?> value="<?php echo $level; ?>"><?php echo $name; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</li>
									<?php } ?>
								<?php } ?>
							</ul>
						</fieldset>
					</fieldset>
				</fieldset>

				<?php if ($allowEmailResponses) : ?>
					<fieldset>
					<legend><?php echo JText::_('COM_GROUPS_EMAIL_SETTINGS_TITLE'); ?></legend>
					<p><?php echo JText::_('COM_GROUPS_EMAIL_SETTINGS_DESC'); ?></p>
						<fieldset>
							<legend><?php echo JText::_('COM_GROUPS_EMAIL_SETTING_FORUM_SECTION_LEGEND'); ?> <span class="optional"><?php echo JText::_('COM_GROUPS_OPTIONAL'); ?></span></legend>
							<label>
								<input type="checkbox" class="option" name="discussion_email_autosubscribe" value="1"
									<?php if ($this->group->get('discussion_email_autosubscribe', null) == 1
											|| ($this->group->get('discussion_email_autosubscribe', null) == null && $autoEmailResponses)) { echo ' checked="checked"'; } ?> />
								<strong><?php echo JText::_('COM_GROUPS_EMAIL_SETTING_FORUM_AUTO_SUBSCRIBE'); ?></strong> <br />
								<span class="indent">
									<?php echo JText::_('COM_GROUPS_EMAIL_SETTINGS_FORUM_AUTO_SUBSCRIBE_NOTE'); ?>
								</span>
							</label>
						</fieldset>
					</fieldset>
				<?php endif; ?>
			</div>

			<div class="col span4 omega floating-iframe-col">
				<?php if ($this->group->get('gidNumber')) : ?>
					<div class="floating-iframe-container">
						<iframe class="floating-iframe" src="<?php echo JRoute::_('index.php?option=com_groups&cn='.$this->group->get('gidNumber').'&controller=media&task=filebrowser&tmpl=component'); ?>"></iframe>
					</div>
				<?php else : ?>
					<p><em>Images &amp; files can be uploaded here once the group has been created.</em></p>
				<?php endif; ?>
			</div>
		</div>

		<p class="submit">
			<input class="btn btn-success" type="submit" value="<?php echo JText::_('COM_GROUPS_EDIT_SUBMIT_BTN_TEXT'); ?>" />
		</p>

		<input type="hidden" name="published" value="<?php echo $this->group->get('published'); ?>" />
		<input type="hidden" name="gidNumber" value="<?php echo $this->group->get('gidNumber'); ?>" />
		<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
		<input type="hidden" name="task" value="save" />
	</form>
</section><!-- / .section -->
