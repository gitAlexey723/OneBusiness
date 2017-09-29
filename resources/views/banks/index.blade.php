@extends('layouts.app')
@section('header-scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
    <style>
        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }
        .dataTables_scroll
        {
            overflow-x: auto;
            overflow-y: auto;
        }

        th.dt-center, td.dt-center { text-align: center; }

        .panel-body {
            padding: 15px !important;
        }

        a.disabled {
            pointer-events: none;
            cursor: default;
            color: transparent;
        }
        .modal {
            z-index: 10001 !important;;
        }


        @media (max-width: 960px) {
            .bankCodeRw {
                margin-left: 0 !important;
            }

            .addBank {
                position: relative;
                margin-left: 0 !important;
                right: -45px;
                margin-top: 10px;
            }

            .acctNumRw{
                margin-left: 2px !important;
            }
        }

        @media (min-width: 961px) and (max-width: 1001px) {
            .bankCodeRw {
                margin-left: 0 !important;
            }

            .addBank {
                position: relative;
                margin-left: 0 !important;
                right: -45px;
                margin-top: 10px;
            }

            .acctNumRw {
                margin-left: 2px !important;
            }

            .acctNumRw > label {
                position: relative;
                left: -450px;
            }
        }

        #example_ddl label {
            position: relative;
            top: 8px;
        }

        #example_ddl5 {
            position: relative;
            top: 5px;
        }

        #example_ddl2, #example_ddl3, #example_ddl4 {
            margin-right: 5px !important;
        }



    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
                <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                    <ul id="sidebar_menu" class="sidebar-nav">
                        <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu<span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
                    </ul>
                    <div class="sidebar-nav" id="sidebar">
                        <div id="treeview_json"></div>
                    </div>
                </div>

                <!-- Page content -->
                <div id="page-content-togle-sidebar-sec">
                    @if(Session::has('alert-class'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                    @elseif(Session::has('flash_message'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                    @endif
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Banks</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="#" class="pull-right @if(!\Auth::user()->checkAccessById(27, "A")) disabled @endif"
                                                data-toggle="modal" data-target="#addNewAccount" >Add Bank Account</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Use</th>
                                            <th>Bank Code</th>
                                            <th>Account Number</th>
                                            <th>Date Created</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal add new bank account -->
    <div id="addNewAccount" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">New Bank Account Number</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/bank-accounts') }}" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                    <label class="col-md-3 control-label" for="bankCode">Bank Code:</label>
                                    <div class="col-md-9">
                                        <select name="bankCode" class="form-control input-md" id="">
                                            <option value="">Select Bank:</option>
                                            @foreach($selectBank as $bank)
                                                <option value="{{ $bank->bank_id }}">{{ $bank->bank_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12" style="margin-left: -30px;">
                                    <a href="#" class="addBank" data-dismiss="modal" data-toggle="modal" data-target="#addNewBank" style="font-size: 0.8em">Add Bank</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="bankAccountNumber">Account number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankAccountNumber" name="bankAccountNumber" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                <button type="submit" class="btn btn-success pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for adding new bank account -->

    <!-- Modal add new bank -->
    <div id="addNewBank" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add Bank</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/banks') }}" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankName">Bank Name:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankName" name="bankName" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankName">Description:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankDescription" name="bankDescription" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <hr class="wide">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table id="bankTable"  class="table table-striped table-hover responsive">
                                            <thead>
                                            <tr>
                                                <th>Bank Name</th>
                                                <th>Description</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectBank as $bank)
                                                    <tr>
                                                        <td>{{ $bank->bank_code }}</td>
                                                        <td>{{ $bank->description }}</td>
                                                        <td>
                                                            <a href="#" name="edit" class="btn btn-success btn-sm editBank  {{--@if(!\Auth::user()->checkAccessById(23, "E")) disabled @endif--}}">
                                                                <i class="glyphicon glyphicon-ok"></i>
                                                            </a>
                                                            <a href="#" name="delete" class="btn btn-danger btn-sm delete  {{--@if(!\Auth::user()->checkAccessById(23, "E")) disabled @endif--}}">
                                                                <i class="glyphicon glyphicon-remove"></i><span style="display: none;">{{ $bank->bank_id }}</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                <button type="submit" class="btn btn-success pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for adding new bank -->

    <!-- Modal delete bank -->
    <div class="modal fade" id="confirm-delete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <form action="" method="POST" >
                    <div class="modal-body">
                        <p class="text-center">You are about to delete one track, this procedure is irreversible.</p>
                        <p class="text-center">Do you want to proceed deleting <span style="font-weight: bold" class="itemToDelete"></span> -
                            <span class="bankToDelete" style="font-weight: bold"></span> ?</p>
                        <p class="debug-url"></p>
                    </div>

                    <div class="modal-footer">
                        <input style="display: none" class="serviceId" >
                        {!! csrf_field() !!}
                        {{ method_field('Delete') }}
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-ok" class="deleteItem">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end Modal -->

    <!-- Modal edit the bank -->
    <div id="editBankModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Bank</h5>
                </div>
                <form class="form-horizontal" action="" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankDescriptionEdit">Bank Name:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankNameEdit" name="bankNameEdit" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankDescriptionEdit">Description:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="bankDescriptionEdit" name="bankDescriptionEdit" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                {{ method_field('PUT') }}
                                <input type="hidden" class="bankID" name="bankID">
                                <button type="submit" class="btn btn-success pull-right">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for editing the bank -->

    <!-- Modal edit account -->
    <div id="editAccountModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Account</h5>
                </div>
                <form class="form-horizontal" action="" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankAccountCodeEdit">Bank Code:</label>
                            <div class="col-md-7 col-xs-12">
                                <select name="bankAccountCodeEdit" id="bankAccountCodeEdit" class="form-control input-md" id="">
                                    <option value="">Select Bank:</option>
                                    @foreach($selectBank as $bank)
                                        <option value="{{ $bank->bank_id }}">{{ $bank->bank_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="bankAccountNumberEdit">Account Number:</label>
                            <div class="col-md-7 col-xs-12">
                                <input id="bankAccountNumberEdit" name="bankAccountNumberEdit" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                {{ method_field('PUT') }}
                                <input type="hidden" class="accountID" name="accountID">
                                <button type="submit" class="btn btn-success pull-right">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for editing the bank -->

    <!-- checkbox change modal -->

    <div class="modal fade" id="confirmCheckbox" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Default Account
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            Your default account number has been changed!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end checkbox change modal -->

@endsection
@section('footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-dateFormat/1.0/jquery.dateFormat.min.js"></script>
    <script>
        (function($){

            var mainTable = $('#myTable').DataTable({
                initComplete: function () {
                    $('<label for="">Filters:</label>').appendTo("#example_ddl");
                    var corporationID = $('<select class="form-control"><option value="">Select Corporation</option></select>')
                        .appendTo('#example_ddl2');
                    var cntCorp = 0;
                    @foreach($corporations as $key => $val)
                    if(cntCorp == 0){
                        var typeSelected = "selected";
                        cntCorp++;
                    }else{
                        typeSelected = "";
                    }
                    corporationID.append('<option value="{{ $val->corp_id }}" '+ typeSelected +'>{{ $val->corp_name }}</option>');
                            @endforeach
                    var branchStatus = $('<select class="form-control"><option value="">Select Branch Status</option></select>')
                        .appendTo('#example_ddl3');
                    branchStatus.append('<option value="1" selected>Active</option>');
                    branchStatus.append('<option value="0">Inactive</option>');
                    var branches = $('<select class="form-control"><option value="">Select Branch</option></select>')
                        .appendTo('#example_ddl4');
                    var cntBranches = 0;
                    @foreach($selectBank as $key => $val)
                    if(cntBranches == 0){
                        var typeSelected = "selected";
                        cntBranches++;
                    }else{
                        typeSelected = "";
                    }
                    branches.append('<option value="{{ $val->bank_id }}" '+ typeSelected +'>{{ $val->bank_code }}</option>');
                    @endforeach
                    var mainStatus = $('<input class="" type="checkbox"><label value="">Main</label>')
                        .appendTo('#example_ddl5');
                },
               "processing": true,
               "serverSide": true,
                "ajax" : {
                   type: "POST",
                    url: "banks/get-banks-list",
                    data: function (d) {
                        d.dataStatus = $('#example_ddl3 select option:selected').val() == undefined ? 1 : $('#example_ddl3 select option:selected').val();
                        d.corpId = $('#example_ddl2 select option:selected').val() == undefined ? '{{ $corporations[0]->corp_id }}' : $('#example_ddl2 select option:selected').val();
                        d.branch = $('#example_ddl4 select option:selected').val() == undefined ? '{{ $selectBank[0]->bank_id }}' : $('#example_ddl4 select option:selected').val();
                        d.MainStatus = $('#example_ddl5 input').is(":checked");

                    }
                },
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row'<'col-sm-2.pull-left'<'#example_ddl'>><'col-sm-2.pull-left'<'#example_ddl2'>><'col-sm-2.pull-left'<'#example_ddl3'>><'col-sm-2.pull-left'<'#example_ddl4'>><'col-sm-2.pull-left'<'#example_ddl5'>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            var checked = "";
                            if(row.default_acct) checked = "checked";
                            return '<input type="checkbox" '+ checked +' disabled >';
                        },
                        "targets": 0
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.bank_code;
                        },
                        "targets": 1
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.acct_no;
                        },
                        "targets": 2
                    },
                    {
                        "render": function ( data, type, row ) {
                           // console.log($.format.date(row.date_created, 'dd/MM/yyyy'));
                            var dateMin = $.format.date(row.date_created, 'dd/MM/yyyy');
                            return dateMin;
                        },
                        "targets": 3
                    },
                    {
                        "render": function ( data, type, row ) {
                            var checkAccess = '<?php  if(\Auth::user()->checkAccessById(27, "E")) {  echo 1; }else{ echo 0; } ?>';
                            var optionClass = "";
                            if(checkAccess == 0) { optionClass = 'disabled' };
                           return '<a href="#" name="checkDefaultAcct" class="btn btn-success btn-sm checkDefaultAcct" '+optionClass+'>' +
                            '<i class="glyphicon glyphicon-ok"></i><span class="changeAccountID" style="display: none;">'+ row.bank_acct_id +'</span>' +
                            '</a>&nbsp<a href="#" name="editAccount" class="btn btn-primary btn-sm editAccount" '+optionClass+'>' +
                            '<i class="glyphicon glyphicon-pencil"></i><span class="editBankID" style="display: none;">'+row.bank_acct_id+'</span>' +
                            '<span class="codeNumID" style="display: none;">'+row.bank_id+'</span></a>'
                        },
                        "targets": 4
                    },
                    { "orderable": false, "width": "5%", "targets": 0},
                    { "orderable": false, "width": "10%", "targets": 4 },
                    {"className": "dt-center", "targets": 4},
                    {"className": "dt-center", "targets": 0}
                ],
                "columns": [
                    { "data": "default_acct" },
                    { "data": "bank_code" },
                    { "data": "acct_no" },
                    { "data": "date_created" }
                ],
            });

            //init datatables
            $('#bankTable').DataTable({
                "bLengthChange": false,
                "pageLength": 5,
                columns: [
                    null,
                    null,
                    { orderable : false }
                ]
            });


            $('.dataTable').wrap('<div class="dataTables_scroll" />');


            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(1)').text();
                var bankName  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .itemToDelete').text(itemCode);
                $('#confirm-delete .bankToDelete').text(bankName);
                $('#confirm-delete form').attr('action', 'banks/'+id);
                $('#confirm-delete').modal("show");
            });

            $(document).on('click', '.editBank', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(1)').text();
                var itemDescription  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#bankNameEdit').val(itemCode);
                $('#bankDescriptionEdit').val(itemDescription);
                $('.bankID').val(id);
             //   $('#addNewBank').modal("toggle");
                $('#editBankModal form').attr('action', 'banks/'+id);
                $('#editBankModal').modal("toggle");
            });

            $(document).on('click', '.editAccount', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('.editBankID').text();
                var accountNum  = $(this).closest('tr').find('td:nth-child(3)').text();
                var codeNum = $(this).closest('tr').find('.codeNumID').text();
                $('#bankAccountCodeEdit').val(codeNum);
                $('#bankAccountNumberEdit').val(accountNum);
                $('.accountID').val(id);
                $('#editAccountModal form').attr('action', 'bank-accounts/'+id);
                $('#editAccountModal').modal("toggle");
            });

            $(document).on('click', '.checkDefaultAcct', function (e) {
                e.preventDefault();

                var ref = $(this);
                var id  = $(this).closest('td').find('.changeAccountID').text();

                $.ajax({
                    type: 'POST',
                    url: '/bank-accounts/change-default-account',
                    data: { id : id },
                    success: function () {
                        ref.closest('tbody').find('input:checked').each(function () {
                            $(this).prop("checked", false);
                        });

                        ref.closest('tr').find('td:first-child input').prop("checked", true);

                        $.alert({
                            title: 'Default Account',
                            content: '<span style="color: green">Successfully changed!</span>',
                            backgroundDismiss: true,
                        });
                    }
                })

            });


            $('#example_ddl5').on("click", function(e) {
                mainTable.ajax.reload();
            });

            $('#example_ddl2').on('change', function () {
                mainTable.ajax.reload();
            })

            $('#example_ddl3').on('change', function () {
                mainTable.ajax.reload();
            })

            $('#example_ddl4').on('change', function () {
                mainTable.ajax.reload();
            })

            })(jQuery);
    </script>
@endsection