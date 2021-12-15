@extends('layouts.backend')

@section('content')

<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Πελάτες</h1>
        </div>
    </div>
</div>
<!-- END Hero -->
<!-- Page Content -->
<div class="content">
    <!-- Your Block -->
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Πελάτες Λίστα</h3>

        </div>
        <div class="block-content">

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">No</th>
                            <th class="d-none d-sm-table-cell" style="width: 30%;">Email</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Membership</th>
                            <th class="d-none d-sm-table-cell" style="width: 20%;">Purchase_date</th>
                            <th class="d-none d-sm-table-cell" style="width: 20%;">Available</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">δράση</th>
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
                            <button type="button" class="btn btn-rounded btn-hero-success" data-toggle="click-ripple"><i class="fa fa-check"></i> Available</button>
                            @else
                            <button type="button" class="btn btn-rounded btn-hero-warning" data-toggle="click-ripple"><i class="fa fa-times"></i> Expired</button>
                            @endif
                            </td>
                            <td class="text-center">
                                <form id="delete-{{$customer->id}}" action="{{ route('customers.destroy',$customer->id) }}" method="POST">
                                    <div class="btn-group">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-block-normal-{{$customer->id}}" title="Delete">
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
                                                        <p>Είστε βέβαιοι ότι θα διαγράψετε αυτές τις πληροφορίες;?</p>
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
    });
</script>
@endsection