<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blogs') }}
        </h2>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{url('/dashboard')}}"> Create Blog</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- <x-jet-welcome /> -->
                <div class="container">
                    <h3> Blogs</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Image</th>
                                <th scope="col">Tags</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($blogs as $key=>$blog)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$blog->title}}</td>
                                <td>{{$blog->description}}</td>
                                <td>
                                    <img style="height: 100px;width: 100px;" src="{{  asset($blog->image_path)}}">
                                </td>
                                <td>{{ implode(',',$blog->tags) }}</td>
                                <td>
                                    @if($blog->user_id == Auth::user()->id )

                                    <a href="{{url('/blog/'.$blog->id.'/edit')}}" type="button">Edit</a>
                                    <form action="{{url('/blog/'.$blog->id)}}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button>Delete</button>
                                    </form>
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            var minsize = 1000; // min 1kb
            if ((value > minsize) && (value <= arg)) {
                return true;
            } else {
                return false;
            }
        });

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
                image: {
                    required: true,
                    // filesize: 100000
                },
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