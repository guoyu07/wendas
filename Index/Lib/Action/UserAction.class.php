<?php
class UserAction extends BaseAction{

	protected function _initialize() {

		parent::_initialize();

		$this->check_login();

	}

}