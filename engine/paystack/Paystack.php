<?php
/**
 * Paystack PHP Library
 *
 * This PHP library provides an interface to interact with the Paystack API,
 * enabling developers to handle payment transactions, transfers, verification,
 * and other financial operations easily.
 *
 * @version 1.0
 * @license MIT
 * @author Claude Amadu
 * @link https://github.com/paystackhq/paystack-php
 * @link https://paystack.com/docs/api
 */

class Paystack
{
    private $api_key;

    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }
    private function setHttp($method, $url, $params)
    {
        $END_POINT = 'https://api.paystack.co';
        try {
            $headers = [
                'Authorization: Bearer ' . $this->api_key,
                "Cache-Control: no-cache",
            ];

            $curl = curl_init();

            // Set common cURL options
            curl_setopt_array($curl, [
                CURLOPT_URL => $END_POINT . $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => strtoupper($method),
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => http_build_query($params),
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);

            curl_close($curl);

            if ($error) {
                throw new Exception('Request Error: ' . $error);
            }
			// Decode the JSON response
			$jsonResponse = json_decode($response);

			if (json_last_error() !== JSON_ERROR_NONE) {
				throw new Exception('Invalid JSON response');
			}

			return $jsonResponse;
        } catch (Exception $exception) {
            // Handle request exception
            return null;
        }
    }

    //Bonus Start
    public function detectNetwork($phoneNumber)
    {
        $prefix = substr($phoneNumber, 0, 3); // Extract the first three digits

        switch ($prefix) {
            case "053":
            case "055":
            case "059":
            case "054":
            case "024":
                $network = "MTN";
                break;
            case "050":
            case "020":
                $network = "VOD";
                break;
            case "057":
            case "027":
                $network = "ATL";
                break;
            default:
                $network = "";
                break;
        }

        return $network;
    }
    //Bonus End

	// Transactions Start
    public function initializeTransaction($amount, $email, $currency = 'GHS', $callback_url = '', $split_code = '', $reference)
    {
        $fields = [
            "amount" => $amount*100,
            "email" => $email,
            "currency" => $currency,
            "callback_url" => $callback_url,
            "reference" => $reference,
            "split_code" => $split_code
        ];
        return $this->setHttp('post', '/transaction/initialize', $fields);
    }
    public function verifyTransaction($reference)
    {
        $fields = [];
        return $this->setHttp('get', '/transaction/verify/'.$reference, $fields);
    }
	// Transactions End
    
	// Transaction Split Start
    public function createSplit($name, $currency, $subaccounts = [], $split_type)
    {
        $fields = [
            "name" => $name,
            "type" => $split_type,
            "currency" => $currency,
            "bearer_type" => 'account',
            "subaccounts" => $subaccounts
        ];
        return $this->setHttp('post', '/split', $fields);
    }
    
	// Transaction Split End
    
	// Subaccounts Start
    public function createSubAccount($business_name, $name, $bank, $number, $email, $description, $percentage_charge)
    {
        $fields = [
            "business_name" => $business_name,
            "settlement_bank" => $bank,
            "account_number" => $number,
            "percentage_charge" => $percentage_charge,
            "description" => $description,
            "primary_contact_name" => $name,
            "primary_contact_email" => $email,
        ];
        return $this->setHttp('post', '/subaccount', $fields);
    }
    
    public function updateSubAccount($code, $business_name, $name, $bank, $number, $email, $description, $percentage_charge)
    {
        $fields = [
            "business_name" => $business_name,
            "settlement_bank" => $bank,
            "account_number" => $number,
            "percentage_charge" => $percentage_charge,
            "description" => $description,
            "primary_contact_name" => $name,
            "primary_contact_email" => $email,
        ];
        return $this->setHttp('put', '/subaccount/'.$code, $fields);
    }
    
    public function fetchSubAccount($id_or_code){
        return $this->setHttp('get', '/subaccount/'.$id_or_code, []);
    }
	
	//Subaccounts End

    // Transfers Recipients Start
    public function createTransferReceipient($name, $bank, $number, $email)
    {
        $fields = [
            "type" => "mobile_money",
            "name" => $name,
            "email" => $email,
            "account_number" => $number,
            "bank_code" => $bank,
            "currency" => "GHS"
        ];
        return $this->setHttp('post', '/transferrecipient', $fields);
    }
    public function updateTransferReceipient($name, $code, $email)
    {
        $fields = [
            "type" => "mobile_money",
            "name" => $name,
            "email" => $email
        ];
        return $this->setHttp('put', '/transferrecipient/' . $code, $fields);
    }
    public function fetchTransferReceipient($code)
    {
        $fields = [];
        return $this->setHttp('get', '/transferrecipient/' . $code, $fields);
    }
    public function deleteTransferReceipient($code)
    {
        $fields = [];
        return $this->setHttp('delete', '/transferrecipient/' . $code, $fields);
    }
    // Transfers Recipients End

    //Transfers Start
    public function initiateTransfer($source, $reason, $amount, $recipient)
    {
        $fields = [
            "source" => $source,
            "reason" => $reason,
            "amount" => $amount * 100,
            "recipient" => $recipient,
            "currency" => "GHS"
        ];
        return $this->setHttp('post', '/transfer', $fields);
    }
    public function finalizeTransfer($transfer_code, $otp)
    {
        $fields = [
            "transfer_code" => $transfer_code,
            "otp" => $otp,
        ];
        return $this->setHttp('post', '/transfer/finalize_transfer', $fields);
    }
    public function fetchTransfer($transfer_code)
    {
        $fields = [];
        return $this->setHttp('get', '/transfer/'.$transfer_code, $fields);
    }
    public function verifyTransfer($reference)
    {
        $fields = [];
        return $this->setHttp('get', '/transfer/verify/'.$reference, $fields);
    }
    //Transfers End

    //Charge Start
        public function charge($email, $amount, $network, $phone_number, $code_type = '', $code = '')
        {
            $fields = [
                'email' => $email,
                'amount' => $amount*100,
                'currency' => "GHS",
                "mobile_money" => [
                    "provider" => $network,
                    "phone" => $phone_number
                ]
            ];
            
            if(!empty($code_type) && !empty($code)){
                $fields["$code_type"] = $code;
            }
            
            return $this->setHttp('post', '/charge', $fields);
        }
    public function submitOTP($otp, $reference)
    {
        $fields = [
            'otp' => $otp,
            'reference' => $reference
        ];
        return $this->setHttp('post', '/charge/submit_otp', $fields);
    }
    public function submitPIN($pin, $reference)
    {
        $fields = [
            'pin' => $pin,
            'reference' => $reference
        ];
        return $this->setHttp('post', '/charge/submit_pin', $fields);
    }
    public function pendingCharge($reference)
    {
        $fields = [];
        return $this->setHttp('get', '/charge/'.$reference, $fields);
    }
    //Charge Start

    //Verification Start
    public function verifyAccount($account_number, $bank_code)
    {
        $fields = [];
        return $this->setHttp('get', "/bank/resolve?account_number=$account_number&bank_code=$bank_code", $fields);
    }
    //Verification End

	// Miscellaneous Start
    public function listBanks($country, $type, $currency)
    {
        $fields = [];
        return $this->setHttp('get', "/bank?country=$country&type=$type&currency=$currency", $fields);
    }

	// Miscellaneous End
    
    // Settlements Start
    public function ListSettlements($subaccount = 'none', $perPage = 50, $page = 1, $status = '', $from = '', $to = '')
    {
        $fields = [
            'perPage' => $perPage,
            'page' => $page,
            'subaccount' => $subaccount
        ];
        
        if(!empty($status)){
            $fields['status'] = $status;
        }
        
        if(!empty($from)){
            $fields['from'] = $from;
        }
        
        if(!empty($from) && !empty($to)){
            $fields['to'] = $to;
        }
        
        $query = http_build_query($fields);
        
        return $this->setHttp('get', '/settlement?'.$query, $fields);
    }
    
    public function ListSettlementTransactions($id, $perPage = 50, $page = 1, $from = '', $to = '', $status = 'success')
    {
        $fields = [
            'perPage' => $perPage,
            'page' => $page,
            'status' => $status
        ];
        
        if(!empty($from)){
            $fields['from'] = $from;
        }
        
        if(!empty($from) && !empty($to)){
            $fields['to'] = $to;
        }
        
        $query = http_build_query($fields);
        
        return $this->setHttp('get', "/settlement/$id/transactions?$query", $fields);
    }
    // Settlements End
    
    // Customers Start    
    public function CreateCustomer($email, $first_name, $last_name, $phone, $metadata = [])
    {
        $fields = [
            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
        ]; 
        
        if(count($metadata) > 0){
            $fields['metadata'] = $metadata;
        }       
        return $this->setHttp('post', '/customer', $fields);
    }
    
    public function FetchCustomer($code)
    {
        $fields = [];        
        return $this->setHttp('get', '/customer/'.$code, $fields);
    }    
    
    public function UpdateCustomer($code, $first_name, $last_name, $phone, $metadata = [])
    {
        $fields = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'phone' => $phone,
        ]; 
        
        if(count($metadata) > 0){
            $fields['metadata'] = $metadata;
        }       
        return $this->setHttp('put', '/customer/'.$code, $fields);
    }
    // Customers End
}

?>