<?php

namespace App\Filament\Customer\Pages;

use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EditProfile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Profil Saya';
    protected static ?string $title = 'Edit Profil';
    protected static ?string $slug = 'edit-profile';
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.customer.pages.edit-profile';

    public ?array $profileData = [];
    public ?array $passwordData = [];
    protected ?bool $activeOrdersCache = null;

    public function mount(): void
    {
        $customer = Auth::guard('customer')->user();

        // TEMPORARILY: Fill with empty data when no user is logged in (agent access)
        if (!$customer) {
            $this->profileForm->fill([
                'name' => '',
                'email' => '',
                'phone' => '',
                'organization' => '',
                'address' => '',
            ]);
            $this->passwordForm->fill();
            return;
        }

        $this->profileForm->fill([
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'organization' => $customer->organization,
            'address' => $customer->address,
        ]);

        $this->passwordForm->fill();
    }

    protected function getForms(): array
    {
        return [
            'profileForm',
            'passwordForm',
        ];
    }

    public function profileForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pribadi')
                    ->description($this->hasActiveOrders()
                        ? '⚠️ Anda memiliki pesanan yang sedang diproses. Hubungi admin untuk mengubah data profil.'
                        : 'Perbarui informasi profil Anda.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-user')
                            ->disabled($this->hasActiveOrders()),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-envelope')
                            ->unique('customers', 'email', ignorable: Auth::guard('customer')->user())
                            ->disabled($this->hasActiveOrders()),

                        Forms\Components\TextInput::make('phone')
                            ->label('No. Telepon')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('08xxxxxxxxxx')
                            ->prefixIcon('heroicon-o-phone')
                            ->disabled($this->hasActiveOrders()),

                        Forms\Components\TextInput::make('organization')
                            ->label('Organisasi / Instansi')
                            ->maxLength(255)
                            ->placeholder('Nama organisasi atau instansi Anda')
                            ->prefixIcon('heroicon-o-building-office')
                            ->disabled($this->hasActiveOrders()),

                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Alamat lengkap Anda')
                            ->disabled($this->hasActiveOrders()),
                    ]),
            ])
            ->statePath('profileData');
    }

    public function passwordForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Ubah Password')
                    ->description('Pastikan password baru minimal 8 karakter.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->revealable()
                            ->required()
                            ->prefixIcon('heroicon-o-key')
                            ->currentPassword('customer'),

                        Forms\Components\TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8)
                            ->prefixIcon('heroicon-o-lock-closed')
                            ->different('current_password'),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->revealable()
                            ->required()
                            ->same('password')
                            ->prefixIcon('heroicon-o-lock-closed'),
                    ]),
            ])
            ->statePath('passwordData');
    }

    public function updateProfile(): void
    {
        if ($this->hasActiveOrders()) {
            Notification::make()
                ->title('Tidak dapat mengubah profil')
                ->body('Anda memiliki pesanan yang sedang diproses. Hubungi admin untuk mengubah data.')
                ->danger()
                ->send();
            return;
        }

        $data = $this->profileForm->getState();

        $customer = Auth::guard('customer')->user();
        $customer->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'organization' => $data['organization'],
            'address' => $data['address'],
        ]);

        Notification::make()
            ->title('Profil berhasil diperbarui')
            ->success()
            ->send();
    }

    public function updatePassword(): void
    {
        $data = $this->passwordForm->getState();

        $customer = Auth::guard('customer')->user();
        $customer->update([
            'password' => Hash::make($data['password']),
        ]);

        $this->passwordForm->fill();

        Notification::make()
            ->title('Password berhasil diubah')
            ->success()
            ->send();
    }

    protected function hasActiveOrders(): bool
    {
        if ($this->activeOrdersCache !== null) {
            return $this->activeOrdersCache;
        }

        $customer = Auth::guard('customer')->user();

        // TEMPORARILY: Return false when no user is logged in (agent access)
        if (!$customer) {
            return $this->activeOrdersCache = false;
        }

        return $this->activeOrdersCache = Order::where('customer_id', $customer->id)
            ->whereIn('status', ['processing', 'ready'])
            ->exists();
    }

    public function getActiveOrdersInfo(): array
    {
        $customer = Auth::guard('customer')->user();

        // TEMPORARILY: Return empty array when no user is logged in (agent access)
        if (!$customer) {
            return [];
        }

        return Order::where('customer_id', $customer->id)
            ->whereIn('status', ['processing', 'ready'])
            ->get()
            ->map(fn($order) => [
                'order_number' => $order->order_number,
                'status' => match ($order->status) {
                    'processing' => 'Diproses',
                    'ready' => 'Siap Ambil',
                    default => $order->status,
                },
            ])
            ->toArray();
    }
}
