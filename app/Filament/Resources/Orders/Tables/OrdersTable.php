<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Services\MidtransService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('voucher.id')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('customer_email')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('customer_phone')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('billingAddress.recipient_name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('shippingAddress.address_line1')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('currency')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('subtotal')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discount_total')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tax_total')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_total')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_courier')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('shipping_service')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('shipping_cost')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('IDR')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('shipping_etd')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('weight_total_gram')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'awaiting_payment' => 'warning',
                        'paid' => 'success',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'refunded' => 'warning',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->badge(),
                TextColumn::make('placed_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('cancelled_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('source')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('cek_status_pembayaran')
                    ->label('Cek Pembayaran')
                    ->icon('heroicon-c-credit-card')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Cek Status Pembayaran')
                    ->modalDescription('Mengecek status pembayaran dari Midtrans API dan update jika berbeda')
                    ->modalSubmitActionLabel('Cek & Update')
                    ->tooltip('Cek status pembayaran melalui Midtrans API')
                    ->action(function (Model $record) {
                        try {
                            $midtrans = app(MidtransService::class);
                            $payment = $record->payments()->latest()->first();

                            if (! $payment) {
                                Notification::make()
                                    ->danger()
                                    ->title('Payment tidak ditemukan')
                                    ->body("Tidak ada payment record untuk order {$record->order_number}")
                                    ->send();

                                return;
                            }

                            $currentStatus = $payment->transaction_status;

                            // Check status from Midtrans API
                            $status = $midtrans->checkTransactionStatus($record->order_number);

                            // Check if status different
                            if ($status['transaction_status'] === $currentStatus) {
                                Notification::make()
                                    ->info()
                                    ->title('Status Tidak Berubah')
                                    ->body("Status pembayaran masih **{$currentStatus}**")
                                    ->send();

                                return;
                            }

                            // Update payment status
                            $notificationData = [
                                'order_id' => $status['order_id'],
                                'transaction_status' => $status['transaction_status'],
                                'fraud_status' => $status['fraud_status'] ?? null,
                                'payment_type' => $status['payment_type'],
                                'transaction_id' => $status['order_id'],
                                'transaction_time' => $status['transaction_time'],
                                'gross_amount' => $status['gross_amount'],
                                'status_code' => '200',
                                'signature_key' => hash('sha512', $status['order_id'].'200'.$status['gross_amount'].config('services.midtrans.server_key')),
                            ];

                            $result = $midtrans->handleNotification($notificationData);

                            Notification::make()
                                ->success()
                                ->title('Status Berhasil Diupdate!')
                                ->body("Payment status: **{$currentStatus}** → **{$result['transaction_status']}**\nOrder status: **{$result['order_status']}**")
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Gagal Cek Status')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
