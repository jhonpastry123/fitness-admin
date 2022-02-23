@extends('layouts.backend')

@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Επισκόπηση Αθλήματος</h1>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Your Block -->
    <div class="block block-rounded block-bordered">
        <div class="block-header block-header-default">
            <h3 class="block-title">Επισκόπηση Αθλήματος</h3>
            <div class="block-options">
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('sports.index') }}"> Πίσω</a>
                </div>
            </div>
        </div>
        <div class="block-content">
            <div class="row justify-content-center">
                <div class="col-xs-8 col-sm-8 col-md-8">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2">Όνομα Αθλήματος:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" value="{{ $sport->name }}" placeholder="Enter Sport Name..." readonly>
                        </div>
                    </div>
                </div>

                <div class="col-xs-8 col-sm-8 col-md-8">
                    <div class="form-group row">
                        <label for="coefficient" class="col-sm-2">Συντελεστής:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="coefficient" name="coefficient" value="{{ $sport->coefficient }}" readonly placeholder="Enter Sport Coefficient...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Your Block -->
</div>
<!-- END Page Content -->
@endsection