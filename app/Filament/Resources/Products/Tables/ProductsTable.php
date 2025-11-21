<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images.path')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(50)
                    ->width(50)
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->getStateUsing(function ($record) {
                        return $record->images->pluck('path')->toArray();
                    }),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->searchable(),
                TextColumn::make('weight_gram')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('length_mm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('width_mm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('height_mm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('sale_price')
                    ->numeric()
                    ->money('IDR')
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->boolean(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('meta_title')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('set_active')
                        ->label('Set Active')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                $record->status = 'active';
                                $record->save();

                                // Also activate variants
                                $record->variants()->update(['is_active' => true]);
                                $count++;
                            }

                            Notification::make()
                                ->success()
                                ->title('Products Activated')
                                ->body("{$count} product(s) and their variants have been set to active.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('set_inactive')
                        ->label('Set Inactive')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                $record->status = 'archived';
                                $record->save();

                                // Also deactivate variants
                                $record->variants()->update(['is_active' => false]);
                                $count++;
                            }

                            Notification::make()
                                ->success()
                                ->title('Products Deactivated')
                                ->body("{$count} product(s) and their variants have been set to inactive.")
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make()
                        ->modalHeading('Hapus Produk ke Trash')
                        ->modalDescription(function ($records) {
                            $totalProducts = $records->count();
                            $totalVariants = 0;

                            foreach ($records as $record) {
                                $totalVariants += $record->variants()->count();
                            }

                            return "Anda akan menghapus **{$totalProducts} produk** dan **{$totalVariants} varian** ke trash (soft delete).\n\n".
                                "✅ Data masih bisa dipulihkan kembali\n".
                                "✅ File gambar tetap aman di storage\n".
                                "✅ Relasi data tetap terjaga\n\n".
                                "💡 **Tip:** Gunakan filter 'Only Trashed' untuk melihat produk yang sudah dihapus.";
                        })
                        ->successNotificationTitle('Produk Dipindahkan ke Trash')
                        ->action(function ($records) {
                            $count = $records->count();
                            foreach ($records as $record) {
                                // Soft delete handled by Product model boot method
                                // Will cascade to variants
                                $record->delete();
                            }

                            Notification::make()
                                ->success()
                                ->title('Berhasil Dipindahkan ke Trash')
                                ->body("{$count} produk dan variannya telah dipindahkan ke trash. Data masih bisa dipulihkan.")
                                ->send();
                        }),
                    ForceDeleteBulkAction::make()
                        ->modalHeading('⚠️ PERINGATAN: Hapus Permanen Produk')
                        ->modalDescription(function ($records) {
                            $totalProducts = $records->count();
                            $totalVariants = 0;
                            $totalImages = 0;
                            $totalReviews = 0;
                            $totalWishlist = 0;
                            $totalCart = 0;
                            $totalOrderItems = 0;

                            foreach ($records as $record) {
                                $totalVariants += $record->variants()->withTrashed()->count();
                                $totalImages += $record->images()->count();
                                $totalReviews += $record->reviews()->count();
                                $totalWishlist += $record->wishlistItems()->count();
                                $totalCart += $record->cartItems()->count();
                                $totalOrderItems += $record->orderItems()->count();
                            }

                            return "**TINDAKAN INI AKAN MENGHAPUS PERMANEN:**\n\n".
                                "📦 **{$totalProducts} Produk** yang dipilih\n\n".
                                "**Data yang akan ikut terhapus:**\n".
                                "• {$totalVariants} Varian Produk\n".
                                "• {$totalImages} Gambar Produk\n".
                                "• {$totalReviews} Review/Rating\n".
                                "• {$totalWishlist} Item Wishlist\n".
                                "• {$totalCart} Item Keranjang\n".
                                "• {$totalOrderItems} Item di Order\n".
                                "• Semua relasi kategori\n\n".
                                "⚠️ **PERINGATAN KERAS:** Tindakan ini **TIDAK DAPAT DIBATALKAN**. \n".
                                "Semua data akan hilang **SELAMANYA** dari database!\n\n".
                                'File gambar fisik juga akan **DIHAPUS** dari storage.';
                        })
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalIconColor('danger')
                        ->modalSubmitActionLabel('Ya, Hapus Permanen Selamanya')
                        ->successNotificationTitle('Produk Dihapus Permanen')
                        ->action(function ($records) {
                            $count = $records->count();
                            foreach ($records as $record) {
                                // Force delete handled by Product model boot method
                                // Will cascade to variants and all related data
                                $record->forceDelete();
                            }

                            Notification::make()
                                ->success()
                                ->title('Penghapusan Permanen Selesai')
                                ->body("{$count} produk dan semua data relasinya telah dihapus permanen dari database.")
                                ->send();
                        }),
                    RestoreBulkAction::make()
                        ->modalHeading('Pulihkan Produk dari Trash')
                        ->modalDescription(function ($records) {
                            $totalProducts = $records->count();
                            $totalVariants = 0;

                            foreach ($records as $record) {
                                $totalVariants += $record->variants()->withTrashed()->count();
                            }

                            return "Anda akan memulihkan **{$totalProducts} produk** dan **{$totalVariants} varian** dari trash.\n\n".
                                "✅ Produk akan kembali aktif\n".
                                "✅ Semua varian akan dipulihkan\n".
                                "✅ Relasi data akan kembali normal\n\n".
                                '💡 Produk akan muncul kembali di daftar produk aktif.';
                        })
                        ->successNotificationTitle('Produk Berhasil Dipulihkan')
                        ->action(function ($records) {
                            $count = $records->count();
                            foreach ($records as $record) {
                                // Restore handled by Product model boot method
                                // Will cascade to variants
                                $record->restore();
                            }

                            Notification::make()
                                ->success()
                                ->title('Berhasil Dipulihkan')
                                ->body("{$count} produk dan semua variannya telah berhasil dipulihkan dari trash.")
                                ->send();
                        }),
                ]),
            ]);
    }
}
