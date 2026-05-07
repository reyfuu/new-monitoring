<?php

namespace App\Filament\Resources\LaporanMingguans\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Riwayat Komentar';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('komentar')
                    ->label('Komentar')
                    ->required()
                    ->rows(3),
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->default(now())
                    ->required(),
                TextInput::make('npm')
                    ->label('NPM')
                    ->disabled()
                    ->placeholder('Otomatis terisi'),
                TextInput::make('dosen')
                    ->label('Dosen')
                    ->disabled()
                    ->placeholder('Otomatis terisi'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('komentar')
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-calendar')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('komentar')
                    ->label('Isi Komentar')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('npm')
                    ->label('NPM')
                    ->icon('heroicon-m-hashtag')
                    ->placeholder('-'),
                TextColumn::make('dosen')
                    ->label('Dosen')
                    ->icon('heroicon-m-user-circle')
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Komentar')
                    ->mutateFormDataUsing(function (array $data): array {
                        $laporan = $this->getOwnerRecord();
                        $data['npm'] = $laporan->mahasiswa?->npm;
                        $data['dosen'] = $laporan->dosen?->name;
                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
