<?php
/*------------------------------------------------------------------------
# Cobalt
# ------------------------------------------------------------------------
# @author Cobalt
# @copyright Copyright (C) 2012 cobaltcrm.org All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.cobaltcrm.org
-------------------------------------------------------------------------*/
// no direct access
defined( '_CEXEC' ) or die( 'Restricted access' ); ?>

<div class="container-fluid">
    <?php echo $this->menu['quick_menu']->render(); ?>
    <div class="row-fluid">
        <div class="span12" id="content">
            <div id="system-message-container"></div>
            <div class="row-fluid">
                <?php echo $this->menu['menu']->render(); ?>
                <div class="span9">
                    <form action="index.php?view=templates" method="post" name="adminForm" id="adminForm" class="form-validate"  >
                        <div class="row-fluid">
                            <fieldset class="adminform">
                                <legend><h3><?php echo TextHelper::_("COBALT_EDITING_WORKFLOW"); ?></h3></legend>
                                <ul class="unstyled adminformlist cobaltadminlist">
                                    <li>
                                        <label><b><?php echo JText::_('COBALT_NAME'); ?></b></label>
                                        <input type="text" class="inputbox" name="name" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_NAME_YOUR_WORKFLOW'); ?>" value="<?php echo $this->template['name']; ?>" />
                                    </li>
                                    <li>
                                        <span class="cobaltfaux-label"><b><?php echo JText::_('COBALT_HEADER_SOURCE_TYPE'); ?></b></span>
                                        <br />

                                        <label class="radio">
                                          <input type="radio" name="type" value="deal" <?php if($this->template['type']=='deal') echo 'checked'; ?> />
                                          <?php echo JText::_('COBALT_DEAL'); ?>
                                        </label>

                                        <label class="radio">
                                          <input type="radio" name="type" value="person" <?php if($this->template['type']=='person') echo 'checked'; ?>/>
                                          <?php echo JText::_('COBALT_PERSON'); ?>
                                        </label>

                                        <label class="radio">
                                          <input type="radio" name="type" value="company" <?php if($this->template['type']=='company') echo 'checked'; ?> />
                                          <?php echo JText::_('COBALT_COMPANY'); ?>
                                        </label>

                                    </li>
                                    <li>
                                        <span class="cobaltfaux-label"></span>
                                        <span class="cobaltfield"></span>
                                    </li>
                                    <li>
                                        <label class="checkbox">
                                            <input type="checkbox" name="default" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_NAME_YOUR_WORKFLOW'); ?>"  <?php if($this->template['default']) echo 'checked'; ?> />
                                            <b><?php echo JText::_("COBALT_MAKE_DEFAULT_TEMPLATE"); ?></b>
                                        </label>
                                    </li>
                                    <li>
                                </ul>
                            </fieldset>
                        </div>
                        <div class="row-fluid">
                            <fieldset class="adminform">
                                <legend><h3><?php echo JText::_("COBALT_ENTER_ITEMS"); ?></h3></legend>
                                <ul class="unstyled adminformlist cobaltadminlist">
                                    <li>
                                        <div id="items">
                                            <?php if ( array_key_exists("data",$this->template) && count($this->template['data']) ){ foreach ($this->template['data'] as $data) { ?>
                                                <span class="cobaltfield clrboth">
                                                <div class="item">
                                                    <table>
                                                        <tr>
                                                            <input type="hidden" name="items[]" value ="<?php echo $data['id']; ?>]" />
                                                            <td><b><?php echo JText::_('COBALT_NAME'); ?></b></td>
                                                            <td><input class="inputbox" type="text" name="names[]" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_EVENT_NAME_TOOLTIP'); ?>" value="<?php echo $data['name']; ?>" /></td>
                                                            <td><b><?php echo JText::_("COBALT_DAY"); ?></b></td>
                                                            <td><input class="inputbox" type="text" name="days[]" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_EVENT_DAY_TOOLTIP'); ?>" value="<?php echo $data['day']; ?>" /></td>
                                                            <td><b><?php echo JText::_("COBALT_HEADER_SOURCE_TYPE"); ?></b></td>
                                                            <td>
                                                                <select class="inputbox" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_EVENT_TYPE_TOOLTIP'); ?>" name="types[]">
                                                                    <option value=""><?php echo JText::_('COBALT_SELECT_EVENT_TYPE'); ?></option>
                                                                      <?php echo JHtml::_('select.options', $this->template_types, 'value', 'text', $data['type'], true);?>
                                                                </select>
                                                            </td>
                                                            <td><a href="javascript:void(0);" class="btn btn-danger remove_item"><?php echo JText::_("COBALT_REMOVE"); ?></a></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                </span>
                                            <?php } } else { ?>
                                                <span class="cobaltfield clrboth">
                                                    <div class="item">
                                                        <table>
                                                            <tr>
                                                                <input type="hidden" name="items[]" value ="" />
                                                                <td><b><?php echo JText::_('COBALT_NAME'); ?></b></td>
                                                                <td><input class="inputbox" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_EVENT_NAME'); ?>" type="text" name="names[]" value="" /></td>
                                                                <td><b><?php echo JText::_('COBALT_DAY'); ?></b></td>
                                                                <td><input class="inputbox" type="text" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_EVENT_DAY'); ?>" name="days[]" value="" /></td>
                                                                <td><b><?php echo JText::_('COBALT_HEADER_SOURCE_TYPE'); ?></b></td>
                                                                <td>
                                                                    <select class="inputbox" name="types[]" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_EVENT_TYPE'); ?>" >
                                                                        <option value=""><?php echo JText::_('COBALT_SELECT_EVENT_TYPE'); ?></option>
                                                                          <?php echo JHtml::_('select.options', $this->template_types, 'value', 'text', '', true);?>
                                                                    </select>
                                                                </td>
                                                                <td><a href="javascript:void(0);" class="btn btn-danger remove_item"><?php echo JText::_("COBALT_REMOVE"); ?></a></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </span>
                                            <?php } ?>
                                        </div>
                                    </li>
                                    <li>
                                        <label><a href="javascript:void(0);" class="btn btn-success" id="add_item"><i class="icon-white icon-plus-sign"></i> <b>Add More Items</a></b></label>
                                    </li>
                                </ul>
                            </fieldset>
                        </div>
                        <div>
                            <?php if ($this->template['id']) { ?>
                                <input type="hidden" name="id" value="<?php echo $this->template['id']; ?>" />
                            <?php } ?>
                            <input type="hidden" name="controller" value="" />
                            <input type="hidden" name="model" value="templates" />
                            <?php echo JHtml::_('form.token'); ?>
                        </div>
                    </form>
                    <div style="display:none;" id="item_template">
                        <span class="cobaltfield clrboth">
                            <div class="item">
                                <table>
                                    <tr>
                                        <input type="hidden" name="items[]" value ="" />
                                        <td><b><?php echo JText::_('COBALT_NAME'); ?></b></td>
                                        <td><input class="inputbox" type="text" name="names[]" value="" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_EVENT_NAME'); ?>"  /></td>
                                        <td><b><?php echo JText::_('COBALT_DAY'); ?></b></td>
                                        <td><input class="inputbox" type="text" name="days[]" value="" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_EVENT_DAY'); ?>"  /></td>
                                        <td><b><?php echo JText::_('COBALT_HEADER_SOURCE_TYPE'); ?></b></td>
                                        <td>
                                            <select class="inputbox" name="types[]" rel="tooltip" data-original-title="<?php echo JText::_('COBALT_EVENT_TYPE'); ?>" >
                                                <option value=""><?php echo JText::_('COBALT_SELECT_EVENT_TYPE'); ?></option>
                                                  <?php echo JHtml::_('select.options', $this->template_types, 'value', 'text', '', true);?>
                                            </select>
                                        </td>
                                        <td><a href="javascript:void(0);" class="btn btn-danger remove_item"><?php echo JText::_("COBALT_REMOVE"); ?></a></td>
                                    </tr>
                                </table>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $this->menu['quick_menu']->render(); ?>
    </div>
</div>
