@php
    $types = App\Models\Type::latest()->limit(6)->get();
@endphp

<section class="type-area pb-90px">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <div class="type-content-wrap">
                    <div class="section-heading">
                        <h5 class="ribbon ribbon-lg mb-2">Types</h5>
                        <h2 class="section__title">All Types</h2>
                        <span class="section-divider"></span>
                    </div><!-- end section-heading -->
                </div>
            </div><!-- end col-lg-9 -->
            <div class="col-lg-3">
                <div class="type-btn-box text-right">
 
                </div><!-- end type-btn-box-->
            </div><!-- end col-lg-3 -->
        </div><!-- end row -->
        <div class="type-wrapper mt-30px">
            <div class="row">
                @foreach ($types as $type)
                @php
                    $course = App\Models\Course::where('type_id', $type->id)->get();
                @endphp
                <div class="col-lg-4 responsive-column-half">
                    <div class="type-item">
                        <img class="type__img lazy" src="{{ asset($type->image) }}" data-src="{{ asset($type->image) }}" alt="Type image">
                        <div class="type-content">
                            <div class="type-inner">
                                <h3 class="type__title"><a href="{{ url('type/'.$type->id.'/'.$type->type_slug) }}">{{ $type->type_name }}</a></h3>
                                <p class="type__meta">{{ count($course) }} courses</p>
                                <a href="{{ url('type/'.$type->id.'/'.$type->type_slug) }}" class="btn theme-btn theme-btn-sm theme-btn-white">Explore<i class="la la-arrow-right icon ml-1"></i></a>
                                </div>
                        </div><!-- end type-content -->
                    </div><!-- end type-item -->
                </div><!-- end col-lg-4 -->
                @endforeach
            </div><!-- end row -->
        </div><!-- end type-wrapper -->
    </div><!-- end container -->
</section><!-- end type-area -->
