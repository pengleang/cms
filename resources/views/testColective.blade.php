<h1>Create Post </h1>


{!! Form::open(['method'=>'POST', action('PostController@index')]) !!}
<div class='form-group'>
{!! Form::label('title', 'Title' , ['class'=>'control-label'])!!}
{!! Form::text('title', null, ['class'=>'form-control'] )!!}

{!! Form::submit('Create Post', ['class'=>'btn btn-primary']) !!}
</div>
{!! Form::close() !!}
