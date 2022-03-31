@extends('layouts.app')

@section('content')
    {{-- boostrap --}}
    <link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <!-- Icons Css -->
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ url('assets/css/app.min.css') }}" id="app-stylesheet" rel="stylesheet" type="text/css" />
    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('/assets/libs/dropify/dropify.min.css') }}">
    <script type="text/javascript" src="{{ asset('/assets/libs/dropify/dropify.min.js') }}"></script>
    <style>
        .clickable {
            cursor: pointer;
            font-size: 20px;
        }

        .fa-trash:hover {
            color: red;
        }

        .fa-pencil:hover {
            color: yellow;
        }

        .fa-comment:hover {
            color: cyan;
        }
    </style>
<div class="container">
    <div class="d-inline flex">
        <form action="/filter" method="post">
            @csrf
            <input type="text" class="form-control mb-4 d-inline" style="width:93%;" name="filter"> <button class="btn btn-primary ml-1">Filter</button>
        </form>
    </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-7">
                <div class="d-flex flex-row-reverse">
                    <button type="button" class="btn btn-primary model mb-4" data-toggle="modal" data-target="#exampleModal">
                        Post
                    </button>
                </div>

                @foreach ($tweets as $data)
                    <div class="card">
                        <div class="card-body">
                            <span class="float-right">
                                <span class='clickable'><i class="fa fa-trash deletepost mr-1" id="{{ $data->id }}"
                                        value="{{ $data->gambar }}"></i></span>
                                <span class='clickable'><i class="fa fa-pencil editpost mr-1"
                                        id="{{ $data->id }}"></i></span>
                                <span class='clickable'><i class="fa fa-comment mr-1" id="{{ $data->id }}"></i></span>
                            </span>
                            <h3>{{ $data->text }}</h3>
                            <span>{{ date_format($data->created_at, 'd-M-Y') }}</span>
                        </div>
                        @if ($data->gambar != null)
                            <img src="{{ asset('storage/post/' . $data->gambar) }}" class="p-2"
                                class="card-img-bottom">
                        @endif
                        <div class="card-footer">
                            @foreach ($data->comment as $komentar)
                                <div class="card border">
                                    <div class="card-header">{{ $komentar->user->name }}
                                        @if (Auth::user()->id == $komentar->user->id)
                                            <div class="float-right">
                                                <span class='clickable'><i class="fa fa-trash deletekomen mr-1"
                                                        id="{{ $komentar->id }}" value="{{ $data->gambar }}"></i></span>
                                                <span class='clickable'><i class="fa fa-pencil editkomen mr-1"
                                                        id="{{ $komentar->id }}"></i></span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">{{ $komentar->komentar }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        <div class="col-md-3">
            <div class="card ">
                <div class="card-body">
                    <div class="profile-detail text-center">
                        <img class="card-img-top mb-3" src="{{ asset('storage/profile/'.Auth::user()->profile)}}" alt="Card image cap">
                        <h5 class="card-title">{{Auth::user()->name}}</h5>
                        <p class="card-text">{{Auth::user()->email}}</p>
                    </div>
                    <form action="{{ route('editProfile') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="profile-detail2 text-center d-none" >
                            <div class="form-group">
                                <label for="name">Profile : </label>
                                <input type="file" class="dropify" id="profile" name="profile" data-allowed-file-extensions="png jpg jpeg">
                            </div>
                            <div class="form-group">
                                <label for="name">Nama : </label>
                                <input type="text" class="form-control" id="name" name="name" value="{{Auth::user()->name}}">
                            </div>
                            <div class="form-group">
                                <label for="email">Email : </label>
                                <input type="text" class="form-control" id="email" name="email" value="{{Auth::user()->email}}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary editprofile d-none">Edit</button>
                    </form>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary editprofile2">Edit</button> 
                    </div>
                </div>
              </div>
        </div>
    </div>
