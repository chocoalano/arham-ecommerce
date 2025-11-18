<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Filament\Resources\Shipments\ShipmentResource;
use App\Services\MidtransService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
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
                    ->visible(fn (Model $record): bool => $record->payments()->where('provider', 'midtrans')->exists())
                    ->action(function (Model $record) {
                        try {
                            $midtrans = app(MidtransService::class);
                            $payment = $record->payments()->where('provider', 'midtrans')->latest()->first();

                            if (! $payment) {
                                Notification::make()
                                    ->warning()
                                    ->title('Payment Tidak Ditemukan')
                                    ->body('Order ini tidak menggunakan Midtrans sebagai payment gateway')
                                    ->send();

                                return;
                            }

                            // Check if payment has midtrans transaction ID
                            if (empty($payment->midtrans_transaction_id) && empty($payment->order_id_ref)) {
                                Notification::make()
                                    ->warning()
                                    ->title('Transaction ID Tidak Ditemukan')
                                    ->body('Payment belum memiliki transaction ID dari Midtrans. Transaksi mungkin belum dibuat atau sudah expire.')
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
                            $errorMessage = $e->getMessage();

                            // Handle specific 404 error from Midtrans
                            if (str_contains($errorMessage, '404') || str_contains($errorMessage, "Transaction doesn't exist")) {
                                Notification::make()
                                    ->warning()
                                    ->title('Transaksi Tidak Ditemukan')
                                    ->body("Transaksi tidak ditemukan di Midtrans. Kemungkinan:\n- Pembayaran belum dilakukan\n- Transaksi sudah expire\n- Order dibuat dengan payment method selain Midtrans")
                                    ->send();
                            } else {
                                Notification::make()
                                    ->danger()
                                    ->title('Gagal Cek Status')
                                    ->body($errorMessage)
                                    ->send();
                            }
                        }
                    }),
                Action::make('set_status')
                    ->label('Set Status')
                    ->icon('heroicon-o-flag')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Update Order Status')
                    ->modalDescription('Change order status. Note: Setting status to "Completed" will update inventory stock.')
                    ->modalSubmitActionLabel('Update Status')
                    ->schema([
                        ToggleButtons::make('status')
                            ->label('New Status')
                            ->options([
                                'pending' => 'Pending',
                                'awaiting_payment' => 'Awaiting Payment',
                                'paid' => 'Paid',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                                'refunded' => 'Refunded',
                                'expired' => 'Expired',
                            ])
                            ->icons([
                                'pending' => Heroicon::OutlinedClock,
                                'awaiting_payment' => Heroicon::OutlinedCreditCard,
                                'paid' => Heroicon::OutlinedBanknotes,
                                'processing' => Heroicon::OutlinedCog,
                                'shipped' => Heroicon::OutlinedTruck,
                                'completed' => Heroicon::OutlinedCheckCircle,
                                'cancelled' => Heroicon::OutlinedXCircle,
                                'refunded' => Heroicon::OutlinedArrowUturnLeft,
                                'expired' => Heroicon::OutlinedStopCircle,
                            ])
                            ->inline()
                            ->required()
                            ->default(fn (Model $record) => $record->status),
                    ])
                    ->action(function (Model $record, array $data) {
                        $oldStatus = $record->status;
                        $newStatus = $data['status'];

                        try {
                            // Update order status
                            $record->update(['status' => $newStatus]);

                            // If status changed to 'completed', sync with inventory
                            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                                $this->syncOrderToInventory($record);

                                Notification::make()
                                    ->success()
                                    ->title('Order Status Updated!')
                                    ->body("Status changed from **{$oldStatus}** to **{$newStatus}**\n\nInventory has been updated successfully.")
                                    ->send();
                            } elseif ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
                                // Check if order has shipment record
                                $hasShipment = \App\Models\Shipment::query()->where('order_id', $record->id)->exists();

                                if ($hasShipment) {
                                    // Update shipment status if exists
                                    \App\Models\Shipment::query()->where('order_id', $record->id)
                                        ->update([
                                            'status' => 'shipped',
                                            'shipped_at' => now(),
                                        ]);

                                    Notification::make()
                                        ->success()
                                        ->title('Order Status Updated!')
                                        ->body("Status changed from **{$oldStatus}** to **{$newStatus}**\n\nShipment status has been updated.")
                                        ->send();
                                } else {
                                    // Revert status change
                                    $record->update(['status' => $oldStatus]);

                                    Notification::make()
                                        ->warning()
                                        ->title('Shipment Required!')
                                        ->body('Please create a shipment record before marking order as shipped.')
                                        ->actions([
                                            Action::make('create_shipment')
                                                ->button()
                                                ->url(ShipmentResource::getUrl('create', ['order' => $record->id]))
                                                ->label('Create Shipment'),
                                        ])
                                        ->send();

                                    return;
                                }
                            }
                            else {
                                Notification::make()
                                    ->success()
                                    ->title('Order Status Updated!')
                                    ->body("Status changed from **{$oldStatus}** to **{$newStatus}**")
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Failed to Update Status')
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

    /**
     * Sync order completion to inventory system
     */
    protected static function syncOrderToInventory($order): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            // Get default warehouse (you can customize this based on your logic)
            $defaultWarehouseId = 1; // Default warehouse ID

            // Load order items with relationships
            $order->load(['items.purchasable']);

            // Check if user exists in inventory database
            $userId = auth()->id();
            $userExists = \Illuminate\Support\Facades\DB::connection('inventory')
                ->table('users')
                ->where('id', $userId)
                ->exists();

            // Create Transaction record in inventory
            $transaction = \App\Models\Inventory\Transaction::create([
                'reference_number' => 'ORD-'.$order->order_number,
                'type' => \App\Models\Inventory\Transaction::TYPE_PENJUALAN,
                'transaction_date' => now(),
                'source_warehouse_id' => $defaultWarehouseId,
                'destination_warehouse_id' => null, // For sales, destination is null
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'customer_full_address' => $order->shipping_address_snapshot['address_line1'] ?? '',
                'item_count' => $order->items->count(),
                'grand_total' => $order->grand_total,
                'status' => \App\Models\Inventory\Transaction::STATUS_POSTED,
                'posted_at' => now(),
                'created_by' => $userExists ? $userId : null,
                'remarks' => 'Auto-generated from order '.$order->order_number,
            ]);

            // Process each order item
            foreach ($order->items as $item) {
                // Find inventory variant by SKU
                $inventoryVariant = \App\Models\Inventory\ProductVariant::where('sku_variant', $item->sku)
                    ->first();

                if (! $inventoryVariant) {
                    \Illuminate\Support\Facades\Log::warning("Inventory variant not found for SKU: {$item->sku}");

                    continue;
                }

                // Create TransactionDetail
                \App\Models\Inventory\TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $inventoryVariant->product_id,
                    'product_variant_id' => $inventoryVariant->id,
                    'warehouse_id' => $defaultWarehouseId,
                    'qty' => $item->quantity,
                    'price' => $item->price,
                    'discount_amount' => 0,
                    'line_total' => $item->subtotal,
                ]);

                // Update WarehouseVariantStock
                $warehouseStock = \App\Models\Inventory\WarehouseVariantStock::where('warehouse_id', $defaultWarehouseId)
                    ->where('product_variant_id', $inventoryVariant->id)
                    ->first();

                if ($warehouseStock) {
                    $oldQty = $warehouseStock->qty;
                    $newQty = max(0, $oldQty - $item->quantity);

                    $warehouseStock->qty = $newQty;
                    $warehouseStock->save();

                    // Create InventoryMovement record
                    \App\Models\Inventory\InventoryMovement::create([
                        'transaction_id' => $transaction->id,
                        'from_warehouse_id' => $defaultWarehouseId,
                        'to_warehouse_id' => null,
                        'product_variant_id' => $inventoryVariant->id,
                        'qty_change' => -$item->quantity, // Negative for stock out
                        'type' => 'out',
                        'occurred_at' => now(),
                        'remarks' => "Sales from order {$order->order_number}",
                        'created_by' => $userExists ? $userId : null,
                    ]);
                } else {
                    \Illuminate\Support\Facades\Log::warning("Warehouse stock not found for variant ID: {$inventoryVariant->id} in warehouse {$defaultWarehouseId}");
                }

                // Update e-commerce product stock
                if ($item->purchasable_type === \App\Models\Product::class) {
                    $product = \App\Models\Product::query()->find($item->purchasable_id);
                    if ($product) {
                        $product->stock = max(0, $product->stock - $item->quantity);
                        $product->save();
                    }
                } elseif ($item->purchasable_type === \App\Models\ProductVariant::class) {
                    $variant = \App\Models\ProductVariant::query()->find($item->purchasable_id);
                    if ($variant && $variant->product) {
                        $variant->product->stock = max(0, $variant->product->stock - $item->quantity);
                        $variant->product->save();
                    }
                }
            }
        });
    }
}
