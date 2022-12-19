@extends('layout')

@section('content')
           
    <form id="forms">
        @csrf
        <div class="card gedf-card col-md-6 mx-auto mt-5">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a 
                        class="nav-link active" 
                        aria-controls="posts" 
                        aria-selected="true">
                            Freedom Wall
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="posts" role="tabpanel" aria-labelledby="posts-tab">
                        <input type="hidden" id="update_id">
                        <div class="form-group">
                            <input 
                                class="form-control col-md-6 mb-2" 
                                type="name"
                                id="username_form" 
                                name="username" 
                                placeholder="Username"
                                value="{{ old('username') }}">

                            <textarea 
                                class="form-control"
                                name="body"
                                id="body_form" 
                                rows="3" 
                                placeholder="What are you thinking?">{{ old('body') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="btn-toolbar justify-content-between">
                    <div class="btn-group mt-2 pull-right col-lg-12">
                        <button 
                            type="submit" 
                            id="saveBtn" 
                            name="post" 
                            class="btn btn-outline-dark mt-3">POST</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Error Message modal -->
    <div class="modal" id="errorMessage">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">Error Message</h4>
            </div>
            <div class="modal-body">
                <h5>Username and Content is required!</h5>
            </div>
            <div class="modal-footer">
                <button 
                    type="button" 
                    class="btn btn-outline-dark" 
                    data-bs-dismiss="modal">
                    <i class="bi bi-x-square"></i>Close</button>
            </div>
          </div>
        </div>
    </div>
    <!-- End Error Message modal -->

    <!-- Start Edit Modal -->
    <div class="modal" id="editcontent">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Content</h4>
                </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <input type="hidden"  id="update_id">
                        <div class="form-group ">
                            <input
                                type="text"
                                id="eUnameForm"
                                class="form-control" 
                                placeholder="Username"
                                style="width: 200px; margin-bottom: 10px">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="eBodyForm" rows="5" placeholder="What are you thinking?"></textarea>
                        </div>
                    </div>
                    <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn_update btn btn-outline-dark"><i class="bi bi-pencil"></i> Update</button>
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"><i class="bi bi-x-square"></i> Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Edit Modal -->

    <!-- Content -->
    <div id="content-body"></div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){

            //Creating a data
            $('#saveBtn').click(function(e){
                e.preventDefault(); //Preventing to reload the page
                var username = $('#username_form').val();
                var body = $('#body_form').val();
                
                //Token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "/store",
                    type:"POST",
                    data:{
                        username:username,
                        body:body
                    },
                    success:function(response){
                        $('#username_form').val(""); 
                        $('#body_form').val("");    
                        fetchData(); 
                    },
                    error: function(response) {
                        $('#errorMessage').modal('show');
                    }
                });
            });

            fetchData(); 
            function fetchData() //fetching all data to the database
            {
                $.ajax({
                    type: "GET",
                    url: "/load_data",
                    dataType: false,
                    success: function (response) {
                        html = '';
                        $.each(response.Fwall, function (key, data) { //looping all the data 
                            
                            //html string for each data
                            html += 
                            `<div class="card gedf-card col-md-6 mx-auto mb-2">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="ml-2">
                                                <div class="h5 m-0" id="username_data">${data.username}</div>
                                            </div>
                                        </div>
                                        <div class="dropdown justify-content-md-end">
                                            <button 
                                                class="btn btn-link-dark dropdown-toggle" 
                                                type="button" 
                                                id="gedf-drop1" 
                                                data-toggle="dropdown" 
                                                aria-haspopup="true" 
                                                aria-expanded="false"></button>

                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                                                <div class="h6 dropdown-header" style="text-align: center;">Configuration</div>
                                                <button 
                                                    type="submit"
                                                    class="dropdown-item"
                                                    style="text-align: center;"
                                                    value="${data.id}"
                                                    onclick="$.edit($(this).val())">Edit</button>
                                                <button
                                                    type="submit"
                                                    class="dropdown-item"
                                                    style="text-align: center;"
                                                    value="${data.id}"
                                                    onclick="$.delete($(this).val())">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="text-muted h7 mb-2">
                                        <i class="fa fa-clock-o"></i>
                                        ${   
                                            data.created_at === data.updated_at 
                                            ? 'Posted: ' +moment(data.created_at).format('LLL')
                                            : 'Edited: ' +moment(data.updated_at).format('LLL')
                                        }
                                    </div>
                                    <p class="card-text" id="body_data">${data.body}</p>
                                </div>
                            </div>`
                        });
                        $('#content-body').html(html); 
                    }
                });
            }

            //retrieving data for editing using modal
            $.extend({
                edit : function(id){
                    var edit_id = id;
                    $('#update_id').val(edit_id);
                    $('#editcontent').modal('show');
                        
                    $.ajax({
                        type: "GET",
                        url: "edit/"+edit_id,
                        success: function (response) { 
                            $('#eUnameForm').val(response.Fwall.username);
                            $('#eBodyForm').val(response.Fwall.body);
                        }
                    });
                },
            })

            //Updating the data using modal
            $('.btn_update').click(function (e) {
                e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var update_id = $('#update_id').val();
                var data = {
                    
                    'username' : $('#eUnameForm').val(),
                    'body' : $('#eBodyForm').val()
                }
        
                $.ajax({
                    type: "POST",
                    url: "update/"+update_id,
                    data: data,
                    dataType: "json",
                    success: function (response) {
                        fetchData();
                    }
                });
                $('#editcontent').modal('hide');
            });

            //Deleting the data
            $.extend({
                delete : function(id){
                    var delID = id;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: 'delete/'+delID,
                        data: {
                            'id':id
                        },
                        success: function(response){
                            fetchData();
                        },
                        error: function(response) {
                            alert("Failed");
                        }
                    });
                },
            });
        });
    </script>
@endsection

