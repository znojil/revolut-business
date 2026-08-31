<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Enum;

/**
 * @link https://developer.revolut.com/docs/api/business#get-exchange-reasons
 */
enum ExchangeReasonCode: string{

	case BusinessExpenseAndClaims = 'business_expense_and_claims';

	case FeesAndCharges = 'fees_and_charges';

	case FundTransferAndIntracompanyPayment = 'fund_transfer_and_intracompany_payment';

	case GiftsAndDonations = 'gifts_and_donations';

	case GovernmentServicesAndTax = 'government_services_and_tax';

	case Insurance = 'insurance';

	case Inventory = 'inventory';

	case InvestmentDividendAndInterest = 'investment_dividend_and_interest';

	case LoanAndLoanRepayment = 'loan_and_loan_repayment';

	case Marketing = 'marketing';

	case PaymentForGoodsAndServices = 'payment_for_goods_and_services';

	case Payroll = 'payroll';

	case Refund = 'refund';

	case RentalAndProperty = 'rental_and_property';

	case Sales = 'sales';

	case ServiceProviderAndSoftware = 'service_provider_and_software';

	case TravelAndTransportation = 'travel_and_transportation';

	case Utilities = 'utilities';

}
