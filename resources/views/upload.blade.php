<!DOCTYPE html>

<html>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<head>

    <title>Laravel 5.7 image upload example - HDTuto.com</title>

    <link rel="stylesheet" href="http://getbootstrap.com/dist/css/bootstrap.css">

</head>

  

<body>

<div class="container">
    <div class="panel panel-primary">

      

      <div class="panel-body">
        @if ($message = Session::get('success'))

        <div class="alert alert-success alert-block">

            <button type="button" class="close" data-dismiss="alert">×</button>

                <strong>{{ $message }}</strong>

        </div>
        <img src="images/{{ Session::get('image') }}">
        @endif
        @if (count($errors) > 0)

            <div class="alert alert-danger">

                <strong>Whoops!</strong> There were some problems with your input.

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif
        <div class="row">

        <div class="col-sm">
            <div class="panel-heading"><h3>GIF-Video</h3></div>
        <form action="{{ route('gif.upload.post') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div >
                <div class="col-md-10">

                    <input type="file" name="image" class="form-control">

                </div>
                   <div class="col-md-6">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </div>
        </form>
        </div>
        <div class="col-sm" >
            <div class="panel-heading"><h3>Image-Video</h3></div>
         <form action="{{ route('image_video.post') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div >
                <div class="col-md-10">

                    <input type="file" name="image2" class="form-control">

                </div>
                   <div class="col-md-6">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </div>
        </form>
        </div>
        <div class="col-sm" >
            <div class="panel-heading"><h3>Video-Upload</h3></div>
        <form action="{{ route('video_upload.post') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div>
                <div class="col-md-10">

                    <input type="file" name="video" class="form-control">

                </div>
                   <div class="col-md-6">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </div>
        </form>
    </div>
    </div>
      </div>
    </div>
</div>
       

</body>

  

</html>

