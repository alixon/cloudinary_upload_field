<?php

	if( !defined('__IN_SYMPHONY__') ) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');



	class extension_cloudinary_upload_field extends Extension
	{

		/*------------------------------------------------------------------------------------------------*/
		/*  Installation  */
		/*------------------------------------------------------------------------------------------------*/

		public function install(){
			return Symphony::Database()->query(
				"CREATE TABLE `tbl_fields_cloudinary_upload` (
				 `id` int(11) unsigned NOT NULL auto_increment,
				 `field_id` int(11) unsigned NOT NULL,
				 `validator` varchar(50),
				 `unique`  varchar(50),
				  PRIMARY KEY (`id`),
				  KEY `field_id` (`field_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
			);
		}


		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/system/preferences/',
					'delegate' => 'CustomActions',
					'callback' => 'savePreferences'
				),
				array(
					'page' => '/system/preferences/',
					'delegate' => 'AddCustomPreferenceFieldsets',
					'callback' => 'appendPreferences'
				),
			);
		}

		public function appendPreferences($context){
			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'settings');
			$group->appendChild(new XMLElement('legend', __('Cloudinary Credentials')));

			$div = new XMLElement('div', NULL, array('class' => 'two columns'));

			$label = Widget::Label(__('API Key ID'));
            $label->setAttribute('class', 'column');
			$label->appendChild(Widget::Input('settings[cloudinary_upload_field][api-key]', General::Sanitize($this->getCloudinaryApiKey())));
			$div->appendChild($label);

			$label = Widget::Label('Secret Access Key');
            $label->setAttribute('class', 'column');
			$label->appendChild(Widget::Input('settings[cloudinary_upload_field][api-secret]', General::Sanitize($this->getCloudinarySecretKey()), 'password'));
			$div->appendChild($label);

			$group->appendChild($div);
			$group->appendChild(new XMLElement('p', 'Get a API Key and Secret Key from the <a href="http://cloudinary.com">Cloudinary Services site</a>.', array('class' => 'help')));
			$context['wrapper']->appendChild($group);
		}

		public function uninstall(){
			Symphony::Database()->query("DROP TABLE `tbl_fields_cloudinary_upload`");
			Symphony::Configuration()->remove('cloudinary_upload_field');

		 	return Symphony::Configuration()->write();
		}

		public function getCloudinaryApiKey() {
			return Symphony::Configuration()->get('api-key', 'cloudinary_upload_field');
		}

		public function getCloudinarySecretKey() {
			return Symphony::Configuration()->get('secret-key', 'cloudinary_upload_field');
		}

	}
