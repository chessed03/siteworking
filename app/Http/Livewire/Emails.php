<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Email;

class Emails extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    protected $listeners       = ['destroy'];
    public $paginateNumber     = 5;
    public $orderBy            = 1;
    public $updateMode         = false;
    public $selected_id, $keyWord, $emailUser, $emailAddress;

    public function render()
    {
		$keyWord        = '%'.$this->keyWord .'%';

        $paginateNumber = $this->paginateNumber;

        $orderBy        = intval($this->orderBy);

        $emails         = Email::getDataForEmailsView( $keyWord, $paginateNumber, $orderBy );

        if ( $paginateNumber > count($emails) ) {

            $this->resetPage();

        }

        return view('livewire.emails.view', [
            'emails' => $emails
        ]);
    }

    public function messageAlert( $heading, $text, $icon )
    {
        $alertMessage = (object)[
            'heading' => $heading,
            'text'    => $text,
            'icon'    => $icon
        ];

        return json_encode( $alertMessage );
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
        $this->hydrate();
    }

    private function resetInput()
    {
		$this->emailUser = null;
		$this->emailAddress = null;
    }

    public function validateEmails()
    {
        $result = false;

        if ( !empty( $this->emailAddress ) ) {

            $emails = explode(';', implode('',array_filter(explode(' ',$this->emailAddress))));

            foreach ( $emails as $email ) {

                $regex = "/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/";

                if ( !preg_match($regex, $email) ) {

                    $this->addError('emailAddress', 'Error in format emails');

                    $result = true;

                }

            }

        }

        return $result;

    }

    public function store()
    {
        $this->validate([
            'emailUser'    => 'required',
            'emailAddress' => 'required',
        ]);

        $validateEmails = $this->validateEmails();

        if ( $validateEmails ) {

            return true;

        }

        $validateNewEmailNoRepeat = Email::validateNewEmailNoRepeat( null, $this->emailUser );

        if ( $validateNewEmailNoRepeat ) {

            Email::create([
                'emailUser'      => $this->emailUser,
                'emailAddress'   => implode('',array_filter(explode(' ',$this->emailAddress))),
                'emailCreatedBy' => 'user-root'
            ]);

            $messageAlert = $this->messageAlert('Éxito!', 'Email creado.','success');

        } else {

            $messageAlert = $this->messageAlert('Error!', 'Ya existe un usuario con el nombre ingresado.','error');

        }

        $this->resetInput();
        $this->emit('closeCreateModal');
        session()->flash('message', $messageAlert);
        $this->hydrate();
    }

    public function edit($id)
    {
        $record             = Email::findOrFail($id);

        $this->selected_id  = $id;

		$this->emailUser    = $record->emailUser;

		$this->emailAddress = $record->emailAddress;

        $this->updateMode   = true;
    }

    public function update()
    {
        $this->validate([
            'emailUser'    => 'required',
            'emailAddress' => 'required',
        ]);

        $validateEmails = $this->validateEmails();

        if ( $validateEmails ) {

            return true;

        }

        if ($this->selected_id) {

            $validateNewEmailNoRepeat = Email::validateNewEmailNoRepeat( $this->selected_id, $this->emailUser );

			if ( $validateNewEmailNoRepeat ) {

                $record = Email::find($this->selected_id);

                $record->update([
                    'emailUser'    => $this->emailUser,
                    'emailAddress' => implode('',array_filter(explode(' ',$this->emailAddress))),
                ]);

                $messageAlert = $this->messageAlert('Éxito!', 'Email actualizado.','success');

            } else {

                $messageAlert = $this->messageAlert('Error!', 'Ya existe un usuario con el nombre ingresado.','error');

            }

            $this->resetInput();
            $this->emit('closeUpdateModal');
            $this->updateMode = false;
            session()->flash('message', $messageAlert);
            $this->hydrate();
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Email::where('idEmail', $id)->first();
            $record->emailStatus = 0;
            $record->update();

            $messageAlert = $this->messageAlert('Éxito!', 'Email eliminado.','success');

            session()->flash('message', $messageAlert);
        }
    }
}
