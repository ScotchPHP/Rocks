<?
namespace Rocks\Account\Repositories;

use	Scotch\Data\SqlParameterDirections as SqlParameterDirections;
use Rocks\Repositories\RocksSqlRepository as RocksSqlRepository;

class AccountRepository extends RocksSqlRepository
{
	const SQL_SESSION_NAME = "account";
	
	function __construct($session)
	{
		parent::__construct($session);
	}
	
	protected function getSqlSessionName()
	{
		return self::SQL_SESSION_NAME;
	}

/* ACCOUNTS */
	public function getAccounts($parameters = array())
	{
		return  $this->callPagedStoredProcedure("acct.getAccounts", $parameters, 
			array(
				$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
				$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters,"accountID")),
				$this->createBitSqlParameter("@isActive", $this->getParameterValue($parameters,"isActive")),
			)
		);
	}
	
	public function getAccounts_Url($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.getAccounts_Url", array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createStringSqlParameter("@accountUrl", 75, $this->getParameterValue($parameters, "accountUrl")),
		));
	}
	
	public function updateAccount($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateAccount", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID"), SqlParameterDirections::InOut),
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createStringSqlParameter("@column", 50, $this->getParameterValue($parameters, "column")),
			$this->createStringSqlParameter("@accountName", 100, $this->getParameterValue($parameters, "accountName")),
			$this->createStringSqlParameter("@email", 75, $this->getParameterValue($parameters, "email")),
			$this->createStringSqlParameter("@phone", 20, $this->getParameterValue($parameters, "phone")),
			$this->createStringSqlParameter("@phoneExt", 8, $this->getParameterValue($parameters, "phoneExt")),
			$this->createStringSqlParameter("@countryID", 2, $this->getParameterValue($parameters, "countryID")),
			$this->createStringSqlParameter("@address", 100, $this->getParameterValue($parameters, "address")),
			$this->createStringSqlParameter("@address2", 100, $this->getParameterValue($parameters, "address2")),
			$this->createStringSqlParameter("@city", 50, $this->getParameterValue($parameters, "city")),
			$this->createStringSqlParameter("@stateID", 3, $this->getParameterValue($parameters, "stateID")),
			$this->createStringSqlParameter("@postalCode", 30, $this->getParameterValue($parameters, "postalCode")),
			$this->createErrorStringSqlParameter("@userID_Error"),
			$this->createErrorStringSqlParameter("@accountName_Error"),
			$this->createErrorStringSqlParameter("@email_Error"),
			$this->createErrorStringSqlParameter("@countryID_Error"),
			$this->createErrorStringSqlParameter("@stateID_Error"),
		));
	}
	
	public function authenticateAccount($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.authenticateAccount", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createIntegerSqlParameter("@permissions", $this->getParameterValue($parameters, "permissions"), SqlParameterDirections::InOut),
			$this->createErrorStringSqlParameter("@auth_Error"), 
		));
	}
	
	public function updateAccount_Active($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateAccount_Active", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createBitSqlParameter("@isActive",  $this->getParameterValue($parameters, "isActive")),
		));
	}
	
	public function updateAccount_Logo($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateAccount_Logo", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createStringSqlParameter("@logoUrl", 100, $this->getParameterValue($parameters, "logoUrl")),
			$this->createStringSqlParameter("@logoAttributes", 100, $this->getParameterValue($parameters, "logoAttributes")),
		));
	}
	
