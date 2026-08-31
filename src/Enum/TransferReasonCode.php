<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/api/business#get-transfer-reasons
 */
enum TransferReasonCode: string{

	case Advertising = 'advertising';

	case AdvisorFees = 'advisor_fees';

	// not documented in the specification
	case BusinessExpenses = 'business_expenses';

	case BusinessInsurance = 'business_insurance';

	// not documented in the specification
	case Commission = 'commission';

	case Construction = 'construction';

	// not documented in the specification
	case Bills = 'bills';

	case Delivery = 'delivery';

	// not documented in the specification
	case Dividends = 'dividends';

	// not documented in the specification
	case Donations = 'donations';

	case Education = 'education';

	case Exports = 'exports';

	case Family = 'family';

	// not documented in the specification
	case FamilySupport = 'family_support';

	case FundInvestment = 'fund_investment';

	case Goods = 'goods';

	case Homesend = 'homesend';

	case Hotel = 'hotel';

	case InsuranceClaims = 'insurance_claims';

	case InsurancePremium = 'insurance_premium';

	case LoanRepayment = 'loan_repayment';

	case Medical = 'medical';

	case Office = 'office';

	// not documented in the specification
	case Other = 'other';

	case OtherFees = 'other_fees';

	// not documented in the specification
	case Pension = 'pension';

	case PersonalTransfer = 'personal_transfer';

	case PropertyPurchase = 'property_purchase';

	case PropertyRental = 'property_rental';

	case Royalties = 'royalties';

	// not documented in the specification
	case Salary = 'salary';

	case Services = 'services';

	case ShareInvestment = 'share_investment';

	case Tax = 'tax';

	case Transfer = 'transfer';

	case Transportation = 'transportation';

	case Travel = 'travel';

	case Utilities = 'utilities';

}
