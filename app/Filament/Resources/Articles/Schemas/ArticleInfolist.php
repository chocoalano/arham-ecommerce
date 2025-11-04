<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Models\Article;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ArticleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('author.name')
                    ->label('Author')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('excerpt')
                    ->placeholder('-'),
                TextEntry::make('content')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('reading_time')
                    ->numeric()
                    ->placeholder('-'),
                ImageEntry::make('cover_image')
                    ->placeholder('-'),
                TextEntry::make('meta_title')
                    ->placeholder('-'),
                TextEntry::make('meta_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('scheduled_at')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('is_pinned')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Article $record): bool => $record->trashed()),
            ]);
    }
}