/* BILLING */
	public function getBilling($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.getBilling", array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
		));
	}
	
	public function updateBilling($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateBilling", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createStringSqlParameter("@billToEmail", 75, $this->getParameterValue($parameters, "billToEmail")),
			$this->createStringSqlParameter("@billToCountryID", 2, $this->getParameterValue($parameters, "billToCountryID")),
			$this->createStringSqlParameter("@billToAddress", 100, $this->getParameterValue($parameters, "billToAddress")),
			$this->createStringSqlParameter("@billToAddress2", 100, $this->getParameterValue($parameters, "billToAddress2")),
			$this->createStringSqlParameter("@billToCity", 50, $this->getParameterValue($parameters, "billToCity")),
			$this->createStringSqlParameter("@billToStateID", 3, $this->getParameterValue($parameters, "billToStateID")),
			$this->createStringSqlParameter("@billToPostalCode", 30, $this->getParameterValue($parameters, "billToPostalCode")),
			$this->createErrorStringSqlParameter("@billToEmail_Error"),
			$this->createErrorStringSqlParameter("@billToCountryID_Error"),
			$this->createErrorStringSqlParameter("@billToAddress_Error"),
			$this->createErrorStringSqlParameter("@billToCity_Error"),
			$this->createErrorStringSqlParameter("@billToStateID_Error"),
			$this->createErrorStringSqlParameter("@billToPostalCode_Error"),
		));
	}

