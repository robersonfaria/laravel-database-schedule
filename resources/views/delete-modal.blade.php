<div class="modal fade" id="delete-modal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="delete-modal"
     aria-hidden="true"
     data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('schedule::schedule.messages.delete_cronjob') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" x-text="message"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('schedule::schedule.buttons.cancel') }}</button>
                <form id="delete-modal-form" :action="route"
                      method="POST" class="d-inline">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">{{ trans('schedule::schedule.buttons.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
