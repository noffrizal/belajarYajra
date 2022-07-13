@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="card-body">
             <!-- Button trigger modal -->
             <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" id="tambah" data-bs-target="#exampleModal">
                Tambah Data
             </button>
             <button type="button" class="btn btn-danger mb-3 ml-2" id="delete" >
                Hapus Data
             </button>


            <table class="table table-stripped" id="tabel1">
                <thead>
                    <tr>
                        <th>&nbsp;  </th>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No Telp</th>
                        <th>Alamat</th>

                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>


  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Data Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="postForm">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nama</label>
                    <input type="text" id="nama" class="form-control" placeholder="Masukkan Nama">
                    <input type="hidden" name="id" id="id">
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">No Telp</label>
                    <input type="text" id="telp" class="form-control" placeholder="No HP">
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">alamat</label>
                    <textarea name="alamat" id="alamat" cols="18" rows="8" class="form-control"></textarea>

                </div>
            </form>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="tutup">Close</button>
          <button type="button" class="btn btn-primary" id="simpan">Simpan</button>
        </div>
      </div>
    </div>
  </div>



    {{-- <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div> --}}
</div>
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>


<script>
    $(document).ready(function () {
        isi()
    })

    function isi() {
        $('#tabel1').DataTable({
            serverSide : true,
            responsive : true,
            ajax : {
                url : "{{ route('data') }}",

            },
            columns :
            [
                {data : "cek", name : "cek"},
                {
                    "data" : null, "sortable" : false,
                    render : function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                },
                {data : "name", name : "name"},
                {data : "telp", name : "telp"},
                {data : "alamat", name : "alamat"},
                {data : "aksi", name : "aksi"}
            ]
        });

    }
</script>

<script>
    $('#simpan').on('click',function () {
        if ($(this).text() === 'Simpan Edit') {
            edits();
        } else {
            tambah();
        }

    })

    function tambah() {
        $.ajax({
                url : "{{ route('data.store') }}",
                type : "POST",
                data : {
                    nama : $("#nama").val(),
                    telp : $("#telp").val(),
                    alamat : $("#alamat").val(),
                    "_token" : "{{ csrf_token() }}"
                },
                success :  function(res){
                    console.log(res);
                    alert(res.text)
                    $("#tutup").click()
                    $("#tabel1").DataTable().ajax.reload()
                    $('#postForm').trigger("reset")

                }, error : function (xhr) {
                    alert(xhr.responseJson.text);
                }
            })
    }


    $(document).on('click','.edit',function () {
        let id = $(this).attr('id')
        $('#tambah').click()
        $('#simpan').text('Simpan Edit')

        $.ajax({
            url : "{{ route('data.edits') }}",
            type : "post",
            data : {
                id : id,
                _token : "{{ csrf_token() }}"
            },
            success: function(res){
                console.log(res);
                $("#id").val(res.data.id)
                $("#nama").val(res.data.name)
                $("#telp").val(res.data.telp)
                $("#alamat").val(res.data.alamat)
            }
        })
    })

    function edits() {
        $.ajax({
                url : "{{ route('data.updates') }}",
                type : "POST",
                data : {
                    id : $("#id").val(),
                    nama : $("#nama").val(),
                    telp : $("#telp").val(),
                    alamat : $("#alamat").val(),
                    "_token" : "{{ csrf_token() }}"
                },
                success :  function(res){
                    alert(res.text)
                    $("#tutup").click()
                    $("#tabel1").DataTable().ajax.reload()
                    $('#postForm').trigger("reset")
                    $('#simpan').text('Simpan')

                }, error : function (xhr) {
                    alert(xhr.responseJson.text);
                }
            })
    }

    $(document).on('click','.hapus',function () {
       var tanya= confirm('Anda Yakin??');
        if (tanya) {
            let id = $(this).attr('id')

            $.ajax({
                url : "{{ route('data.hapus') }}",
                type : "post",
                data : {
                    id : id,
                    multi : null,
                    _token : "{{ csrf_token() }}"
                },
                success: function(params){
                    alert(params.text)
                    $("#tabel1").DataTable().ajax.reload()
                }
            })
        }

    })

    let dataCek = []
    $(document).on('change','.ceks', function () {
        let id = $(this).attr('id')
        if ($(this).is(':checked')) {
            dataCek.push(id);
            console.log(dataCek);
        } else {
            let index = dataCek.indexOf(id)
            // console.log('uncek');
            if (index > -1) {
                dataCek.splice(index,1)
            }
            console.log(dataCek)
        }
    })


    $(document).on('click', '#delete', function () {
        var tanya= confirm('Anda Yakin??');
        if (tanya) {
            let id = $(this).attr('id')

            $.ajax({
                url : "{{ route('data.hapus') }}",
                type : "post",
                data : {
                    data : dataCek,
                    multi : 1,
                    _token : "{{ csrf_token() }}"
                },
                success: function(params){
                    alert(params.text)
                    $("#tabel1").DataTable().ajax.reload()
                }
            })
        }
    })
</script>
@endpush
@endsection
