<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
	public function creditAdd(Request $request)
	{
		$request->validate([
			'amount' => 'required|numeric|min:1',
			'cardholder_name' => 'required|string',
			'card_number' => 'required|string',
			'exp_month' => 'required|numeric',
			'exp_year' => 'required|numeric',
			'cvc' => 'required|string',
		]);

		// PayMongo API: Create payment method (card)
		$client = new Client();
		$apiKey = env('PAYMONGO_SECRET_KEY');
		try {
			$response = $client->post('https://api.paymongo.com/v1/payment_methods', [
				'auth' => [$apiKey, ''],
				'headers' => [
					'Content-Type' => 'application/json',
					'accept' => 'application/json',
				],
				'verify' => 'C:/xampp/htdocs/SmartPayBeta/cacert.pem',
				'json' => [
					'data' => [
						'attributes' => [
							'type' => 'card',
							'details' => [
								'card_number' => $request->card_number,
								'exp_month' => (int)$request->exp_month,
								'exp_year' => (int)$request->exp_year,
								'cvc' => $request->cvc,
							],
							'billing' => [
								'name' => $request->cardholder_name,
								'email' => $request->email,
							],
						]
					]
				]
			]);
			$result = json_decode($response->getBody(), true);
			if (isset($result['data']['id'])) {
				// Payment method created successfully, update balance
				$studentId = session('student_id');
				$student = Student::find($studentId);
				if ($student) {
					$student->Balance += $request->amount;
					$student->save();
					return redirect()->back()->with('success', 'Successfully added ₱' . number_format($request->amount, 2) . ' to your credit balance.');
				} else {
					return redirect()->back()->with('error', 'Student not found.');
				}
			} else {
				return redirect()->back()->with('error', 'Payment method creation failed.');
			}
		} catch (\GuzzleHttp\Exception\ClientException $e) {
			// Handle 400 Bad Request from PayMongo
			if ($e->getResponse() && $e->getResponse()->getStatusCode() === 400) {
				return redirect()->back()->with('error', 'Request Invalid');
			}
			return redirect()->back()->with('error', 'PayMongo error: ' . $e->getMessage());
		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'PayMongo error: ' . $e->getMessage());
		}
	}

    public function gcashPay(Request $request)
	{
		$request->validate([
			'amount' => 'required|numeric|min:1',
			'email' => 'required|email',
		]);

		$client = new \GuzzleHttp\Client();
		$apiKey = env('PAYMONGO_SECRET_KEY');
		try {
			$response = $client->post('https://api.paymongo.com/v1/sources', [
				'auth' => [$apiKey, ''],
				'headers' => [
					'Content-Type' => 'application/json',
					'accept' => 'application/json',
				],
				'verify' => 'C:/xampp/htdocs/SmartPayBeta/cacert.pem',
				'json' => [
					'data' => [
						'attributes' => [
							'amount' => (int)($request->amount * 100), // PayMongo expects amount in centavos
							'redirect' => [
								'success' => url('/student/credit'),
								'failed' => url('/student/credit'),
							],
							'type' => 'gcash',
							'currency' => 'PHP',
							'billing' => [
								'name' => $request->name,
								'email' => $request->email,
							],
						]
					]
				]
			]);
			$result = json_decode($response->getBody(), true);
			if (isset($result['data']['attributes']['redirect']['checkout_url'])) {
				return redirect($result['data']['attributes']['redirect']['checkout_url']);
			} else {
				return redirect()->back()->with('error', 'GCash payment initiation failed.');
			}
		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'PayMongo error: ' . $e->getMessage());
		}
	}

	// Handle PayMongo webhook for GCash payment confirmation
    public function handlePaymongoWebhook(Request $request)
    {
		$payload = $request->all();
		if (
			isset($payload['data']['attributes']['status']) &&
			$payload['data']['attributes']['status'] === 'paid'
		) {
			$email = $payload['data']['attributes']['billing']['email'] ?? null;
			$amount = $payload['data']['attributes']['amount'] ?? 0;
			if ($email && $amount) {
				$student = \App\Models\Student::whereRaw('LOWER(gmail) = ?', [strtolower($email)])->first();
				if ($student) {
					$student->Balance += $amount / 100; // Convert centavos to pesos
					$student->save();
				}
			}
		}
		return response()->json(['status' => 'ok']);
    }
}
