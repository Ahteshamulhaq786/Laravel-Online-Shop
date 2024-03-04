@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.alert')
        <form id="category_form" autocomplete="off">
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" value="{{ $category->name }}" class="form-control" placeholder="Name">
                                <p></p>	
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email">Slug</label>
                                <input type="text" readonly name="slug" id="slug" value="{{ $category->slug }}" class="form-control" placeholder="Slug">	
                                <p></p>	
                            </div>
                        </div>	
                     
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dropzone">Image</label>
                                <div id="dropzone" class="dropzone"></div>
                            </div>
                        </div>	

                        <input type="hidden" name="image_id" id="image_id">
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label for="email">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option {{ $category->status==1 ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ $category->status==0 ? 'selected' : '' }} value="0">Block</option>    
                                </select>	
                                <p></p>	
                            </div>
                        </div>	

                        <div class="col-md-4">
                        </div>

                        <div class="col-md-2">
                            @if(!empty($category->image))
                                <img style="width:100px" src="{{ asset('uploads/category/'.$category->image) }}" alt="">
                            @endif
                           
                        </div>
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection


@push('scripts')
<script>
    Dropzone.autoDiscover = false;
    $(document).ready(function(){
   
        $("#dropzone").dropzone({ 
            init: function() {
                this.on("addedfile", function(file) {
                    if(this.files.length>1){
                        this.removeFile(this.files[0]);
                    }
                });
                this.on("success", function(files, response) {
                    if(response.status){
                        $('#image_id').val(response.image_id);
                    }
                });
            },
            url: "{{ route('admin.upload_temp_image') }}",
            method: "post",
            paramName: "image",
            acceptedFiles: "image/jpeg,image/png,image/gif",
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });

        $('#category_form').on('submit',function(e){
            e.preventDefault();
            let formData = $(this).serializeArray();
            $('button[type="submit"]').prop('disabled',true);

            $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

            $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

            $('#status').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');

            $.ajax({
                url : "{{ route('admin.categories.update',$category->id) }}",
                method : "PUT",
                data : formData,
                success : function(response){
                    $('button[type="submit"]').prop('disabled',false);
                    if(response.status){
                        window.location.href="{{ route('admin.categories.index') }}";
                    }else{
                        const errors=response.errors;
                        if(errors.name){
                            $('#name').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name);
                        }
                        if(errors.slug){
                            $('#slug').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.slug);
                        }
                        if(errors.status){
                            $('#status').addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.status);
                        }
                    }
                },
                error : function(xhr,status,error){
                    console.log("Something Went Wrong");
                }
            });
        });

        $('#name').on('change',function(){
            const keyword = $(this).val();
            $.ajax({
                url : "{{ route('getSlug') }}",
                method : "GET",
                data : { keyword : keyword },
                success : function(response){
                    if(response.status){
                        $('#slug').val(response.slug);
                    }
                },
                error : function(xhr,status,error){
                    console.log("Something Went Wrong");
                }
            });
        });
    });
</script>
@endpush
