@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <router-link to="/">Home</router-link>
        <router-link to="/about">Home</router-link>
        <el-button type="primary">Default Button</el-button>
        <a class="button is-primary">White</a>
        <ui-button :size="size" color="primary">Normal</ui-button>
    </div>    
</div>
@endsection