</div>
    {{-- Modal Post --}}
    <div class="modal fade postmodal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <form action="{{ route('tweets_submit') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h4>Wanting to post something?</h4>
                        </div>
                        <div class="card-body">
                            <textarea class="post" name="post" id="" cols="60" rows="5" maxlength="250"></textarea>
                            <div class="float-right"><span id="rchars">250</span> Character(s) Remaining</div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h4>Image (optional)</h4>
                                    <input type="file" name="gambar_post" parsley-trigger="change"
                                        class="form-control dropify" data-allowed-file-extensions="png jpg jpeg"
                                        id="gambar_post">
                                </div>

                                <div class="col-md-6">
                                    <h4>File (optional)</h4>
                                    <input type="file" name="file_post" parsley-trigger="change"
                                        class="form-control dropify" id="file_post">
                                </div>
                            </div>
                        </div>
                        <div class="float-right p-2">
                            <button type="submit" class="btn btn-primary float-right">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- modal delete --}}
    <div class="modal delete fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Want to delete this post?</h3>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('tweets_delete') }}">
                        <input type="hidden" id="delete_id" name="delete_id">
                        <input type="hidden" id="gambar_name" name="gambar_name">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- edit Modal --}}
    <div class="modal fade editmodal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <form action="{{ route('tweets_update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h4>Edit</h4>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="edit_id" name="edit_id">
                            <textarea class="post" name="post" id="post_edit" cols="60" rows="5" maxlength="250"></textarea>
                            <div class="float-right"><span id="rchars">250</span> Character(s) Remaining</div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h4>Image (optional)</h4>
                                    <input type="file" name="gambar_post_edit" parsley-trigger="change"
                                        class="form-control dropify" data-allowed-file-extensions="png jpg jpeg"
                                        id="gambar_post_edit">
                                </div>

                                <div class="col-md-6">
                                    <h4>File (optional)</h4>
                                    <input type="file" name="file_post_edit" parsley-trigger="change"
                                        class="form-control dropify" id="file_post_edit">
                                </div>
                            </div>
                        </div>
                        <div class="float-right p-2">
                            <button type="submit" class="btn btn-primary float-right">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- addkomen --}}
    <div class="modal fade addkomen" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <form action="{{ route('komentar') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h4>Komentar</h4>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="tweets_id" name="tweets_id">
                            <textarea class="komentar" name="komentar" id="komentar" cols="60" rows="5" maxlength="250"></textarea>
                            <div class="float-right"><span id="sisa">250</span> Character(s) Remaining</div>
                        </div>
                        <div class="float-right p-2">
                            <button type="submit" class="btn btn-primary float-right">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- komen delete --}}
    <div class="modal komendelete fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title text-center">Want to delete this Comment?</h3>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('komentar_delete') }}">
                        <input type="hidden" id="komentardelete_id" name="komentardelete_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- edit komen --}}
    <div class="modal fade editkomentar" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="card">
                    <form action="{{ route('komentar_update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h4>Edit Komentar</h4>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="komentaredit_id" name="komentaredit_id">
                            <textarea class="komentar" name="komentar" id="komentar_edit" cols="60" rows="5" maxlength="250"></textarea>
                            <div class="float-right"><span id="sisa">250</span> Character(s) Remaining</div>
                        </div>
                        <div class="float-right p-2">
                            <button type="submit" class="btn btn-primary float-right">Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // inisialisasi plugin dropify
        $('.dropify').dropify();
        //max length dari text area
        var maxLength = 250; //max lenghtnya 250
        $('.post').keyup(function() {
            var textlen = maxLength - $(this).val().length; //menghitung sisa char
            $('#rchars').text(textlen); //mengoutputkan sisa char
        });
        //max length komentar
        var maxLength = 250; //max lenghtnya 250
        $('.komentar').keyup(function() {
            var textlen = maxLength - $(this).val().length; //menghitung sisa char
            $('#sisa').text(textlen); //mengoutputkan sisa char
        });
        // open modal post
        $('.model').on('click', function() {
            $(".postmodal").modal('show');
        })
        // open modal delete
        $('.deletepost').on('click', function() {
            var id = $(this).attr("id"); //mengambil data id
            var nama_gambar = $(this).attr("value"); //mengambil data id
            $(".delete").modal('show'); //membuka modal delete
            $("#delete_id").val(id); //mengassign value id di input type hidden
            $("#gambar_name").val(nama_gambar); //mengassign value nama gambar
        })
        // edit modal
        $('.editpost').on('click', function() {
            var id = $(this).attr("id");
            $(".editmodal").modal('show'); //membuka modal delete
            $.ajax({
                url: '/tweets_get/' + id,
                success: function(data) {
                    $("#gambar_value").val(data.gambar);
                    if (data.gambar != null) { //jika ada gambar
                        var lokasi_gambar = "{{ asset('storage/post') }}" + '/' + data.gambar;
                        var fileDropper = $("#gambar_post_edit").dropify({
                            messages: {
                                default: "Seret dan lepas logo di sini atau klik",
                                replace: "Seret dan lepas logo di sini atau klik",
                                remove: "Remove",
                                error: "Terjadi kesalahan"
                            },
                            error: {
                                fileSize: "Ukuran file gambar terlalu besar (Maksimal 1 MB)"
                            },
                        });

                        fileDropper = fileDropper.data('dropify');
                        fileDropper.resetPreview();
                        fileDropper.clearElement();
                        fileDropper.settings['defaultFile'] = lokasi_gambar;
                        fileDropper.destroy();
                        fileDropper.init();
                    }

                    if (data.file != null) { //jika ada file
                        var lokasi_file = "{{ asset('storage/post') }}" + '/' + data.file;
                        var file_edit = $("#file_post_edit").dropify({
                            messages: {
                                default: "Seret dan lepas logo di sini atau klik",
                                replace: "Seret dan lepas logo di sini atau klik",
                                remove: "Remove",
                                error: "Terjadi kesalahan"
                            },
                            error: {
                                fileSize: "Ukuran file gambar terlalu besar (Maksimal 1 MB)"
                            },
                        });

                        file_edit = file_edit.data('dropify');
                        file_edit.resetPreview();
                        file_edit.clearElement();
                        file_edit.settings['defaultFile'] = lokasi_file;
                        file_edit.destroy();
                        file_edit.init();
                    }
                    $('#post_edit').val(data.text);
                    $('#gambar_value').val(data.gambar);
                    $('#edit_id').val(data.id); //assign value id
                    var textlen = maxLength - data.text.length; //menghitung sisa char
                    $('#rchars').text(textlen); //mengoutputkan sisa char
                }
            })
        })
        // addkomentar
        $('.fa-comment').on('click', function() {
            $(".addkomen").modal('show');
            var id = $(this).attr("id"); //mengambil data id
            $('#tweets_id').val(id);
        })
        // delete komen
        $('.deletekomen').on('click', function() {
            $(".komendelete").modal('show');
            var id = $(this).attr("id"); //mengambil data id
            $('#komentardelete_id').val(id);
        })
        // edit komen

        $('.editkomen').on('click', function() {
            $(".editkomentar").modal('show');
            var id = $(this).attr("id"); //mengambil data id
            $('#komentaredit_id').val(id);
            $.ajax({
                url: "/komentar_get/" + id,
                success: function(data) {
                    console.log(data);
                    $("#komentar_edit").val(data.komentar);
                }
            })
        })
        // edit Profile
        $('.editprofile2').on('click',function(){
            $(".profile-detail").empty();
            $(".profile-detail").removeClass('text-center');
            $(".profile-detail2").removeClass('text-center');
            $(".profile-detail").removeClass('d-none');
            $(".profile-detail2").addClass('d-block');
            $('.editprofile').removeClass('d-none');
            $('.editprofile2').addClass('d-none');
        })
    </script>
@endsection
