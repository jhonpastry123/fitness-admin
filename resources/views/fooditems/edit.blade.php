@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Επεξεργασία Τροφίμων</h1>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Your Block -->
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Επεξεργασία Τροφίμων</h3>
                <div class="block-options">
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('fooditems.index') }}"> Πίσω</a>
                    </div>
                </div>
            </div>
            <div class="block-content">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('fooditems.update',$fooditem->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row justify-content-center">
                        <div class="col-xs-10 col-sm-10 col-md-10">
                            <div class="form-group row">
                                <label for="food_categories_id" class="col-sm-4">Κατηγορία:</label>
                                <div class="col-sm-8">
                                    <select class="js-select2 form-control" id="food_categories_id" name="food_categories_id[]" style="width: 100%;" data-placeholder="Επέλεξε κατηγορία.." multiple>
                                        <option></option>
                                        @foreach($foodcategories as $foodcategory)
                                            <option value="{{$foodcategory->id}}" <?php if(in_array($foodcategory->id, $fooditem->category_ids)) echo "selected"?>>{{$foodcategory->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4">Όνομα Τροφής:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="food_name" name="food_name" value="{{ $fooditem->food_name }}" placeholder="Πληκτρολόγησε το όνομα...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4">Υδατάνθρακες (carbs):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="carbon" name="carbon" value="{{ $fooditem->carbon }}" placeholder="Πληκτρολόγησε τους υδατάνθρακες...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4">Πρωτεΐνες (proteins):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="protein" name="protein" value="{{ $fooditem->protein }}" placeholder="Πληκτρολόγησε την πρωτεΐνη...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4">Λιπαρά (fat):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="fat" name="fat" value="{{ $fooditem->fat }}" placeholder="Πληκτρολόγησε τα λιπαρά...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4">Γραμμάρια (g):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="portion_in_grams" name="portion_in_grams" value="{{ $fooditem->portion_in_grams }}" placeholder="Πληκτρολόγησε τα γραμμάρια...">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4">Kcal:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="kcal" name="kcal" value="{{ $fooditem->kcal }}" placeholder="Πληκτρολόγησε Kcal..">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4">Μερίδα (grams):</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="serving_size" name="serving_size" value="{{ $fooditem->serving_size }}" placeholder="Πληκτρολόγησε τη μερίδα σε γραμμάρια..">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-sm-4">Επέλεξε πρόθεμα:</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="serving_prefix" name="serving_prefix" style="width: 100%;" data-placeholder="Επέλεξε πρόθεμα...">
                                        <option value="τεμάχιο/ια">1 τεμάχιο/ια</option>
                                        <option value="φλιτζάνι/ια">1 φλιτζάνι/ια</option>
                                        <option value="κουταλιά/ες σούπας">1 κουταλιά/ες σούπας</option>
                                        <option value="κουταλάκι/ια γλυκού">1 κουταλάκι/ια γλυκού</option>
                                        <option value="μερίδα/ες">1 μερίδα/ες</option>
                                        <option value="φέτα/ες">1 φέτα/ες</option>
                                        <option value="μέτριο/ια">1 μέτριο/ια</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8 ml-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-fw fa-check"></i> Ενημέρωση
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Your Block -->
    </div>
    <!-- END Page Content -->
@endsection
@section('js_after')
<script>
    $(document).ready(function() {
        let prefix = "{{ $fooditem->serving_prefix }}"
        $('select option[value="' + prefix + '"]').attr("selected",true);
    })
</script>
@endsection
