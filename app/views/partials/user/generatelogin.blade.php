      <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Generated Link</h4>
              </div>
              <div class="modal-body">
                <p>To logged as {{ $user->username }}, please copy the generated link below and paste in new browser session*</p>
                <p><input type="text" class="form-control" value="{{ URL::to('linklogin/' . $user->admin_session_token) }}"> </p>

                <div class="alert alert-danger">
                    <strong>*</strong> Use different browser or private / igcognito mode to prevent logout from current session. <br/>Link will expire after use.
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
              </div>