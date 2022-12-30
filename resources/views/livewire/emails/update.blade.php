<!-- Modal -->
<div wire:ignore.self class="modal fade bs-update-data-modal" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="updateModalLabel">Editar correo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true"><i class="fe-x-circle"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <p class="sub-header">
                    *Campos requeridos.
                    <br>
                    Nota: Si desea agregar más de un correo, debe estar sepado por <strong>";"</strong> (punto y coma) <strong>sin espacios</strong>.
                </p>
                <form>
                    <input type="hidden" wire:model="selected_id">

                    <div class="form-group">
                        <label for="emailUser">Usuario*</label>
                        <input wire:model="emailUser" id="emailUser" type="text" class="form-control" placeholder="Usuario">@error('emailUser') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="emailAddress">Correo*</label>
                        <input wire:model="emailAddress" id="emailAddress" type="text" class="form-control" placeholder="Correo">@error('emailAddress') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-danger btn-rounded waves-effect close-btn" data-dismiss="modal"><i class="bx bx-fw bxs-x-circle"></i> Cerrar</button>
                <button type="button" wire:click.prevent="update()" class="btn btn-success btn-rounded waves-effect close-modal"><i class="bx bx-fw bxs-check-circle"></i> Guardar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