/* USERS */
	public function getUsers($parameters = array())
	{
		return $this->callPagedStoredProcedure("acct.getUsers", $parameters, array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createStringSqlParameter("@email", 75, $this->getParameterValue($parameters, "email")),
			$this->createBitSqlParameter("@isActive", $this->getParameterValue($parameters, "isActive")),
		), array(
			$this->createErrorStringSqlParameter("@email_Error"),
		));
	}
	
	public function getUserAuthenticationInfo($parameters = array())
	{	
		return $this->callStandardStoredProcedure("acct.getUser_AuthenticationInfo", array(
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createStringSqlParameter("@email", 75, $this->getParameterValue($parameters, "email")),
			$this->createErrorStringSqlParameter("@auth_Error"),
		));
	}
	
	public function updateUser($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateUser", array(
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID"), SqlParameterDirections::InOut),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID"), SqlParameterDirections::InOut),
			$this->createIntegerSqlParameter("@invitationID", $this->getParameterValue($parameters, "invitationID")),
			$this->createStringSqlParameter("@invitationCode", 50, $this->getParameterValue($parameters, "invitationCode")),
			$this->createStringSqlParameter("@email", 75, $this->getParameterValue($parameters, "email")),
			$this->createStringSqlParameter("@password", 50, $this->getParameterValue($parameters, "password")),
			$this->createStringSqlParameter("@passwordConfirm", 50, $this->getParameterValue($parameters, "passwordConfirm")),
			$this->createStringSqlParameter("@salt", 50, $this->getParameterValue($parameters, "salt")),
			$this->createIntegerSqlParameter("@authProviderID", $this->getParameterValue($parameters, "authProviderID")),
			$this->createStringSqlParameter("@authID", 50, $this->getParameterValue($parameters, "authID")),
			$this->createStringSqlParameter("@firstName", 30, $this->getParameterValue($parameters, "firstName")),
			$this->createStringSqlParameter("@lastName", 30, $this->getParameterValue($parameters, "lastName")),
			$this->createStringSqlParameter("@phone", 20, $this->getParameterValue($parameters, "phone")),
			$this->createStringSqlParameter("@phoneExt", 8, $this->getParameterValue($parameters, "phoneExt")),
			$this->createStringSqlParameter("@imageUrl", 500, $this->getParameterValue($parameters, "imageUrl")),
			$this->createIntegerSqlParameter("@permissions", $this->getParameterValue($parameters,"permissions")),
			$this->createErrorStringSqlParameter("@accountID_Error"),
			$this->createErrorStringSqlParameter("@invitation_Error"),
			$this->createErrorStringSqlParameter("@email_Error"),
			$this->createErrorStringSqlParameter("@password_Error"),
			$this->createErrorStringSqlParameter("@passwordConfirm_Error"),
			$this->createErrorStringSqlParameter("@salt_Error"),
			$this->createErrorStringSqlParameter("@firstName_Error"),
			$this->createErrorStringSqlParameter("@lastName_Error"),
			$this->createErrorStringSqlParameter("@authProviderID_Error"),
			$this->createErrorStringSqlParameter("@authID_Error"),
		));
	}
	
	public function authenticateUser($parameters)
	{
		return $this->callStandardStoredProcedure("acct.authenticateUser", array(
			$this->createStringSqlParameter("@email", 75, $this->getParameterValue($parameters, "email")),
			$this->createStringSqlParameter("@password", 50, $this->getParameterValue($parameters, "password")),
			$this->createIntegerSqlParameter("@authProviderID", $this->getParameterValue($parameters, "authProviderID")),
			$this->createStringSqlParameter("@authID", 50, $this->getParameterValue($parameters, "authID")),
			$this->createIntegerSqlParameter("@invitationID", $this->getParameterValue($parameters, "invitationID")),
			$this->createStringSqlParameter("@invitationCode", 50, $this->getParameterValue($parameters, "invitationCode")),
			$this->createStringSqlParameter("@imageUrl", 500, $this->getParameterValue($parameters, "imageUrl")),
			$this->createStringSqlParameter("@ipAddress", 50, $this->getParameterValue($parameters, "ipAddress")),
			$this->createErrorStringSqlParameter("@auth_Error"),
			$this->createErrorStringSqlParameter("@invitation_Error"),
		));
	}
	
	public function updateUser_Active($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateUser_Active", array(
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createBitSqlParameter("isActive",  $this->getParameterValue($parameters, "isActive")),
		));
	}
	
	public function updateUser_Forgot($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateUser_Forgot", array(
			$this->createStringSqlParameter("@email", 75, $this->getParameterValue($parameters, "email")),
			$this->createErrorStringSqlParameter("@email_Error"),
		));
	}
	
	public function updateUser_Password($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateUser_Password", array(
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createStringSqlParameter("@password", 50, $this->getParameterValue($parameters, "password")),
			$this->createStringSqlParameter("@newPassword", 50, $this->getParameterValue($parameters, "newPassword")),
			$this->createStringSqlParameter("@newPasswordConfirm", 50, $this->getParameterValue($parameters, "newPasswordConfirm")),
			$this->createStringSqlParameter("@salt", 50, $this->getParameterValue($parameters, "salt")),
			$this->createErrorStringSqlParameter("@password_Error"),
			$this->createErrorStringSqlParameter("@newPassword_Error"),
			$this->createErrorStringSqlParameter("@newPasswordConfirm_Error"),
			$this->createErrorStringSqlParameter("@salt_Error"),
		));
	}
	
	public function updateUser_Recovery($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateUser_Recovery", array(
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createStringSqlParameter("@email", 75, $this->getParameterValue($parameters, "email")),
			$this->createStringSqlParameter("@recoveryKey", 40, $this->getParameterValue($parameters, "recoveryKey")),
			$this->createStringSqlParameter("@password", 50, $this->getParameterValue($parameters, "password")),
			$this->createStringSqlParameter("@passwordConfirm", 50, $this->getParameterValue($parameters, "passwordConfirm")),
			$this->createStringSqlParameter("@salt", 50, $this->getParameterValue($parameters, "salt")),
			$this->createBitSqlParameter("@recoveryExpired",  $this->getParameterValue($parameters, "recoveryExpired"), SqlParameterDirections::InOut),
			$this->createErrorStringSqlParameter("@email_Error"),
			$this->createErrorStringSqlParameter("@password_Error"),
			$this->createErrorStringSqlParameter("@passwordConfirm_Error"),
			$this->createErrorStringSqlParameter("@recoveryKey_Error"),
		));
	}
	
	public function updateUser_Email($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateUser_Email", array(
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createStringSqlParameter("@email", 50, $this->getParameterValue($parameters, "email")),
			$this->createErrorStringSqlParameter("@email_Error"),
		));
	}
	
