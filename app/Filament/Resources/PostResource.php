<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Create Post')
                ->description('Create a post over here.')
                ->schema([
                    TextInput::make('title')->required(),
                    TextInput::make('slug')->required(),
    
                    Select::make('category_id')
                            ->label('Category')
                            // ->options(Category::all()->pluck('name', 'id'))
                            ->relationship('category', 'name')
                            ->searchable(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault:true),
                ImageColumn::make('thumbnail'),
                TextColumn::make('title')
                ->sortable()
                ->searchable(),
                TextColumn::make('slug')
                ->sortable()
                ->searchable(),
                TextColumn::make('category.name')
                ->sortable()
                ->searchable(),
                ColorColumn::make('color')
                        ->copyable()
                        ->copyMessage('You copied')
                        ->copyMessageDuration(1000),
                TextColumn::make('tags'),
                CheckboxColumn::make('published'),
                TextColumn::make('created_at')
                ->label('Published On')
                ->date()
                ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
