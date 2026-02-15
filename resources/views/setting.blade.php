
@extends('layouts.main_layout')

@section('content')
    @if(session('success'))
        <input type="hidden" id="success-message" value="{{ session('success') }}">
    @endif
    <div class="setting-body">
        <div class="setting-window">
            <div class="setting-header"><h3>Profile Edit</h3></div>
            <div class="setting-content">
                    <form method="POST" action="{{ url('/student/setting/update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="setting-profile">
                            <div class="sp-1">
                                <div class="pfp-box">
                                    <img src="{{ isset($student) && $student->ImagePath ? asset('temporary_student_images/' . $student->ImagePath) : '/temporary_student_images/pfp1.jpg' }}" id="mypfp">
                                </div>
                                <h1>
                                    @if(isset($student))
                                        {{ $student->FirstName }} {{ $student->MiddleInitial }} {{ $student->LastName }}
                                    @endif
                                </h1>
                                <button id="change-pfp-btn" type="button"><img src="/images/camera.png" id="cam-icn">Change Avatar</button>
                                <input type="file" name="avatar" id="avatar-input" accept="image/*" style="display:none;">
                            </div>
                            <div class="sp-2">
                                <label for="FirstName">First Name
                                    @if($errors->has('FirstName'))
                                        ({{ $errors->first('FirstName') }})
                                    @endif
                                </label>
                                <input type="text" name="FirstName" id="FirstName" value="{{ old('FirstName', $student->FirstName ?? '') }}" required>
                            </div>
                            <div class="sp-2">
                                <label for="LastName">Last Name
                                    @if($errors->has('LastName'))
                                        ({{ $errors->first('LastName') }})
                                    @endif
                                </label>
                                <input type="text" name="LastName" id="LastName" value="{{ old('LastName', $student->LastName ?? '') }}" required>
                            </div>
                            <div class="sp-2">
                                <label for="MiddleInitial">Middle Initial
                                    @if($errors->has('MiddleInitial'))
                                        ({{ $errors->first('MiddleInitial') }})
                                    @endif
                                </label>
                                <input type="text" name="MiddleInitial" id="MiddleInitial" value="{{ old('MiddleInitial', $student->MiddleInitial ?? '') }}">
                            </div>
                            <div class="sp-2">
                                <label for="gmail">Email
                                    @if($errors->has('gmail'))
                                        ({{ $errors->first('gmail') }})
                                    @endif
                                </label>
                                <input type="email" name="gmail" id="gmail" value="{{ old('gmail', $student->gmail ?? '') }}">
                            </div>
                            <div class="sp-2">
                                <label for="Password">Change Password
                                    @if($errors->has('Password'))
                                        ({{ $errors->first('Password') }})
                                    @endif
                                </label>
                                <input type="password" name="Password" id="Password" placeholder="Change Password">
                            </div>
                            <div class="sp-2">
                                <label for="Password_confirmation">Confirm Password
                                    @if($errors->has('Password_confirmation'))
                                        ({{ $errors->first('Password_confirmation') }})
                                    @endif
                                    @if($errors->has('Password'))
                                        @if(str_contains($errors->first('Password'), 'confirmation'))
                                            (Password doesn\'t match)
                                        @endif
                                    @endif
                                </label>
                                <input type="password" name="Password_confirmation" id="Password_confirmation" placeholder="Confirm Password">
                            </div>
                            <div class="sp-3">
                                <button type="submit">Save Changes</button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
@endsection
