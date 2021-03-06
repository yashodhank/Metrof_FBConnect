<?php

class Metrof_FBConnect_Model_Customer
extends Mage_Customer_Model_Customer {

	function _construct()
	{
		$this->_setResourceModel('fbconnect/customer', 'customer/customer_collection');
	}


	/**
	 * Validate customer attribute values
	 *
	 * SKIP EMAIL VALIDATION
	 * SKIP PASSWORD
	 *
	 * @return bool
	 */
	public function validate()
	{
		//force email confirmation on new customers
		$isNewCustomer = !$this->getId();

		$errors = array();

		$fbUser = FALSE;
		if (Mage::helper('fbconnect')->userIsFb($this)) {
			$fbUser = TRUE;
		}
		if ($fbUser && $isNewCustomer) {
			$this->forceFbCustomerConfirmed();
		}
		if (!Zend_Validate::is( trim($this->getFirstname()) , 'NotEmpty')) {
			$errors[] = Mage::helper('customer')->__('First name can\'t be empty');
		}

		if (!Zend_Validate::is( trim($this->getLastname()) , 'NotEmpty')) {
			$errors[] = Mage::helper('customer')->__('Last name can\'t be empty');
		}

		if (!$fbUser) {
			if (!Zend_Validate::is($this->getEmail(), 'EmailAddress')) {
				$errors[] = Mage::helper('customer')->__('Invalid email address "%s"', $this->getEmail());
			}
			$password = $this->getPassword();
			if (!$this->getId() && !Zend_Validate::is($password , 'NotEmpty')) {
				$errors[] = Mage::helper('customer')->__('Password can\'t be empty');
			}
			if ($password && !Zend_Validate::is($password, 'StringLength', array(6))) {
				$errors[] = Mage::helper('customer')->__('Password minimal length must be more %s', 6);
			}
			$confirmation = $this->getConfirmation();
			if ($password != $confirmation) {
				$errors[] = Mage::helper('customer')->__('Please make sure your passwords match.');
			}
		}

		if (('req' === Mage::helper('customer/address')->getConfig('dob_show'))
			&& '' == trim($this->getDob())) {
				$errors[] = Mage::helper('customer')->__('Date of Birth is required.');
			}
		if (('req' === Mage::helper('customer/address')->getConfig('taxvat_show'))
			&& '' == trim($this->getTaxvat())) {
				$errors[] = Mage::helper('customer')->__('TAX/VAT number is required.');
			}

		if (empty($errors)) {
			return true;
		}
		return $errors;
	}


	/**
	 * FBCustomers cannot confirm an email
	 */
	public function forceFbCustomerConfirmed() {
		try {
			// force new customer active
			$this->setForceConfirmed(true);
		} catch (Exception $e){
		}
	}
}
