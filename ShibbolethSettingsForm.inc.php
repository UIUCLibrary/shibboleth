<?php

/**
 * @file plugins/generic/shibboleth/ShibbolethSettingsForm.inc.php
 *
 * Copyright (c) 2014-2019 Simon Fraser University
 * Copyright (c) 2003-2019 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ShibbolethSettingsForm
 * @ingroup plugins_generic_shibboleth
 *
 * @brief Form for managers to modify Shibboleth
 * authentication plugin settings
 */

import('lib.pkp.classes.form.Form');

class ShibbolethSettingsForm extends Form {

	/** @var int */
	var $_contextId;

	/** @var object */
	var $_plugin;

	/**
	 * Constructor
	 * @param $plugin ShibbolethAuthPlugin
	 * @param $contextId int
	 */
	function __construct($plugin, $contextId) {
		$this->_contextId = $contextId;
		$this->_plugin = $plugin;

		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

		foreach ($this->_plugin->settingsRequired as $setting) {
			$this->addCheck(
				new FormValidator(
					$this,
					$setting,
					'required',
					'plugins.generic.shibboleth.manager.settings.' . $setting . 'Required'
				)
			);
		}
		$this->addCheck(new FormValidatorPost($this));
		$this->addCheck(new FormValidatorCSRF($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$this->_data = array(
			'shibbolethWayfUrl' => $this->_plugin->getSetting($this->_contextId, 'shibbolethWayfUrl'),
			'shibbolethHeaderUin' => $this->_plugin->getSetting($this->_contextId, 'shibbolethHeaderUin'),
			'shibbolethHeaderFirstName' => $this->_plugin->getSetting($this->_contextId, 'shibbolethHeaderFirstName'),
			'shibbolethHeaderLastName' => $this->_plugin->getSetting($this->_contextId, 'shibbolethHeaderLastName'),
			'shibbolethHeaderInitials' => $this->_plugin->getSetting($this->_contextId, 'shibbolethHeaderInitials'),
			'shibbolethHeaderEmail' => $this->_plugin->getSetting($this->_contextId, 'shibbolethHeaderEmail'),
			'shibbolethHeaderPhone' => $this->_plugin->getSetting($this->_contextId, 'shibbolethHeaderPhone'),
			'shibbolethHeaderMailing' => $this->_plugin->getSetting($this->_contextId, 'shibbolethHeaderMailing'),
			'shibbolethAdminUins' => $this->_plugin->getSetting($this->_contextId, 'shibbolethAdminUins'),
			'shibbolethOptional' => $this->_plugin->getSetting($this->_contextId, 'shibbolethOptional'),
			'shibbolethOptionalTitle' => $this->_plugin->getSetting($this->_contextId, 'shibbolethOptionalTitle'),
			'shibbolethOptionalLoginDescription' => $this->_plugin->getSetting($this->_contextId, 'shibbolethOptionalLoginDescription'),
			'shibbolethOptionalRegistrationDescription' => $this->_plugin->getSetting($this->_contextId, 'shibbolethOptionalRegistrationDescription'),
			'shibbolethOptionalButtonLabel' => $this->_plugin->getSetting($this->_contextId, 'shibbolethOptionalButtonLabel'),
		);
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('shibbolethWayfUrl'));
		$this->readUserVars(array('shibbolethHeaderUin'));
		$this->readUserVars(array('shibbolethHeaderFirstName'));
		$this->readUserVars(array('shibbolethHeaderLastName'));
		$this->readUserVars(array('shibbolethHeaderInitials'));
		$this->readUserVars(array('shibbolethHeaderEmail'));
		$this->readUserVars(array('shibbolethHeaderPhone'));
		$this->readUserVars(array('shibbolethHeaderMailing'));
		$this->readUserVars(array('shibbolethAdminUins'));
		$this->readUserVars(array('shibbolethOptional'));
		$this->readUserVars(array('shibbolethOptionalTitle'));
		$this->readUserVars(array('shibbolethOptionalLoginDescription'));
		$this->readUserVars(array('shibbolethOptionalRegistrationDescription'));
		$this->readUserVars(array('shibbolethOptionalButtonLabel'));
	}

	/**
	 * Fetch the form.
	 * @copydoc Form::fetch()
	 */
	function fetch($request) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		return parent::fetch($request);
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethWayfUrl',
			trim($this->getData('shibbolethWayfUrl'), "\"\';"),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethHeaderUin',
			trim($this->getData('shibbolethHeaderUin'), "\"\';"),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethHeaderFirstName',
			trim($this->getData('shibbolethHeaderFirstName'), "\"\';"),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethHeaderLastName',
			trim($this->getData('shibbolethHeaderLastName'), "\"\';"),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethHeaderInitials',
			trim($this->getData('shibbolethHeaderInitials'), "\"\';"),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethHeaderEmail',
			trim($this->getData('shibbolethHeaderEmail'), "\"\';"),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethHeaderPhone',
			trim($this->getData('shibbolethHeaderPhone'), "\"\';"),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethHeaderMailing',
			trim($this->getData('shibbolethHeaderMailing'), "\"\';"),
			'string'
		);

