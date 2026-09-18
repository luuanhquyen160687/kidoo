<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /**
     * Single inbound endpoint for payment gateway webhooks/IPN calls,
     * addressed as /webhooks/payment/{gateway}. No gateway is wired up yet:
     * this verifies a generic HMAC signature (per-gateway secret in
     * config/services.php) and logs the raw payload to a dedicated channel
     * so a real handler (crediting balance, marking tuition paid, ...) can
     * be built once a provider is chosen.
     */
    public function handle(Request $request, string $gateway)
    {
        $rawPayload = $request->getContent();
        $config = config("services.payment_webhooks.{$gateway}") ?? config('services.payment_webhooks.default');
        $verified = $this->verifySignature($request, $rawPayload, $config);

        Log::channel('payment_webhooks')->info('Payment webhook received', [
            'gateway' => $gateway,
            'ip' => $request->ip(),
            'verified' => $verified,
            'headers' => $request->headers->all(),
            'payload' => $request->all() ?: $rawPayload,
        ]);

        if (!$verified) {
            abort(401, 'Invalid signature');
        }

        // TODO: once a real gateway is integrated, parse $request and dispatch
        // to the right handler, e.g. recordSchoolTransaction($school_id, 'tuition_payment',
        // 'credit', $amount, ['reference_type' => 'student_tuitions', 'reference_id' => $tuitionId]).

        return response()->json(['status' => 'ok']);
    }

    private function verifySignature(Request $request, string $rawPayload, ?array $config): bool
    {
        
        



        if (!$config || empty($config['secret'])) {
            return false;
        }

        $signature = $request->header($config['signature_header'] ?? 'X-SePay-Signature')
            ?? $request->input($config['signature_field'] ?? 'signature');
        $timestamp = $request->header($config['signature_timestamp']);
        
        if (!$signature) {
            return false;
        }
        $payload = file_get_contents('php://input');
        $expected = 'sha256=' . hash_hmac('sha256', $timestamp . '.' . $payload, $config['secret']);
    
        return hash_equals($expected, (string) $signature);
    }
}
