<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $payload = json_decode($rawPayload,true);

        // log payment
        Log::channel('payment_webhooks')->info('Payment webhook received', [
            'gateway' => $gateway,
            'ip' => $request->ip(),
            'payload' => $payload,
        ]);

        if ($payload['notification_type'] === 'ORDER_PAID') {
            $order_invoice_number=$payload['order']['order_invoice_number'];
            $order_invoice_numbers=explode("-",$order_invoice_number);
            if($order_invoice_numbers[0]=='student_tuitions'){
                $student_tuitions_id=$order_invoice_numbers[1];
                $tuition = DB::table('student_tuitions')->where('id', $student_tuitions_id)->first();

                // update student_tuition payment status
                DB::table('student_tuitions')
                    ->where('id', $student_tuitions_id)
                    ->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'updated_at' => now(),
                    ]);

                // add new school_transaction
                $amount = DB::table('student_tuitions_fees')->where('tuition_id', $student_tuitions_id)->sum('amount');

                $student = DB::table('students')->where('id', $tuition->student_id)->first();
                $class = DB::table('class_student')
                    ->join('classes', 'classes.id', 'class_student.class_id')
                    ->where('class_student.student_id', $tuition->student_id)
                    ->select('classes.name')
                    ->first();

                recordSchoolTransaction($tuition->school_id, 'tuition_payment', 'credit', $amount, [
                    'reference_type' => 'student_tuitions',
                    'reference_id' => $student_tuitions_id,
                    'description' => "Thanh toán học phí tháng {$tuition->month} năm {$tuition->year} - {$student->name} - Lớp {$class->name}",
                ]);
            }
        }
        

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
