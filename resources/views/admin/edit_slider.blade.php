@extends('admin_layout.admin')

@section('content')
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Slider</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Slider</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Edit slider</h3>
              </div>
               @if(Session::has('message'))
               <div class="alert alert-success">
                 {{Session::get('message')}}
               </div>
              @endif

              @if ($errors->any())
                 <div class="alert alert-danger">
                     <ul>
                         @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                         @endforeach
                     </ul>
                  </div>
              @endif
              <!-- /.card-header -->
              <!-- form start -->
              {!!Form::open(['action'=>'App\Http\Controllers\SliderController@updateslider',
              'method'=>'POST', 'enctype'=>'multipart/form-data']) !!}
              {{csrf_field()}}
                <div class="card-body">
                  <div class="form-group">
                    {{Form::hidden('id', $slider->id)}}
                    {{Form::label('', 'Description one', ['for'=>'descriptionone'])}}
                    {{Form::text('description1', $slider->description1, ['class'=>'form-control', 'id'=>'description1', 'placeholder'=>'Enter Description one'])}}
                  </div>
                   <div class="form-group">
                    {{Form::label('', 'Description two', ['for'=>'desciptiontwo'])}}
                    {{Form::text('description2', $slider->description2 , ['class'=>'form-control', 'id'=>'description2', 'placeholder'=>'Enter Description two'])}}
                  </div>
              
                  <label for="exampleInputFile">Slider image</label>
                  <div class="input-group">
                    <div class="custom-file">
                      {{-- <input type="file" class="custom-file-input" id="exampleInputFile">
                      <label class="custom-file-label" for="exampleInputFile">Choose file</label> --}}
                      {{Form::file('slider_image', ['class'=>'custom-file-input', 'id'=>'exampleInputFile'])}}
                      {{Form::label('', 'Choose file', ['class'=>'custom-file-label', 'id'=>'exampleInputFile'])}}
                    </div>
                    <div class="input-group-append">
                      <span class="input-group-text">Upload</span>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <!-- <button type="submit" class="btn btn-success">Submit</button> -->
                 {{--  <input type="submit" class="btn btn-success" value="Save"> --}}
                  {{Form::submit('Update', ['class'=>'btn btn-primary'])}}
                </div>
                {!!Form::close()!!}
              {{-- </form> --}}
            </div>
            <!-- /.card --> 
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('scripts')
<!-- AdminLTE App -->
<script src="backend/dist/js/adminlte.min.js"></script>
<script>
$(function () {
  $.validator.setDefaults({
    submitHandler: function () {
      alert( "Form successful submitted!" );
    }
  });
  $('#quickForm').validate({
    rules: {
      email: {
        required: true,
        email: true,
      },
      password: {
        required: true,
        minlength: 5
      },
      terms: {
        required: true
      },
    },
    messages: {
      email: {
        required: "Please enter a email address",
        email: "Please enter a vaild email address"
      },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      terms: "Please accept our terms"
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
@endsection

