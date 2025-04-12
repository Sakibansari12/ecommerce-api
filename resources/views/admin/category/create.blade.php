@extends('admin.layouts.main')
@section('content')
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="page-breadcrumb">
    <div class="row align-items-center">
        <div class="col-md-6 col-8 align-self-center">
            <h3 class="page-title mb-0 p-0">Create Category</h3>
        </div>
        <div class="col-md-6 col-4 align-self-center">
            <div class="text-end upgrade-btn">
                <a href="{{route('category.index')}}"
                    class="btn btn-secondary d-none d-md-inline-block text-white"> Category <i class="mdi me-2 mdi-format-list-bulleted"></i></a>
            </div>
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
                    <form name="Categorycreate" id="Categorycreate" method="POST" class="form-horizontal form-material mx-2" enctype="multipart/form-data">
                        @csrf
                          <div class="row mt-3">
                              <div class="col-md-6">
                                   <label>Category Title<span class="text-danger">*</span></label>
                                    <input type="text" name="category_title" id="category_title" placeholder="Enter category title" class="form-control" value="{{ old('category_title') }}">
                                    <p></p>
                              </div>
                          </div>
                          <div class="row mt-3">
                              <div class="col-md-6">
                                   <label>Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" row="6" class="form-control"  placeholder="Enter Description"></textarea>
                                    <p></p>
                              </div>
                              
                          </div>
                          
                        <div class="row mt-3">
                            <div>
                                <button type="submit" class="btn btn-secondary" id="savebutton">Submit
                                <span id="spinner_new_prompt" class="spinner-border spinner-border-sm " style="color: #fff; display: none;" role="status"  aria-hidden="true" ></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$('#Categorycreate').submit(function (event) {
    //alert(32);
    event.preventDefault();
    var formData = new FormData(this);
    
    var button_new = document.getElementById('savebutton');
    button_new.disabled = true;

    var spinner_new = document.getElementById('spinner_new_prompt');
    spinner_new.style.display = 'inline-block';
    
    $.ajax({
        url: '{{ route("category.store") }}',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: function (response) {
            if (response['status'] == true) {
                localStorage.setItem('successMessage', 'category create successfully');
                window.location.href = "{{  route('category.index') }}";
                
                button_new.disabled = false;
                spinner_new.style.display = 'none';
            } else {
                // Handle errors
                button_new.disabled = false;
                spinner_new.style.display = 'none';
                var errors = response['errors'];
                $.each(errors, function (key, value) {
                    var elementId = key.replace(/\./g, '_');
                    $('#' + elementId).next('p').addClass('text-danger').html(value[0]);
                });
            }
        },
        error: function () {
            console.log('Something went wrong');
        }
    });
});
    $(document).ready(function() {
        $('#category_title').on('input', function() {
            $('#category_title').siblings('p').removeClass('text-danger').html('');
        });
        $('#description').on('input', function() {
            $('#description').siblings('p').removeClass('text-danger').html('');
        });
    });
</script>

@endsection