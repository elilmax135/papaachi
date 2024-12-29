@extends('layouts.admin.app')

@section('content')
<div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body bg-extreme rounded-3">
                                    <div class="row">
                                      <div class="col-lg-12 col-md-12 col-12">
                                        <div class="d-lg-flex justify-content-between align-items-center ">
                                          <div class="d-md-flex align-items-center">
                                            @if(!empty($setting->profile) && file_exists(public_path('storage/' . $setting->profile)))
                                                <img src="{{ asset('storage/' . $setting->profile) }}" alt="Image" width="60px" class="rounded-circle avatar avatar-xl">
                                            @else
                                                <img src="https://raw.githubusercontent.com/abisanthm/abisanthm.github.io/main2/profile-girl.png" alt="Default Image" width="60px" class="rounded-circle border border-light border-3 avatar avatar-xl">
                                            @endif
                                            <div class="ms-md-4 mt-3">
                                              <h3 class="text-white fw-600 mb-1">Welcome, {{ auth()->user()->name }}!</h3>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
