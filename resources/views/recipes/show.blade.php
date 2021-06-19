@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Show Recipe</h1>
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Your Block -->
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Show Recipe</h3>
                <div class="block-options">
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('recipes.index') }}"> Back</a>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <div class="row justify-content-center">
                    <div class="col-xs-10 col-sm-10 col-md-10">
                        <div class="form-group row">
                            <label for="name" class="col-sm-4">Title:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="title" name="title" value="{{$recipe->title}}" readonly placeholder="Enter Title...">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="categories_id" class="col-sm-4">Category:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="categories_id" name="categories_id" value="{{$recipe->title}}" readonly placeholder="Enter Title...">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-sm-4">Description:</label>
                            <div class="col-sm-8" >
                                {!! $recipe->description !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="image" class="col-sm-4">Image:</label>
                            <div class="col-sm-8">
                                <img src="data:image/png;base64, {{ $recipe->image }}" width="100" height="100" alt="Recipe" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="add" class="col-sm-4">Food List:</label>
                            <div class="col-sm-8">
                                <table class="table table-bordered table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 30%;">Food Name</th>
                                            <th class="text-center" style="width: 30%;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($foodvalues as $foodvalue)
                                        <tr>
                                            <td>{{$foodvalue->fooditem->food_name}}</td>
                                            <td>{{$foodvalue->amount}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
