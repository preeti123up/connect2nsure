
@extends('layouts.app')
@section('content')
    <!-- SETTINGS START -->
    <div class="w-100 d-flex">

        <x-super-admin.front-setting-sidebar :activeMenu="$activeSettingMenu" />
        <x-setting-card>
        <x-slot name="header">

        </x-slot>


        <x-slot name="buttons">
                <div class="row">

                    <div class="col-md-12 mb-2">
                           <h4>Enquiry List</h4>
                    </div>

                </div>
        </x-slot>
        <div class="table-responsive" id="table-view">
                @include($view)
            </div>
      </x-setting-card>

    </div>

     
    <!-- SETTINGS END -->
@endsection

