<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('URL-friendly version of the title'),

                        Select::make('template')
                            ->required()
                            ->options([
                                'default' => 'Default',
                                'about' => 'About Page',
                                'faq' => 'FAQ Page',
                                'contact' => 'Contact Page',
                            ])
                            ->default('default'),
                    ])->columns(2),

                Section::make('Content')
                    ->schema([
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'link',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                            ]),
                    ]),

                Section::make('Sections (JSON)')
                    ->schema([
                        KeyValue::make('sections')
                            ->addButtonLabel('Add Section')
                            ->keyLabel('Section Key')
                            ->valueLabel('Section Data')
                            ->helperText('Advanced: Define sections in JSON format for custom templates'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('SEO & Meta')
                    ->schema([
                        KeyValue::make('meta')
                            ->addButtonLabel('Add Meta')
                            ->keyLabel('Meta Key')
                            ->valueLabel('Meta Value')
                            ->helperText('Add meta description, keywords, etc.'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Settings')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only active pages are visible on the website'),

                        Toggle::make('show_in_footer')
                            ->label('Show in Footer')
                            ->default(false)
                            ->live()
                            ->helperText('Display this page link in the footer'),

                        TextInput::make('footer_order')
                            ->label('Footer Order')
                            ->numeric()
                            ->minValue(1)
                            ->visible(fn ($get) => $get('show_in_footer'))
                            ->helperText('Order in footer menu (lower numbers appear first)'),
                    ])->columns(3),
            ]);
    }
}
