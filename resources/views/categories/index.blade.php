@extends('layouts.backend')

@section('content')
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                <h1 class="flex-sm-fill font-size-h2 font-w400 mt-2 mb-0 mb-sm-2">Κατηγορίες Μενού</h1>
            </div>
        </div>
    </div>
    <!-- END Hero -->

    <!-- Page Content -->
    <div class="content">
        <!-- Your Block -->
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Λίστα Κατηγοριών Γευμάτων</h3>
                <div class="block-options">
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('categories.create') }}" data-toggle="tooltip" title="Create">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="block-content">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p style="margin-bottom:0;">{{ $message }}</p>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10%;">No</th>
                                <th class="d-none d-sm-table-cell" style="width: 70%;">Όνομα</th>
                                <th class="d-none d-md-table-cell text-center" style="width: 20%;">Ενέργειες</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td class="text-center">{{ ++$i }}</td>
                                <td class="d-none d-sm-table-cell">{{ $category->name }}</td>
                                <td class="text-center">
                                    <form action="{{ route('categories.destroy',$category->id) }}" method="POST">
                                        <div class="btn-group">
                                            <a class="btn btn-success" href="{{ route('categories.show',$category->id) }}" data-toggle="tooltip" title="Show">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a class="btn btn-primary" href="{{ route('categories.edit',$category->id) }}"  data-toggle="tooltip" title="Edit">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title="Delete">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-center" style="width: fit-content; margin:auto;">{!! $categories->links() !!}</div>
                </div>
            </div>
        </div>
        <!-- END Your Block -->
    </div>
    <!-- END Page Content -->
@endsection
