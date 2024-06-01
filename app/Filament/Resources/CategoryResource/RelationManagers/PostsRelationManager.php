<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('Create Post')
            ->description('Create a post over here.')
            ->schema([
                TextInput::make('title')->required(),
                TextInput::make('slug')->required(),

                ColorPicker::make('color')->hexcolor()->required(),

                MarkdownEditor::make('content')->required()->columnSpanFull(),
            ])->columns(2)->columnSpan(2),
            
            Group::make()->schema([
                Section::make('Image')
                ->collapsed()
                ->schema([
                    FileUpload::make('thumbnail')->disk('public')->directory('thumbnails')->required(),
                ]),
                Section::make('Meta')
                ->schema([
                    TagsInput::make('tags')->required(),
                    Checkbox::make('published'),
                ]),
            ]),
            
        ])->columns([
            'default' => 2,
            'md' => 2,
            'lg' => 3,
            'xl' => 3,
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