		$oldAdminStr = $this->_plugin->getSetting($this->_contextId, 'shibbolethAdminUins');
		$newAdminStr = trim($this->getData('shibbolethAdminUins'), "\"\';");
		$this->updateAdmin($oldAdminStr,$newAdminStr);

		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethAdminUins',
			trim($this->getData('shibbolethAdminUins'), "\"\';"),
			'string'
								);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethOptional',
			$this->getData('shibbolethOptional'),
			'bool'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethOptionalTitle',
			$this->getData('shibbolethOptionalTitle'),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethOptionalButtonLabel',
			$this->getData('shibbolethOptionalButtonLabel'),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethOptionalLoginDescription',
			$this->getData('shibbolethOptionalLoginDescription'),
			'string'
		);
		$this->_plugin->updateSetting(
			$this->_contextId,
			'shibbolethOptionalRegistrationDescription',
			$this->getData('shibbolethOptionalRegistrationDescription'),
			'string'
		);
		$userDao = DAORegistry::getDAO('UserDAO');
		$adminData = trim($this->getData('shibbolethAdminUins'), "\"\';");
		$admins = explode(" ", $adminData);
		foreach ($admins as $admin){

		}
	}

	function updateAdmin($oldAdminStr, $newAdminStr) {

		$oldAdmins = explode(' ', $oldAdminStr);
		$newAdmins = explode(' ', $newAdminStr);

		$addAdmins = array_diff($newAdmins, $oldAdmins);
		$removeAdmins = array_diff_assoc($oldAdmins, $newAdmins);

		$userGroupDao = DAORegistry::getDAO('UserGroupDAO');

		// should be unique
		$adminGroup = $userGroupDao->getByRoleId(0, ROLE_ID_SITE_ADMIN)->next();
		$adminId = $adminGroup->getId();

		//add users who were not already admins
		foreach($addAdmins as $uin){

			$userId = $this->getUserId($uin);

			// if the uin entered in the form is associated with a user
			if($userId){
				// and if they are not already an admin
				if(!$userGroupDao->userInGroup($userId, $adminId)) {
					error_log( "Shibboleth assigning admin to user identified by $userId");
					$userGroupDao->assignUserToGroup($userId, $adminId);
				}
			}


		}

		//remove users who are no longer in the form data
		foreach($removeAdmins as $uin){
			$userId = $this->getUserId($uin);

			if($userId) {
				error_log( "Shibboleth removing admin user identified by $userId");
				$userGroupDao->removeUserFromGroup($userId, $adminId, 0);
			}
		}
	}

	private function getUserId($uin){
		$userDao = DAORegistry::getDAO('UserDAO');
		$user = false;
		if(str_contains($uin, '@') ){
			$user = $userDao->getUserByEmail($uin);
			error_log('user was fetched using email');
			$user = $user->getId();
		} else {
			$user = $userDao->getUserByAuthStr($uin, true);
			error_log('user was fetched auth string');
			$user = $user->getId();

		}
		return $user;
	}
}
