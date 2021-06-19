@extends('layouts.backend')

@section('content')
<!-- Hero -->
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Πίνακας Διαχείρισης</h1>
            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">App</li>
                    <li class="breadcrumb-item active" aria-current="page">Πίνακας Διαχείρισης</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <div class="row justify-content-center">
        <div class="col-md-6 col-xl-5">
            <div class="block block-rounded block-bordered">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Καλώς ήλθατε στο πίνακα διαχείρισης του Trinity App</h3>
                </div>
                <div class="block-content">
                    <p>
                        Μπορείς να Προσθέσεις, Διαγράψεις και να κάνεις Ενημέρωση προϊόντων και Συνταγών.
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Page Content -->
@endsection