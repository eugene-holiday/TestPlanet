@extends('layouts.main')


@section('content')
<div class="post commentable" data-id="0" data-type="Post">
    <h1>{{{$post->title}}}</h1>
    <p>{{{$post->description}}}</p>
    <span class="reply"><a href="#post">Reply <i class="fa fa-reply"></i></a></span>
</div>

<div class="comments">
    {{View::make('posts.comments', ['comments' => $post->rootComments])->render()}}
</div>

@include('comments.create')

@stop

@section('script')
    <script type="text/javascript">
        jQuery( document ).ready( function( $ ) {
            $( '#form-add-comment').hide();
            $( '.reply a' ).on( 'click', function() {
                var form = $( '#form-add-comment');
                form.find('textarea').each(function(){
                    $(this).val('');
                });
                var comment = $(this).closest('.commentable');
                form.hide().appendTo(comment).slideDown(300);
            });

            $( '#form-add-comment' ).on( 'submit', function() {

                var form = $(this);
                var parent = $(this).closest('.commentable');

                $.post(
                    $( this ).prop( 'action' ),
                    {
                        "_token": $( this ).find( 'input[name=_token]' ).val(),
                        "content": $( this ).find( 'textarea[name=content]' ).val(),
                        "commentable_id": {{$post->id}},
                        "commentable_type": "Post",
                        "parent_id": parent.data('id')

                    },
                    function( html ) {
                        console.log(html);
                        form.hide();
                        var replies = parent.children('div.replies');
                        if(parent.data('id')){
                            if(replies.length === 0){
                                replies = $('<div class="replies"></div>   ')
                                        .appendTo(parent);
                            }
                            parent.children('.replies').append(html);
                        } else{
                            $('.comments').append(html);
                            $("html, body").animate({ scrollTop: $(document).height() }, 1000);
                        }

                    }
                );

                return false;
            } );

        } );
    </script>
@stop