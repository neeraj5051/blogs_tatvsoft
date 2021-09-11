<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{url('all-blogs')}}">{{ __('Blogs') }} </a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- <x-jet-welcome /> -->
                <div class="container">
                    <h3>Create Blogs</h3>
                    <form method="POST" action="{{ url('blog') }}" id="blog-form" enctype="multipart/form-data">
                        @csrf
                        @if(!empty($blog))
                        <input type="hidden" name="id" value="{{$blog->id}}">
                        @endif
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Blog Title</label>
                            <input type="text" class="form-control" value="{{ !empty($blog) ? $blog->title :'' }}" placeholder="Enter Title" name="title">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Blog Description</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3"> {{ !empty($blog) ? $blog->description :'' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlFile1">Blog Image</label>
                            <input type="file" accept="image/*" class="form-control-file" name="image" id="exampleFormControlFile1">
                            @if(!empty($blog))
                            <img style="height: 200px;width: 200px;" src="{{  asset($blog->image_path)}}">
                            @endif
                        </div>
                        <!-- <div class="form-row"> -->

                        <span id="tagScope">
                            @if(!empty($blog))
                            @foreach($blog->tags as $tag)
                            <div class="form-group">
                                <label>Tags</label>

                                <input type="text" class="form-control" value="{{$tag}}" name="tags[]" placeholder="name@example.com">
                                <button type="button" class="btn btn-primary removeTag">+ Remove</button>
                            </div>
                            @endforeach
                            @else
                            <div class="form-group">
                                <label>Tags</label>
                                <input type="text" class="form-control" name="tags[]" placeholder="name@example.com">
                                <!-- <button type="button" id="firstTagRemove" class="btn btn-primary removeTag">+ Remove</button> -->
                            </div>
                            @endif
                        </span>

                        <button type="button" id="addTag" class="btn btn-primary">+ Add Tag</button>
                        <!-- </div> -->
                        <br><br><br><br><br>
                        <div class="form-group">
                            <button class="btn btn-primary">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function() {

        $("#addTag").on('click', function() {
            let tagHtml = `<div class="form-group">
                                <label>Tags</label>
                                <input type="text" class="form-control" name="tags[]" placeholder="name@example.com">
                                <button type="button" class="btn btn-primary removeTag">+ Remove</button>
                            </div>`;
            $("#tagScope").append(tagHtml)
        });

        $(document).on('click', '.removeTag', function() {

            console.log($('.removeTag').length)
            if ($('.removeTag').length > 1) {
                // ('#firstTagRemove').show();
                $(this).parent().remove();
            } else {
                $('#firstTagRemove').hide();
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $.validator.addMethod('filesize', function(value, element, arg) {
            console.log(value)
            if (element.files[0].size <= arg) {
                return true;
            } else {
                return false;
            }
        });

        let is_edit = "{{!empty($blog) ? '1':'2'}}";
        var image_val = is_edit == '1' ? {
            required: false,
            filesize: 100000
        } : {
            required: true,
            filesize: 100000
        };
        $("#blog-form").validate({
            rules: {

                title: {
                    required: true,
                    maxlength: 225
                },
                description: {
                    required: true,
                    maxlength: 65535
                },
                image: image_val,
                'tags[]': 'required'

            },
            messages: {
                image: {
                    filesize: " file size must be less than 100 KB.",
                    accept: "Please upload .jpg or .png or .pdf file of notice.",
                    required: "Please upload file."
                }
            },

            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>