<form wire:submit="save">
                        <div class="mb-3">
                          <label class="form-label" for="card-name">Name</label>
                          <input class="form-control" type="text" id="card-name" wire:model="name" />
                              <div>
        @error('name') <span class="error">{{ $message }}</span> @enderror
    </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="card-cnic">CNIC</label>
                          <input class="form-control" type="number" id="card-cnic" wire:model="cnic" />
                              <div>
        @error('cnic') <span class="error">{{ $message }}</span> @enderror
    </div>
                        </div>
                        <div class="mb-3">
                          <label class="form-label" for="card-email">Email address</label>
                          <input class="form-control" type="email" id="card-email" />

                        </div>
                        <div class="row gx-2">
                          <div class="mb-3 col-sm-6">
                            <label class="form-label" for="card-password">Password</label>
                            <input class="form-control" type="password" autocomplete="on" id="card-password" />
                          </div>
                          <div class="mb-3 col-sm-6">
                            <label class="form-label" for="card-confirm-password">Confirm Password</label>
                            <input class="form-control" type="password" autocomplete="on" id="card-confirm-password" />
                          </div>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="card-register-checkbox" />
                          <label class="form-label" for="card-register-checkbox">I accept the <a href="#!">terms </a>and <a class="white-space-nowrap" href="#!">privacy policy</a></label>
                        </div>
                        <div class="mb-3">
                          <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Register</button>
                        </div>
                      </form>
