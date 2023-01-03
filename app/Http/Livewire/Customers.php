<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;
use App\Libraries\ResolveStatus;

class Customers extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners       = ['destroy'];
    public $paginateNumber     = 5;
    public $orderBy            = 3;
    public $updateMode         = false;
    public $selected_id, $keyWord, $customerName;

    public function render()
    {

        $customerStatus = new ResolveStatus;

        $keyWord        = '%'.$this->keyWord .'%';

        $paginateNumber = $this->paginateNumber;

        $orderBy        = intval($this->orderBy);

        $customers      = Customer::getDataForCustomersView( $keyWord, $paginateNumber, $orderBy );

        if ( $paginateNumber > count($customers) ) {

            $this->resetPage();

        }

        return view('livewire.customers.view', [
            'customers'      => $customers,
            'customerStatus' => $customerStatus
        ]);
    }

    public function messageAlert( $heading, $text, $icon )
    {

        $this->emit('message', $heading, $text, $icon);

    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
        $this->emit('closeCreateModal');
        $this->emit('closeUpdateModal');
        $this->hydrate();
    }

    private function resetInput()
    {

        $this->customerName = null;

    }

    public function store()
    {
        $this->validate([
		    'customerName' => 'required',
        ]);

        $validateNewCustomerNoRepeat = Customer::validateNewCustomerNoRepeat( null, $this->customerName );

        if ( $validateNewCustomerNoRepeat ) {

            Customer::create([
                'customerName'      => $this->customerName,
                'customerCreatedBy' => 'user-root'
            ]);

            $this->messageAlert('Éxito!', 'Cliente creado.','success');

        } else {

            $this->messageAlert('Error!', 'Ya existe un cliente con el nombre ingresado.','error');

        }

        $this->resetInput();
        $this->emit('closeCreateModal');
        $this->hydrate();
    }

    public function edit($id)
    {
        $record             = Customer::findOrFail($id);

        $this->selected_id  = $id;

        $this->customerName = $record->customerName;

        $this->updateMode   = true;
    }

    public function update()
    {
        $this->validate([
		    'customerName' => 'required',
        ]);

        if ($this->selected_id) {

            $validateNewCustomerNoRepeat = Customer::validateNewCustomerNoRepeat( $this->selected_id, $this->customerName );

            if ( $validateNewCustomerNoRepeat ) {

                $record = Customer::find($this->selected_id);

                $record->update([
                    'customerName' => $this->customerName
                ]);

                $this->messageAlert('Éxito!', 'Cliente actualizado.','success');

            } else {

                $this->messageAlert('Error!', 'Ya existe un cliente con el nombre ingresado.','error');

            }

            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
            $this->hydrate();

        }
    }

    public function destroy($id)
    {
        if ($id) {

            $validateCustomerActiveOnSites = Customer::validateCustomerActiveOnSites( $id );

            $record = Customer::where('id', $id);
            $record->delete();
        }
    }
}
