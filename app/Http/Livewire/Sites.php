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
    public $orderBy            = 3;
    public $updateMode         = false;
    public $selected_id, $keyWord, $siteUrl, $idCustomer;


    public function render()
    {
        $siteStatus     = new ResolveStatus;

        $keyWord        = '%'.$this->keyWord .'%';

        $paginateNumber = $this->paginateNumber;

        $orderBy        = intval($this->orderBy);

        $customers      = Site::getCustomersActives();

        $sites          = Site::getDataForSitesView( $keyWord, $paginateNumber, $orderBy );

        if ( $paginateNumber > count($sites) ) {

            $this->resetPage();

        }

        return view('livewire.sites.view', [
            'sites'      => $sites,
            'customers'  => $customers,
            'siteStatus' => $siteStatus
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
        $this->emit('select2');
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
		$this->idCustomer = null;
		$this->siteUrl  = null;
    }

    public function store()
    {
        $this->validate([
		    'idCustomer' => 'required',
		    'siteUrl'  => 'required',
        ]);

        $validateNewSiteNoRepeat = Site::validateNewSiteNoRepeat( null, $this->siteUrl );

        if ( $validateNewSiteNoRepeat ) {

            Site::create([
                'idCustomer'    => intval($this->idCustomer),
                'siteUrl'       => $this->siteUrl,
                'siteCreatedBy' => 'user-root'
            ]);

            $this->messageAlert('Éxito!', 'Sitio creado.','success');

        } else {

            $this->messageAlert('Error!', 'Ya existe un sitio con el URL ingresado.','error');

        }

        $this->resetInput();
        $this->emit('closeCreateModal');
        $this->hydrate();

    }

    public function edit($id)
    {
        $record            = Site::findOrFail($id);

        $this->selected_id = $id;

		$this->idCustomer  = $record->idCustomer;

		$this->siteUrl     = $record->siteUrl;

        $this->updateMode  = true;
    }

    public function update()
    {
        $this->validate([
            'idCustomer' => 'required',
            'siteUrl'    => 'required',
        ]);

        if ($this->selected_id) {

            $validateNewSiteNoRepeat = Site::validateNewSiteNoRepeat( $this->selected_id, $this->siteUrl );

            if ( $validateNewSiteNoRepeat ) {

                $record = Site::find($this->selected_id);

                $record->update([
                    'idCustomer'   => $this->idCustomer,
                    'siteUrl'    => $this->siteUrl,
                    'siteHealth' => 0
                ]);

                $this->messageAlert('Éxito!', 'Sitio actualizado.','success');

            } else {

                $this->messageAlert('Error!', 'Ya existe un sitio con el nombre ingresado.','error');

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
            $record = Site::where('idSite', $id)->first();
            $record->siteStatus = 0;
            $record->update();

            $this->messageAlert('Éxito!', 'Sitio eliminado.','success');
        }
    }

    public function scrapingSingleVerification($id)
    {
        $this->updateMode = true;

        $site = Site::where('idSite', $id)->first();

        sleep(1);

        $process = __consumeScraperService( $site->siteUrl );

        if ( !empty( $process ) ) {

            Site::sitesProcessFail( [$site->idSite] );

        } else {

            Site::sitesProccessSuccess( [$site->idSite] );

        }

        $this->updateMode = false;

        $this->messageAlert('Éxito!', 'Proceso terminado.','success');
    }

}
