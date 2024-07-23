@extends('frontend.master')
@section('home')
@php
    $type = App\Models\Type::find($type_id);
    $courses = App\Models\Course::where('type_id', $type_id)->get();
@endphp

<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white">{{ $type->type_name }}</h2>
            </div>
            <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="index.html">Home</a></li>
                <li>{{ $type->type_name }}</li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->

        <div class="course-wrapper mt-30px">
            <div class="row">
                @foreach ($courses as $course)
                <div class="col-lg-4 responsive-column-half">
                    <div class="course-item">
                        <img class="course__img lazy" src="{{ asset($course->course_image) }}" data-src="{{ asset($course->course_image) }}" alt="Course image">
                        <div class="course-content">
                            <div class="course-inner">
                                <h3 class="course__title"><a href="{{ url('course/'.$course->id) }}">{{ $course->course_title }}</a></h3>
                                <p class="course__meta">{{ $course->description }}</p>
                                <a href="{{ url('course/'.$course->id) }}" class="btn theme-btn theme-btn-sm theme-btn-white">View Details<i class="la la-arrow-right icon ml-1"></i></a>
                            </div>
                        </div><!-- end course-content -->
                    </div><!-- end course-item -->
                </div><!-- end col-lg-4 -->
                @endforeach
            </div><!-- end row -->
        </div><!-- end course-wrapper -->
    </div><!-- end container -->
</section><!-- end type-all-courses -->
@endsection
