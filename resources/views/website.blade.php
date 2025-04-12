<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>News Records</title>
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  
  <!-- Bootstrap 4 CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  
  <!-- Bootstrap 4 JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
  
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  
  <!-- AdminLTE App -->
  <script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
  
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="page-breadcrumb mt-2">
        <div class="row d-flex justify-content-end" style="margin-right: 30px;">
            <div class="">
                
                    
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <!-- column -->
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                    <div class="alert alert-success" id="success-message" style="display: none; background-color: green; color: white;"></div>
                    <div class="alert alert-danger" id="danger-message" style="display: none; background-color: #b62a2a; color: white;"></div>


    <div class="table-responsive">
        <table class="table table-th-background" id="user_table">
            <thead>
                <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Sub-Category</th>
                <th>Status</th>
                <th>Description</th>
                <th>Created At</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($data as $news)
                <tr>
                    <td>{{ $news->id }}</td>
                    <td>{{ $news->news_title }}</td>
                    <td>{{ $news->category_title }}</td>
                    <td>{{ $news->sub_category_title }}</td>
                    <td>{{ $news->status ? 'Active' : 'Inactive' }}</td>
                    <td>{{ $news->description }}</td>
                    <td>{{ \Carbon\Carbon::parse($news->created_at)->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No News Found</td>
                </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    </div>
                </div>
            </div>
        </div>
    </div>

    </body>
</html>
