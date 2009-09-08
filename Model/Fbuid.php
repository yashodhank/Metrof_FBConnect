<?php

class Metrof_FBConnect_Model_Fbuid extends Mage_Core_Model_Abstract {

    public function __construct()
    {
        $this->_init('fbconnect/fbuid');
    }

	public function loadByUserId($uid) {
        $this->_getResource()->load($this, $uid, 'user_id');
        $this->_afterLoad();
        $this->setOrigData();
        return $this;
	}
}