/* ACCOUNT USERS */
	public function getAccountUsers($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.getAccountUsers", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
		));
	}
	
	public function getUserAccounts($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.getUserAccounts", array(
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
		));
	}
	
	public function updateAccountUser($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateAccountUser", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createIntegerSqlParameter("@permissions", $this->getParameterValue($parameters, "permissions")),
			$this->createErrorStringSqlParameter("@accountID_Error"),
			$this->createErrorStringSqlParameter("@userID_Error"),
		));
	}

	public function updateAccountUser_Owner($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateAccountUser_Owner", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
		));
	}
	
	public function deleteAccountUser($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.deleteAccountUser", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
		));
	}
	
/* CONTACTS */
	public function getContacts($parameters = array())
	{
		return $this->callPagedStoredProcedure("acct.getContacts", $parameters, array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createIntegerSqlParameter("@contactID", $this->getParameterValue($parameters, "contactID")),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
		), array(
		));
	}
		
	public function deleteContact($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.deleteContact", array(
			$this->createIntegerSqlParameter("@contactID", $this->getParameterValue($parameters, "contactID")),
		));
	}

	public function updateContact($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateContact", array(
			$this->createIntegerSqlParameter("@contactID", $this->getParameterValue($parameters, "contactID"), SqlParameterDirections::InOut),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createIntegerSqlParameter("@contactTypeID", $this->getParameterValue($parameters, "contactTypeID")),
			$this->createStringSqlParameter("@firstName", 50, $this->getParameterValue($parameters, "firstName")),
			$this->createStringSqlParameter("@lastName", 50, $this->getParameterValue($parameters, "lastName")),
			$this->createStringSqlParameter("@email", 75, $this->getParameterValue($parameters, "email")),
			$this->createStringSqlParameter("@officePhone", 20, $this->getParameterValue($parameters, "officePhone")),
			$this->createStringSqlParameter("@officePhoneExt", 8, $this->getParameterValue($parameters, "officePhoneExt")),
			$this->createStringSqlParameter("@cellPhone", 20, $this->getParameterValue($parameters, "cellPhone")),
			$this->createStringSqlParameter("@homePhone", 20, $this->getParameterValue($parameters, "homePhone")),
			$this->createErrorStringSqlParameter("@accountID_Error"),
			$this->createErrorStringSqlParameter("@firstName_Error"),
			$this->createErrorStringSqlParameter("@lastName_Error"),
		));
	}
	
/* ALERTS */
	public function getAlerts($parameters = array())
	{
		return $this->callPagedStoredProcedure("acct.getAlerts", $parameters, array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createIntegerSqlParameter("@alertID", $this->getParameterValue($parameters, "alertID")),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
		), array(
		));
	}

	public function deleteAlert($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.deleteAlert", array(
			$this->createIntegerSqlParameter("@alertID", $this->getParameterValue($parameters, "alertID")),
		));
	}

	public function updateAlert($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateAlert", array(
			$this->createIntegerSqlParameter("@alertID", $this->getParameterValue($parameters, "alertID"), SqlParameterDirections::InOut),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createStringSqlParameter("@email", 1000, $this->getParameterValue($parameters, "email")),
			$this->createDateTimeSqlParameter("@alertDate", $this->getParameterValue($parameters, "alertDate")),
			$this->createStringSqlParameter("@alertTitle", 1000, $this->getParameterValue($parameters, "alertTitle")),
			$this->createStringMaxSqlParameter("@alertDescription", $this->getParameterValue($parameters, "alertDescription")),
			$this->createErrorStringSqlParameter("@accountID_Error"),
			$this->createErrorStringSqlParameter("@email_Error"),
			$this->createErrorStringSqlParameter("@alertDate_Error"),
			$this->createErrorStringSqlParameter("@alertTitle_Error"),
		));
	}
	
