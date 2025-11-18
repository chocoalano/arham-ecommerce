<?php

namespace App\Livewire;

use App\Repositories\Contracts\CheckoutRepositoryInterface;
use Livewire\Component;

class ShippingCalculator extends Component
{
    public $recipientName = '';

    public $email = '';

    public $phone = '';

    public $addressLine1 = '';

    public $addressLine2 = '';

    public $provinceId = '';

    public $cityId = '';

    public $postalCode = '';

    public $provinces = [];

    public $cities = [];

    public $quotes = [];

    public $selectedShipping = null;

    public $isCalculating = false;

    protected $rules = [
        'recipientName' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'addressLine1' => 'required|string|max:255',
        'provinceId' => 'required|integer',
        'cityId' => 'required|integer',
        'postalCode' => 'required|string|max:10',
    ];

    protected $messages = [
        'recipientName.required' => 'Recipient Name harus diisi',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'phone.required' => 'Phone harus diisi',
        'addressLine1.required' => 'Address harus diisi',
        'provinceId.required' => 'Province harus dipilih',
        'cityId.required' => 'City harus dipilih',
        'postalCode.required' => 'Postal Code harus diisi',
    ];

    public function mount($address = [])
    {
        // Load data dari address jika ada
        $this->recipientName = $address['recipient_name'] ?? '';
        $this->email = $address['email'] ?? '';
        $this->phone = $address['phone'] ?? '';
        $this->addressLine1 = $address['address_line1'] ?? '';
        $this->addressLine2 = $address['address_line2'] ?? '';
        $this->provinceId = $address['province_id'] ?? '';
        $this->cityId = $address['city_id'] ?? '';
        $this->postalCode = $address['postal_code'] ?? '';

        $this->loadProvinces();

        if ($this->provinceId) {
            $this->loadCities();
        }
    }

    public function loadProvinces()
    {
        try {
            $service = app(\App\Services\RajaOngkirService::class);
            $result = $service->getProvinces();

            if ($result['success']) {
                $this->provinces = $result['data'];
            }
        } catch (\Exception $e) {
            $this->provinces = [];
        }
    }

    public function updatedProvinceId()
    {
        $this->cityId = '';
        $this->cities = [];
        $this->quotes = [];
        $this->selectedShipping = null;

        if ($this->provinceId) {
            $this->loadCities();
        }
    }

    public function loadCities()
    {
        try {
            $service = app(\App\Services\RajaOngkirService::class);
            $result = $service->getCities($this->provinceId);

            if ($result['success']) {
                $this->cities = $result['data'];
            }
        } catch (\Exception $e) {
            $this->cities = [];
        }
    }

    public function calculateShipping()
    {
        $this->validate();

        $this->isCalculating = true;
        $this->quotes = [];
        $this->selectedShipping = null;

        try {
            $repository = app(CheckoutRepositoryInterface::class);

            $addressData = [
                'recipient_name' => $this->recipientName,
                'email' => $this->email,
                'phone' => $this->phone,
                'address_line1' => $this->addressLine1,
                'address_line2' => $this->addressLine2,
                'province_id' => $this->provinceId,
                'city_id' => $this->cityId,
                'postal_code' => $this->postalCode,
            ];

            $this->quotes = $repository->getShippingQuotes($addressData);

            if (! empty($this->quotes)) {
                // Auto-select cheapest option
                $this->selectedShipping = $this->quotes[0]['code'];

                $this->dispatch('shipping-calculated', [
                    'quotes' => $this->quotes,
                    'selected' => $this->selectedShipping,
                ]);

                session()->flash('success', count($this->quotes).' shipping options loaded!');
            } else {
                session()->flash('warning', 'No shipping options available for this location');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to calculate shipping: '.$e->getMessage());
        } finally {
            $this->isCalculating = false;
        }
    }

    public function updatedSelectedShipping($value)
    {
        $selectedQuote = collect($this->quotes)->firstWhere('code', $value);

        if ($selectedQuote) {
            $this->dispatch('shipping-selected', [
                'code' => $value,
                'cost' => $selectedQuote['cost'],
                'label' => $selectedQuote['label'],
            ]);
        }
    }

    public function render()
    {
        return view('livewire.shipping-calculator');
    }
}
