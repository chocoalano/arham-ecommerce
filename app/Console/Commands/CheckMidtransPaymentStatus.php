<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Console\Command;

class CheckMidtransPaymentStatus extends Command
{
    protected $signature = 'midtrans:check-status {order_number} {--update : Automatically update payment status without confirmation}';

    protected $description = 'Check and update payment status from Midtrans API';

    public function __construct(private MidtransService $midtrans)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $orderNumber = $this->argument('order_number');

        $this->info("Checking payment status for order: {$orderNumber}");

        // Find payment
        $payment = Payment::where('order_id_ref', $orderNumber)->first();

        if (! $payment) {
            $this->error("Payment not found for order: {$orderNumber}");

            return self::FAILURE;
        }

        $this->info("Payment ID: {$payment->id}");
        $this->info("Current Status: {$payment->transaction_status}");

        try {
            // Check status from Midtrans API
            $status = $this->midtrans->checkTransactionStatus($orderNumber);

            $this->table(
                ['Field', 'Value'],
                [
                    ['Order ID', $status['order_id']],
                    ['Transaction Status', $status['transaction_status']],
                    ['Fraud Status', $status['fraud_status'] ?? 'N/A'],
                    ['Payment Type', $status['payment_type']],
                    ['Transaction Time', $status['transaction_time']],
                    ['Gross Amount', $status['gross_amount']],
                ]
            );

            // Check if auto-update or needs confirmation
            $shouldUpdate = $this->option('update') || $this->confirm('Do you want to update the local payment record with this status?', true);

            if ($shouldUpdate) {
                // Simulate notification
                $notificationData = [
                    'order_id' => $status['order_id'],
                    'transaction_status' => $status['transaction_status'],
                    'fraud_status' => $status['fraud_status'] ?? null,
                    'payment_type' => $status['payment_type'],
                    'transaction_id' => $status['order_id'],
                    'transaction_time' => $status['transaction_time'],
                    'gross_amount' => $status['gross_amount'],
                    'status_code' => '200',
                    'signature_key' => $this->generateSignature(
                        $status['order_id'],
                        '200',
                        $status['gross_amount']
                    ),
                ];

                $result = $this->midtrans->handleNotification($notificationData);

                $this->info('✓ Payment status updated successfully!');
                $this->info("Order Status: {$result['order_status']}");
                $this->info("Transaction Status: {$result['transaction_status']}");

                return self::SUCCESS;
            }

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to check status: {$e->getMessage()}");

            return self::FAILURE;
        }
    }

    private function generateSignature(string $orderId, string $statusCode, string $grossAmount): string
    {
        $serverKey = config('services.midtrans.server_key');

        return hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
    }
}
