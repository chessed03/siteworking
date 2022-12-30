<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Site;
use App\Libraries\ResolveStatus;

class Sites extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners       = ['destroy'];
    public $paginateNumber     = 5;
    public $orderBy            = 1;
    public $updateMode         = false;
    public $selected_id, $keyWord, $siteName, $siteUrl;

    public function render()
    {
        $siteStatus = new ResolveStatus;

        $keyWord        = '%'.$this->keyWord .'%';

        $paginateNumber = $this->paginateNumber;

        $orderBy        = intval($this->orderBy);

        $sites          = Site::getDataForSitesView( $keyWord, $paginateNumber, $orderBy );

        if ( $paginateNumber > count($sites) ) {

            $this->resetPage();

        }

        return view('livewire.sites.view', [
            'sites'      => $sites,
            'siteStatus' => $siteStatus
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
		$this->siteName = null;
		$this->siteUrl  = null;
    }

    public function store()
    {
        $this->validate([
		    'siteName' => 'required',
		    'siteUrl'  => 'required',
        ]);

        $validateNewSiteNoRepeat = Site::validateNewSiteNoRepeat( null, $this->siteName );

        if ( $validateNewSiteNoRepeat ) {

            Site::create([
                'siteName'      => $this->siteName,
                'siteUrl'       => $this->siteUrl,
                'siteCreatedBy' => 'user-root'
            ]);

            $messageAlert = $this->messageAlert('Éxito!', 'Sitio creado.','success');

        } else {

            $messageAlert = $this->messageAlert('Error!', 'Ya existe un sitio con el nombre ingresado.','error');

        }

        $this->resetInput();
        $this->emit('closeCreateModal');
        session()->flash('message', $messageAlert);
        $this->hydrate();
    }

    public function edit($id)
    {
        $record            = Site::findOrFail($id);

        $this->selected_id = $id;

		$this->siteName    = $record->siteName;

		$this->siteUrl     = $record->siteUrl;

        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'siteName' => 'required',
            'siteUrl'  => 'required',
        ]);

        if ($this->selected_id) {

            $validateNewSiteNoRepeat = Site::validateNewSiteNoRepeat( $this->selected_id, $this->siteName );

            if ( $validateNewSiteNoRepeat ) {

                $record = Site::find($this->selected_id);

                $record->update([
                    'siteName' => $this->siteName,
                    'siteUrl' => $this->siteUrl
                ]);

                $messageAlert = $this->messageAlert('Éxito!', 'Sitio actualizado.','success');

            } else {

                $messageAlert = $this->messageAlert('Error!', 'Ya existe un sitio con el nombre ingresado.','error');

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
            $record = Site::where('idSite', $id)->first();
            $record->siteStatus = 0;
            $record->update();

            $messageAlert = $this->messageAlert('Éxito!', 'Sitio eliminado.','success');

            session()->flash('message', $messageAlert);
        }
    }
}
