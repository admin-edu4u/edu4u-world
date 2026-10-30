@extends('memberLayout')
@section('style')
 <style>
    .backbtn:hover {
        background-color:white !important;
        color:black !important;
        border-color:black !important;
    }
 </style>
@endsection

@section('content')
<div class="pe-2 ps-2" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh;">
    <h1 style="text-align: center; color:#1A124E; font-size:100px;">404</h1>
    <h2 style="text-align: center; font-size:24px; font-weight:400 !important;">{{__('lang.404Desc')}}</h2>
    <button class="mt-5 backbtn" onclick="location='/'" style="border-radius: 8px; width: 173px; height: 54px; color: white; background-color: #1A124E; font-size:18px; border-color: #1A124E;">{{__('lang.backToHome')}}</button>
</div>
@endsection