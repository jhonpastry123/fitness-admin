@extends('layouts.backend')

@section('content')

<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Διαχείριση Πελατών</h1>
        </div>
    </div>
</div>
<!-- END Hero -->
<!-- Page Content -->
<div class="content">
    <!-- Your Block -->
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Λίστα Πελατών</h3>

        </div>
        <div class="block-content">
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p style="margin-bottom:0;">{{ $message }}</p>
            </div>
            @endif
            @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <p style="margin-bottom:0;">{{ $message }}</p>
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">No</th>
                            <th class="d-none d-sm-table-cell" style="width: 25%;">Email</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Συνδρομή</th>
                            <th class="d-none d-sm-table-cell" style="width: 20%;">Ημερομηνία Αγοράς</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Κατάσταση</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Change Password</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Διαγραφή</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="d-none d-sm-table-cell">{{ $customer->email }}</td>
                            <td class="d-none d-sm-table-cell">{{ $customer->membership }}</td>
                            <td class="d-none d-sm-table-cell">{{ $customer->purchase_time }}</td>
                            <td class="d-none d-sm-table-cell">
                            @if($customer->available)
                            <button type="button" class="btn btn-rounded btn-hero-success" data-toggle="click-ripple"><i class="fa fa-check"></i> Ενεργό</button>
                            @else
                            <button type="button" class="btn btn-rounded btn-hero-warning" data-toggle="click-ripple"><i class="fa fa-times"></i> Σε Λήξη</button>
                            @endif
                            </td>
                            <td class="text-center">
                            <form id="reset-{{$customer->id}}" action="{{ route('customers.reset',$customer->id) }}" method="POST">
                                    <div class="btn-group">
                                        @csrf
                                        <button type="button" class="btn btn-primary" data-toggle="modal"  data-target="#modal-normal-{{$customer->id}}" title="Change Password"><i class="fa fa-edit"></i></button>

                                        <div class="modal" id="modal-normal-{{$customer->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa fa-check"></i>Change Password</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body pb-1">
                                                        <div class="form-group">
                                                            <input type="email" class="form-control" id="email_{{$customer->id}}" name="email" placeholder="Email" value="{{$customer->email}}" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input type="password" class="form-control" id="password_{{$customer->id}}" name="password" placeholder="New Password" require>
                                                                <span class="input-group-text input-group-text-alt show_password">
                                                                    <i class="far fa-eye"></i>
                                                                </span>
                                                                <div class="empty-error-{{$customer->id}} invalid-feedback">Password required!</div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input type="password" class="form-control" id="password_confirmation_{{$customer->id}}" name="password_confirmation" placeholder="New Password Comfirm">
                                                                <span class="input-group-text input-group-text-alt show_password">
                                                                    <i class="far fa-eye"></i>
                                                                </span>
                                                                <div class="match-error-{{$customer->id}} invalid-feedback">Password not matched!</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-primary change" data-id="{{$customer->id}}">Ναί</button>
                                                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Οχι</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </td>
                            <td class="text-center">
                                <form id="delete-{{$customer->id}}" action="{{ route('customers.destroy',$customer->id) }}" method="POST">
                                    <div class="btn-group">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-block-normal-{{$customer->id}}" title="Διαγραφή">
                                            <i class="fa fa-times"></i>
                                        </button>

                                        <div class="modal" id="modal-block-normal-{{$customer->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-normal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa fa-check"></i>Προειδοποίηση</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body pb-1">
                                                        <p>Είστε βέβαιοι ότι θέλετε να διαγράψετε αυτές τις πληροφορίες?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-sm btn-primary confirm" data-id="{{$customer->id}}">Ναί</button>
                                                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Οχι</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-center" style="width: fit-content; margin:auto;">{!! $customers->links() !!}</div>
            </div>
        </div>
    </div>
    <!-- END Your Block -->
</div>
<!-- END Page Content -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.confirm').click(function() {
            $('#delete-' + $(this).data("id")).submit();
        });

        $('.change').click(function() {
            if ($("#password_" + $(this).data("id") ).val() == "") {
                $(".empty-error-" + $(this).data("id") ).css("display", "block");
            } else if ($("#password_"+$(this).data("id")).val() != $("#password_confirmation_"+$(this).data("id")).val()) {
                console.log("!");
                $(".match-error-" + $(this).data("id") ).css("display", "block");
            } else {
                $(".empty-error-" + $(this).data("id") ).css("display", "none");
                $(".match-error-" + $(this).data("id") ).css("display", "none");
                $('#reset-' + $(this).data("id")).submit();
            }

        });

        $(".show_password").click(function() {
            console.log();
            if ($(this).prev().attr("type") == "password") {
                $(this).prev().attr("type", "text")
            } else {
                $(this).prev().attr("type", "password")
            }
        })
    });
</script>
@endsection