/* PAYMENT METHODS */
	public function updatePaymentMethod($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updatePaymentMethod", array(
			$this->createIntegerSqlParameter("@paymentMethodID", $this->getParameterValue($parameters, "paymentMethodID"), SqlParameterDirections::InOut),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createIntegerSqlParameter("@paymentTypeID", $this->getParameterValue($parameters, "paymentTypeID")),
			$this->createStringSqlParameter("@paymentMethodName", 100, $this->getParameterValue($parameters, "paymentMethodName")),
			$this->createIntegerSqlParameter("@creditCardTypeID", $this->getParameterValue($parameters, "creditCardTypeID")),
			$this->createStringSqlParameter("@creditCardNameSalt", 100, $this->getParameterValue($parameters, "creditCardNameSalt")),
			$this->createStringSqlParameter("@creditCardName", 100, $this->getParameterValue($parameters, "creditCardName")),
			$this->createStringSqlParameter("@creditCardNumberSalt", 100, $this->getParameterValue($parameters, "creditCardNumberSalt")),
			$this->createStringSqlParameter("@creditCardNumber", 100, $this->getParameterValue($parameters, "creditCardNumber")),
			$this->createStringSqlParameter("@creditCardNumberTruncated", 25, $this->getParameterValue($parameters, "creditCardNumberTruncated")),
			$this->createStringSqlParameter("@creditCardExpirationSalt", 100, $this->getParameterValue($parameters, "creditCardExpirationSalt")),
			$this->createStringSqlParameter("@creditCardExpiration", 100, $this->getParameterValue($parameters, "creditCardExpiration")),
			$this->createStringSqlParameter("@paymentCountryID", 2, $this->getParameterValue($parameters, "paymentCountryID")),
			$this->createStringSqlParameter("@paymentAddress", 100, $this->getParameterValue($parameters, "paymentAddress")),
			$this->createStringSqlParameter("@paymentCity", 50, $this->getParameterValue($parameters, "paymentCity")),
			$this->createStringSqlParameter("@paymentStateID", 3, $this->getParameterValue($parameters, "paymentStateID")),
			$this->createStringSqlParameter("@paymentPostalCode", 30, $this->getParameterValue($parameters, "paymentPostalCode")),
			$this->createStringSqlParameter("@echeckRoutingNumberSalt", 100, $this->getParameterValue($parameters, "echeckRoutingNumberSalt")),
			$this->createStringSqlParameter("@echeckRoutingNumber", 100, $this->getParameterValue($parameters, "echeckRoutingNumber")),
			$this->createStringSqlParameter("@echeckAccountNumberSalt", 100, $this->getParameterValue($parameters, "echeckAccountNumberSalt")),
			$this->createStringSqlParameter("@echeckAccountNumber", 100, $this->getParameterValue($parameters, "echeckAccountNumber")),
			$this->createIntegerSqlParameter("@bankAccountTypeID", $this->getParameterValue($parameters, "bankAccountTypeID")),
			$this->createStringSqlParameter("@echeckNameSalt", 100, $this->getParameterValue($parameters, "echeckNameSalt")),
			$this->createStringSqlParameter("@echeckName", 100, $this->getParameterValue($parameters, "echeckName")),
			$this->createStringSqlParameter("@echeckBankNameSalt", 100, $this->getParameterValue($parameters, "echeckBankNameSalt")),
			$this->createStringSqlParameter("@echeckBankName", 100, $this->getParameterValue($parameters, "echeckBankName")),
			$this->createBitSqlParameter("@isPrimary",  $this->getParameterValue($parameters, "isPrimary")),
			$this->createErrorStringSqlParameter("@accountID_Error"),
			$this->createErrorStringSqlParameter("@paymentTypeID_Error"),
			$this->createErrorStringSqlParameter("@paymentMethodName_Error"),
			$this->createErrorStringSqlParameter("@creditCardTypeID_Error"),
			$this->createErrorStringSqlParameter("@creditCardNameSalt_Error"),
			$this->createErrorStringSqlParameter("@creditCardName_Error"),
			$this->createErrorStringSqlParameter("@creditCardNumberSalt_Error"),
			$this->createErrorStringSqlParameter("@creditCardNumber_Error"),
			$this->createErrorStringSqlParameter("@creditCardNumberTruncated_Error"),
			$this->createErrorStringSqlParameter("@creditCardExpirationSalt_Error"),
			$this->createErrorStringSqlParameter("@creditCardExpiration_Error"),
			$this->createErrorStringSqlParameter("@paymentCountryID_Error"),
			$this->createErrorStringSqlParameter("@paymentAddress_Error"),
			$this->createErrorStringSqlParameter("@paymentCity_Error"),
			$this->createErrorStringSqlParameter("@paymentStateID_Error"),
			$this->createErrorStringSqlParameter("@paymentPostalCode_Error"),
			$this->createErrorStringSqlParameter("@echeckRoutingNumberSalt_Error"),
			$this->createErrorStringSqlParameter("@echeckRoutingNumber_Error"),
			$this->createErrorStringSqlParameter("@echeckAccountNumberSalt_Error"),
			$this->createErrorStringSqlParameter("@echeckAccountNumber_Error"),
			$this->createErrorStringSqlParameter("@bankAccountTypeID_Error"),
			$this->createErrorStringSqlParameter("@echeckNameSalt_Error"),
			$this->createErrorStringSqlParameter("@echeckName_Error"),
			$this->createErrorStringSqlParameter("@echeckBankNameSalt_Error"),
			$this->createErrorStringSqlParameter("@echeckBankName_Error"),
		));
	}
	
	public function deletePaymentMethod($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.deletePaymentMethod", array(
			$this->createIntegerSqlParameter("@paymentMethodID", $this->getParameterValue($parameters, "paymentMethodID")),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
		));
	}
	
	public function getPaymentMethods($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.getPaymentMethods", array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createIntegerSqlParameter("@paymentMethodID", $this->getParameterValue($parameters, "paymentMethodID")),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
		));
	}

