
-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_persons', '_bx_persons', 'bx_persons@modules/boonex/persons/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_persons', '_bx_persons', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_persons_autoapproval', 'on', @iCategId, 'Activate all persons after creation automatically', 'checkbox', '', '', '', 1),
('bx_persons_default_acl_level', '2', @iCategId, 'Default member\'s level to assign after person\'s profile creation', 'select', 'PHP:bx_import(''BxDolAcl''); return BxDolAcl::getInstance()->getMemberships(false, true);', '', '', 2);

-- STORAGES & TRANSCODERS

SET @iTotalFilesSize = (SELECT SUM(`size`) FROM `bx_persons_pictures`);
SET @iTotalFilesNum = (SELECT COUNT(*) FROM `bx_persons_pictures`);

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_persons_pictures', 'Local', '', 360, 2592000, 3, 'bx_persons_pictures', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, @iTotalFilesSize, 0, @iTotalFilesNum, 0, 0),
('bx_persons_pictures_resized', 'Local', '', 360, 2592000, 3, 'bx_persons_pictures_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, @iTotalFilesSize, 0, @iTotalFilesNum, 0, 0);

INSERT INTO `sys_objects_transcoder_images` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_persons_thumb', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0'),
('bx_persons_preview', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_images_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_persons_thumb', 'Resize', 'a:4:{s:1:"w";s:2:"64";s:1:"h";s:2:"64";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0'),
('bx_persons_preview', 'Resize', 'a:4:{s:1:"w";s:3:"278";s:1:"h";s:3:"278";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');


-- PAGES

--
-- Dumping data for 'bx_persons_create_profile' page
--
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`, `layout_name`, `layout_icon`, `layout_title`, `layout_template`, `layout_cells_number`) VALUES 
('bx_persons_create_profile', '_bx_persons_page_title_sys_create_profile', '_bx_persons_page_title_create_profile', 'bx_persons', 5, 2147483647, 1, 'page.php?i=create-persons-profile', '', '', '', 0, 1, 0, '', '', '1_column', 'layout_1_column.png', '_sys_layout_1_column', 'layout_1_column.html', 1);

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_persons_create_profile', 1, 'bx_persons', '_bx_persons_page_block_title_create_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:14:\"create_profile\";}', 0, 1, 1);

--
-- Dumping data for 'bx_persons_delete_profile' page
--
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`, `layout_name`, `layout_icon`, `layout_title`, `layout_template`, `layout_cells_number`) VALUES 
('bx_persons_delete_profile', '_bx_persons_page_title_sys_delete_profile', '_bx_persons_page_title_delete_profile', 'bx_persons', 1, 2147483647, 1, 'page.php?i=delete-persons-profile', '', '', '', 0, 1, 0, 'BxPersonsPageProfile', 'modules/boonex/persons/classes/BxPersonsPageProfile.php', 'bar_left', 'layout_bar_left.png', '_sys_layout_bar_left', 'layout_bar_left.html', 2);

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_persons_delete_profile', 2, 'bx_persons', '_bx_persons_page_block_title_delete_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:14:\"delete_profile\";}', 0, 1, 0),
('bx_persons_delete_profile', 1, 'bx_persons', '_bx_persons_page_block_title_profile_picture', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:15:\"profile_picture\";}', 0, 0, 1),
('bx_persons_delete_profile', 1, 'bx_persons', '_bx_persons_page_block_title_profile_menu', 13, 2147483647, 'menu', 'bx_persons_view', 0, 0, 2);

--
-- Dumping data for 'bx_persons_edit_profile' page
--
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`, `layout_name`, `layout_icon`, `layout_title`, `layout_template`, `layout_cells_number`) VALUES 
('bx_persons_edit_profile', '_bx_persons_page_title_sys_edit_profile', '_bx_persons_page_title_edit_profile', 'bx_persons', 1, 2147483647, 1, 'page.php?i=edit-persons-profile', '', '', '', 0, 1, 0, 'BxPersonsPageProfile', 'modules/boonex/persons/classes/BxPersonsPageProfile.php', 'bar_left', 'layout_bar_left.png', '_sys_layout_bar_left', 'layout_bar_left.html', 2);

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_persons_edit_profile', 2, 'bx_persons', '_bx_persons_page_block_title_edit_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:12:\"edit_profile\";}', 0, 1, 0),
('bx_persons_edit_profile', 1, 'bx_persons', '_bx_persons_page_block_title_profile_picture', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:15:\"profile_picture\";}', 0, 1, 1),
('bx_persons_edit_profile', 1, 'bx_persons', '_bx_persons_page_block_title_profile_menu', 13, 2147483647, 'menu', 'bx_persons_view', 0, 1, 2);

--
-- Dumping data for 'bx_persons_view_profile' page
--
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`, `layout_name`, `layout_icon`, `layout_title`, `layout_template`, `layout_cells_number`) VALUES 
('bx_persons_view_profile', '_bx_persons_page_title_sys_view_profile', '_bx_persons_page_title_view_profile', 'bx_persons', 1, 2147483647, 1, 'page.php?i=view-persons-profile', '', '', '', 0, 1, 0, 'BxPersonsPageProfile', 'modules/boonex/persons/classes/BxPersonsPageProfile.php', 'bar_left', 'layout_bar_left.png', '_sys_layout_bar_left', 'layout_bar_left.html', 2);

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_persons_view_profile', 2, 'bx_persons', '_bx_persons_page_block_title_profile_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:12:\"profile_info\";}', 0, 0, 0),
('bx_persons_view_profile', 1, 'bx_persons', '_bx_persons_page_block_title_profile_picture', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:15:\"profile_picture\";}', 0, 0, 1),
('bx_persons_view_profile', 1, 'bx_persons', '_bx_persons_page_block_title_profile_menu', 13, 2147483647, 'menu', 'bx_persons_view', 0, 0, 2);


-- MENU

SET @iCreateProfileMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_profiles_create' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `order`) VALUES
('sys_profiles_create', 'bx_persons', 'create-person-profile', '_bx_persons_menu_item_title_system_persons_profile', '_bx_persons_menu_item_title_persons_profile', 'page.php?i=create-persons-profile', '', '', 'user', '', 2147483647, 1, IFNULL(@iCreateProfileMenuOrder, 0) + 1);

--
-- Dumping data for 'bx_persons_view' menu
--
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_view', '_bx_persons_menu_title_view_person', 'bx_persons_view', 'bx_persons', 6, 0, 1, 'BxPersonsMenuViewPerson', 'modules/boonex/persons/classes/BxPersonsMenuViewPerson.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_persons_view', 'bx_persons', '_bx_persons_menu_set_title_view_person', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_persons_view', 'bx_persons', 'view-persons-profile', '_bx_persons_menu_item_title_system_view_person', '_bx_persons_menu_item_title_view_person', 'page.php?i=view-persons-profile&id={content_id}', '', '', 'user', '', 2147483647, 1, 0),
('bx_persons_view', 'bx_persons', 'edit-persons-profile', '_bx_persons_menu_item_title_system_edit_person', '_bx_persons_menu_item_title_edit_person', 'page.php?i=edit-persons-profile&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 1),
('bx_persons_view', 'bx_persons', 'delete-persons-profile', '_bx_persons_menu_item_title_system_delete_person', '_bx_persons_menu_item_title_delete_person', 'page.php?i=delete-persons-profile&id={content_id}', '', '', 'remove', '', 2147483647, 1, 2);


-- ACL

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_persons', 'create person profile', NULL, '_bx_persons_acl_action_create_profile', '', 1, 1);
SET @iIdActionProfileCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_persons', 'delete person profile', NULL, '_bx_persons_acl_action_delete_profile', '', 1, 1);
SET @iIdActionProfileDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_persons', 'view person profile', NULL, '_bx_persons_acl_action_view_profile', '', 1, 1);
SET @iIdActionProfileView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_persons', 'edit any person profile', NULL, '_bx_persons_acl_action_edit_any_profile', '', 1, 1);
SET @iIdActionProfileEditAny = LAST_INSERT_ID();


SET @iUnauthenticated = 1;
SET @iStandard = 2;
SET @iUnconfirmed = 3;
SET @iPending = 4;
SET @iSuspended = 5;
SET @iModerator = 6;
SET @iAdministrator = 7;
SET @iPremium = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- profile create
(@iStandard, @iIdActionProfileCreate),
(@iUnconfirmed, @iIdActionProfileCreate),
(@iPending, @iIdActionProfileCreate),
(@iModerator, @iIdActionProfileCreate),
(@iAdministrator, @iIdActionProfileCreate),
(@iPremium, @iIdActionProfileCreate),

-- profile delete
(@iStandard, @iIdActionProfileDelete),
(@iUnconfirmed, @iIdActionProfileDelete),
(@iPending, @iIdActionProfileDelete),
(@iModerator, @iIdActionProfileDelete),
(@iAdministrator, @iIdActionProfileDelete),
(@iPremium, @iIdActionProfileDelete),

-- profile view
(@iUnauthenticated, @iIdActionProfileView),
(@iStandard, @iIdActionProfileView),
(@iUnconfirmed, @iIdActionProfileView),
(@iPending, @iIdActionProfileView),
(@iModerator, @iIdActionProfileView),
(@iAdministrator, @iIdActionProfileView),
(@iPremium, @iIdActionProfileView),

-- any profile edit
(@iModerator, @iIdActionProfileEditAny),
(@iAdministrator, @iIdActionProfileEditAny);

