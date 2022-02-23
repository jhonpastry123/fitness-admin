@extends('layouts.backend')
@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Τροφίμων</h1>
        </div>
    </div>
</div>
<!-- END Hero -->
<!-- Page Content -->
<div class="content">
    <!-- Your Block -->
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Λίστα Τροφίμων</h3>
            <div class="block-options">
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('fooditems.create') }}" data-toggle="tooltip" title="Δημιουργία">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="block-content">
            <div class="pull-right d-flex justify-content-end mb-3">
                <form action="{{ route('fooditems.index') }}" method="GET" role="search" style="width:fit-content;">
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-info" type="submit" title="Αναζήτηση τροφίμων">
                                <span class="fas fa-search"></span>
                            </button>
                        </span>
                        <input type="text" class="form-control" name="search" placeholder="Αναζήτηση τροφίμων" id="search" value="{{ $search }}">
                        <a href="{{ route('fooditems.index') }}">
                            <span class="input-group-btn">
                                <button class="btn btn-danger" type="button" title="Ανανέωση Σελίδας">
                                    <span class="fas fa-sync-alt"></span>
                                </button>
                            </span>
                        </a>
                    </div>
                </form>
            </div>
            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p style="margin-bottom:0;">{{ $message }}</p>
            </div>
            @endif
            <div class="table-responsive">
                <table id="foodTable" class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">No</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Κατηγορία</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Όνομα Τροφής</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Υδατάνθρακες (carbs)</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Πρωτεΐνες (proteins)</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Λιπαρά (fat)</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Γραμμάρια (g)</th>
                            <th class="d-none d-sm-table-cell" style="width: 10%;">Kcal</th>
                            <th class="d-none d-md-table-cell text-center" style="width: 15%;">Ενέργειες</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fooditems as $foodItem)
                        <tr>
                            <td class="text-center">{{ ++$i }}</td>
                            <td class="d-none d-sm-table-cell">{{ $foodItem->food_name }}</td>
                            <td class="d-none d-sm-table-cell">{{ $foodItem->categories }}</td>
                            <td class="d-none d-sm-table-cell">{{ $foodItem->carbon }}</td>
                            <td class="d-none d-sm-table-cell">{{ $foodItem->protein }}</td>
                            <td class="d-none d-sm-table-cell">{{ $foodItem->fat }}</td>
                            <td class="d-none d-sm-table-cell">{{ $foodItem->portion_in_grams }}</td>
                            <td class="d-none d-sm-table-cell">{{ $foodItem->kcal }}</td>
                            <td class="text-center">
                                <form id="delete-{{$foodItem->id}}" action="{{ route('fooditems.destroy',$foodItem->id) }}" method="POST">
                                    <div class="btn-group">
                                        <a class="btn btn-success" href="{{ route('fooditems.show',$foodItem->id) }}" data-toggle="tooltip" title="Επισκόποηση">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-primary" href="{{ route('fooditems.edit',$foodItem->id) }}" data-toggle="tooltip" title="Επεξεργασία">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        @csrf
                                        @method('DELETE')
                                        <!-- 
                                        <button type="submit" class="btn btn-danger" data-toggle="modal" data-id="{{$foodItem->id}}" title="Delete">
                                            <i class="fa fa-times"></i>
                                        </button> -->
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-block-normal-{{$foodItem->id}}" title="Διαγραφή">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <!-- Normal Default Modal -->
                                        <div class="modal" id="modal-block-normal-{{$foodItem->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-block-normal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fa fa-check"></i>Προειδοποίηση</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body pb-1">
                                                        <p>Είστε βέβαιοι ότι θέλετε να διαγράψετε το <span class="text-info">τρόφιμο</span>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" id="confirm" class="btn btn-sm btn-primary confirm" data-id="{{$foodItem->id}}">Ναί</button>
                                                        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Οχι</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END Normal Default Modal -->
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($search == '')
                <div class="text-center" style="width: fit-content; margin:auto;">{!! $fooditems->links() !!}</div>
                @endif
            </div>
        </div>
    </div>
    <!-- END Your Block -->
</div>
<!-- END Page Content -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<script>
    $(document).ready(function() {
        $('.confirm').click(function() {
            $('#delete-' + $(this).data("id")).submit();
        });
    });
</script>
@endsection