<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 Purdue University. All rights reserved.
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
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2015 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// No direct access
defined('_HZEXEC_') or die();

$canDo = \Components\Blog\Admin\Helpers\Permissions::getActions('entry');

Toolbar::title(Lang::txt('COM_BLOG_TITLE'), 'blog.png');
if ($canDo->get('core.admin'))
{
	Toolbar::preferences($this->option, '550');
	Toolbar::spacer();
}
if ($canDo->get('core.edit.state'))
{
	Toolbar::publishList();
	Toolbar::unpublishList();
	Toolbar::spacer();
}
if ($canDo->get('core.delete'))
{
	Toolbar::deleteList('', 'delete');
}
if ($canDo->get('core.edit'))
{
	Toolbar::editList();
}
if ($canDo->get('core.create'))
{
	Toolbar::addNew();
}
Toolbar::spacer();
Toolbar::help('entries');

Html::behavior('tooltip');
?>
<script type="text/javascript">
function submitbutton(pressbutton)
{
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform(pressbutton);
		return;
	}
	// do field validation
	submitform(pressbutton);
}
</script>

<form action="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="col width-50 fltlft">
			<label for="filter_search"><?php echo Lang::txt('JSEARCH_FILTER'); ?>:</label>
			<input type="text" name="search" id="filter_search" value="<?php echo $this->escape($this->filters['search']); ?>" placeholder="<?php echo Lang::txt('COM_BLOG_FILTER_SEARCH_PLACEHOLDER'); ?>" />

			<?php if ($this->filters['scope'] == 'group') { ?>
				<label for="filter_scope_id"><?php echo Lang::txt('COM_BLOG_SCOPE_GROUP'); ?>:</label>
				<select name="scope_id" id="filter_scope_id">
					<?php
					$groups = \Hubzero\User\Group::find(array(
						'authorized' => 'admin',
						'fields'     => array('cn','description','published','gidNumber','type'),
						'type'       => array(1,3),
						'sortby'     => 'description'
					));

					$html  = '<option value="0"';
					if ($this->filters['scope_id'] == 0)
					{
						$html .= ' selected="selected"';
					}
					$html .= '>' . Lang::txt('JNONE') . '</option>' . "\n";
					if ($groups)
					{
						foreach ($groups as $group)
						{
							$html .= ' <option value="' . $group->gidNumber . '"';
							if ($this->filters['scope_id'] == $group->gidNumber)
							{
								$html .= ' selected="selected"';
							}
							$html .= '>' . $this->escape(stripslashes($group->description)) . '</option>' . "\n";
						}
					}
					echo $html;
					?>
				</select>
			<?php } ?>

			<input type="submit" value="<?php echo Lang::txt('COM_BLOG_GO'); ?>" />
			<button type="button" onclick="$('#filter_search').val('');$('#filter-state').val('');this.form.submit();"><?php echo Lang::txt('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="col width-50 fltrt">
			<label for="filter-state"><?php echo Lang::txt('COM_BLOG_FIELD_STATE'); ?>:</label>
			<select name="state" id="filter-state" onchange="this.form.submit();">
				<option value=""<?php if ($this->filters['state'] == '') { echo ' selected="selected"'; } ?>><?php echo Lang::txt('COM_BLOG_ALL_STATES'); ?></option>
				<option value="public"<?php if ($this->filters['state'] == 'public') { echo ' selected="selected"'; } ?>><?php echo Lang::txt('COM_BLOG_FIELD_STATE_PUBLIC'); ?></option>
				<option value="registered"<?php if ($this->filters['state'] == 'registered') { echo ' selected="selected"'; } ?>><?php echo Lang::txt('COM_BLOG_FIELD_STATE_REGISTERED'); ?></option>
				<option value="private"<?php if ($this->filters['state'] == 'private') { echo ' selected="selected"'; } ?>><?php echo Lang::txt('COM_BLOG_FIELD_STATE_PRIVATE'); ?></option>
				<option value="trashed"<?php if ($this->filters['state'] == 'trashed') { echo ' selected="selected"'; } ?>><?php echo Lang::txt('COM_BLOG_FIELD_STATE_TRASHED'); ?></option>
			</select>
		</div>
	</fieldset>
	<div class="clr"></div>

	<table class="adminlist">
		<thead>
			<tr>
				<th><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->rows);?>);" /></th>
				<th scope="col" class="priority-4"><?php echo $this->grid('sort', 'COM_BLOG_COL_ID', 'id', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<th scope="col"><?php echo $this->grid('sort', 'COM_BLOG_COL_TITLE', 'title', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<th scope="col" class="priority-4"><?php echo $this->grid('sort', 'COM_BLOG_COL_CREATOR', 'created_by', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<th scope="col" class="priority-1"><?php echo $this->grid('sort', 'COM_BLOG_COL_STATE', 'state', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<th scope="col" class="priority-5"><?php echo $this->grid('sort', 'COM_BLOG_COL_CREATED', 'created', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<th scope="col" class="priority-2" colspan="2"><?php echo $this->grid('sort', 'COM_BLOG_COL_COMMENTS', 'comments', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<?php if ($this->filters['scope'] == 'group') { ?>
					<th scope="col"><?php echo $this->grid('sort', 'COM_BLOG_COL_GROUP', 'scope_id', @$this->filters['sort_Dir'], @$this->filters['sort']); ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?php echo ($this->filters['scope'] == 'group') ? '9' : '8'; ?>"><?php
				// Initiate paging
				echo $this->pagination(
					$this->total,
					$this->filters['start'],
					$this->filters['limit']
				);
				?></td>
			</tr>
		</tfoot>
		<tbody>
<?php
$k = 0;
$i = 0;

$now    = Date::of('now');
$db     = App::get('db');

$nullDate = $db->getNullDate();

foreach ($this->rows as $row)
{
	$publish_up   = Date::of($row->get('publish_up'));
	$publish_down = Date::of($row->get('publish_down'));
	//$publish_up->setTimezone(Config::get('offset'));
	//$publish_down->setTimezone(Config::get('offset'));

	$alt  = Lang::txt('JUNPUBLISHED');
	$cls  = 'unpublish';
	$task = 'publish';

	if ($now->toUnix() <= $publish_up->toUnix() && $row->get('state') == 1)
	{
		$alt  = Lang::txt('JPUBLISHED');
		$cls  = 'publish';
		$task = 'unpublish';
	}
	else if (($now->toUnix() <= $publish_down->toUnix() || $row->get('publish_down') == $nullDate) && $row->get('state') == 1)
	{
		$alt  = Lang::txt('JPUBLISHED');
		$cls  = 'publish';
		$task = 'unpublish';
	}
	else if ($now->toUnix() > $publish_down->toUnix() && $row->get('state') == 1)
	{
		$alt  = Lang::txt('JLIB_HTML_PUBLISHED_EXPIRED_ITEM');
		$cls  = 'publish';
		$task = 'unpublish';
	}
	else if ($row->get('state') == 0)
	{
		$alt  = Lang::txt('JUNPUBLISHED');
		$task = 'publish';
		$cls  = 'unpublish';
	}
	else if ($row->get('state') == -1)
	{
		$alt  = Lang::txt('JTRASHED');
		$task = 'publish';
		$cls  = 'trash';
	}
	else if ($row->get('state') == 2)
	{
		$alt  = Lang::txt('COM_BLOG_FIELD_STATE_REGISTERED');
		$task = 'publish';
		$cls  = 'publish';
	}

	$times = '';
	if ($row->get('publish_up'))
	{
		if ($row->get('publish_up') == $nullDate)
		{
			$times .= Lang::txt('COM_BLOG_START') . ': ' . Lang::txt('COM_BLOG_ALWAYS');
		}
		else
		{
			$times .= Lang::txt('COM_BLOG_START') . ': ' . $publish_up->toSql();
		}
	}
	if ($row->get('publish_down'))
	{
		if ($row->get('publish_down') == $nullDate)
		{
			$times .= '<br />' . Lang::txt('COM_BLOG_FINISH') . ': ' . Lang::txt('COM_BLOG_NO_EXPIRY');
		}
		else
		{
			$times .= '<br />' . Lang::txt('COM_BLOG_FINISH') . ': ' . $publish_down->toSql();
		}
	}

	if ($row->get('allow_comments') == 0)
	{
		$calt = Lang::txt('JOFF');
		$cls2 = 'off';
		$state = 1;
	}
	else
	{
		$calt = Lang::txt('JON');
		$cls2 = 'on';
		$state = 0;
	}
?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<input type="checkbox" name="id[]" id="cb<?php echo $i;?>" value="<?php echo $row->get('id') ?>" onclick="isChecked(this.checked, this);" />
				</td>
				<td class="priority-4">
					<?php echo $row->get('id'); ?>
				</td>
				<td>
					<?php if ($canDo->get('core.edit')) { ?>
						<a href="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller . '&task=edit&id=' . $row->get('id')); ?>">
							<?php echo $this->escape(stripslashes($row->get('title'))); ?>
						</a>
					<?php } else { ?>
						<span>
							<?php echo $this->escape(stripslashes($row->get('title'))); ?>
						</span>
					<?php } ?>
				</td>
				<td class="priority-4">
					<?php echo $this->escape(stripslashes($row->get('name'))); ?>
				</td>
				<td class="priority-1">
					<span class="editlinktip hasTip" title="<?php echo Lang::txt('COM_BLOG_PUBLISH_INFO');?>::<?php echo $times; ?>">
						<?php if ($canDo->get('core.edit.state')) { ?>
							<a class="state <?php echo $cls; ?>" href="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller . '&task=' . $task . '&id=' . $row->get('id') . '&' . Session::getFormToken() . '=1'); ?>">
								<span><?php echo $alt; ?></span>
							</a>
						<?php } else { ?>
							<span class="state <?php echo $cls; ?>">
								<span><?php echo $alt; ?></span>
							</span>
						<?php } ?>
					</span>
				</td>
				<td class="priority-5">
					<time datetime="<?php echo $row->get('created'); ?>">
						<?php echo $row->published('date'); ?>
					</time>
				</td>
				<td class="priority-4">
					<a class="state <?php echo $cls2; ?>" href="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller . '&task=setcomments&state=' . $state . '&id=' . $row->get('id') . '&' . Session::getFormToken() . '=1'); ?>">
						<span><?php echo $calt; ?></span>
					</a>
				</td>
				<td class="priority-2">
					<?php if ($canDo->get('core.edit')) { ?>
						<a class="comment" href="<?php echo Route::url('index.php?option=' . $this->option . '&controller=comments&entry_id=' . $row->get('id')); ?>">
							<?php echo Lang::txt('COM_BLOG_COMMENTS', $row->get('comments')); ?>
						</a>
					<?php } else { ?>
						<span class="comment">
							<?php echo Lang::txt('COM_BLOG_COMMENTS', $row->get('comments')); ?>
						</span>
					<?php } ?>
				</td>
				<?php if ($this->filters['scope'] == 'group') { ?>
					<td>
						<span>
							<?php echo $this->escape($row->get('scope_id')); ?>
						</span>
					</td>
				<?php } ?>
			</tr>
<?php
	$i++;
	$k = 1 - $k;
}
?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option ?>" />
	<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="scope" value="<?php echo $this->filters['scope']; ?>" />
	<input type="hidden" name="filter_order" value="<?php echo $this->filters['sort']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filters['sort_Dir']; ?>" />

	<?php echo Html::input('token'); ?>
</form>
