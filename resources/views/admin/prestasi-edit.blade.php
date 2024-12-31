<x-base>
<div class="content-wrapper">  
        <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Edit Prestasi</h4>
                    <p class="card-description"> Edit Data Prestasi  </p>
                    @if($errors->any())
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                      @endif
                    <form class="forms-sample" enctype="multipart/form-data" action="{{ route('prestasi.update', $prestasi->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                      <div class="form-group">
                        <label for="judul">Judul Prestasi</label>
                        <input value="{{ old('judul', $prestasi->judul ?? '') }}" type="text" name="judul" class="form-control" id="judul" placeholder="Nama">
                      </div>
                      <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <input value="{{ old('deskripsi', $prestasi->deskripsi ?? '') }}" type="text" name="deskripsi" class="form-control" id="deskripsi" placeholder="deskripsi">
                      </div>
                      <div class="form-group">
                          <label>Gambar</label>
                          <input type="file" name="gambar" class="file-upload-default" style="display: none;">
                          <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                              <span class="input-group-append">
                                  <button class="file-upload-browse btn btn-gradient-primary" type="button" onclick="document.querySelector('.file-upload-default').click()">Upload</button>
                              </span>
                          </div>
                          @if($prestasi->gambar)
                              <img src="{{ asset($prestasi->gambar) }}" alt="Foto" style="max-width: 150px; max-height: 150px;">
                          @endif
                      </div>

                      

                      <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                      <a href="{{ route('prestasi') }}" class="btn btn-secondary">Kembali</a>

                    </form>
                  </div>
                </div>
              </div>
              <script>
                          document.querySelector('.file-upload-default').addEventListener('change', function() {
                              let fileName = this.files[0]?.name || '';
                              document.querySelector('.file-upload-info').value = fileName;
                          });
                      </script>
</x-base>