/* PRODUCTS */
	public function getAccountProducts($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.getAccountProducts", array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createIntegerSqlParameter("@accountProductID", $this->getParameterValue($parameters, "accountProductID")),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
		));
	}
	
	public function updateAccountProduct($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateAccountProduct", array(
			$this->createIntegerSqlParameter("@accountProductID", $this->getParameterValue($parameters, "accountProductID")),
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createIntegerSqlParameter("@productID", $this->getParameterValue($parameters, "productID")),
			$this->createIntegerSqlParameter("@planID", $this->getParameterValue($parameters, "planID")),
			$this->createDecimalSqlParameter("@price", 9, 2, $this->getParameterValue($parameters, "price")),
			$this->createErrorStringSqlParameter("@accountID_Error"),
			$this->createErrorStringSqlParameter("@productID_Error"),
			$this->createErrorStringSqlParameter("@planID_Error"),
			$this->createErrorStringSqlParameter("@price_Error"),
		));
	}
	
	public function updateAccountProduct_Active($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateAccountProduct_Active", array(
			$this->createIntegerSqlParameter("@accountProductID", $this->getParameterValue($parameters, "accountProductID")),
			$this->createBitSqlParameter("@isActive",  $this->getParameterValue($parameters, "isActive")),
		));
	}

/* PASSWORD RECOVERY */
	public function getPasswordRecovery($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.getPasswordRecovery", array(
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
		));
	}
	
/* Permissions */
	public function getPermissions($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.getPermissions", array(
			$this->createIntegerSqlParameter("@permissionID", $this->getParameterValue($parameters, "permissionID")),
		));
	}
	
/* Account Permissions */
	public function updateAccountUser_Permissions($parameters = array())
	{
		return $this->callStandardStoredProcedure("acct.updateAccountUser_Permissions", array(
			$this->createIntegerSqlParameter("@accountID", $this->getParameterValue($parameters, "accountID")),
			$this->createIntegerSqlParameter("@userID", $this->getParameterValue($parameters, "userID")),
			$this->createIntegerSqlParameter("@permissions", $this->getParameterValue($parameters, "permissions")),
		));
	}
}
?>