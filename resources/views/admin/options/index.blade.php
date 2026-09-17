@extends('admin.layouts.app')
@section('content')
<div class="pb-9">
          <div class="row g-5">
            <div class="col-12 col-xl-12">
            



            <div class="col-sm-12 col-md-12"> 
                <div class="form-floating">
                    
                  
                        <div class="card mt-5">
                          
                          <div class="card-body pt-0 school_options">
                            <div class="myfiles-action-bar mx-n4 mb-4">
                              <h6 class="mb-0 text-body-tertiary" id="file-manager-replace-element">Thông tin nhà trường</h6>
                              <button class="btn btn-phoenix-secondary school_options_update" type="button" data-bs-toggle="modal" data-bs-target="#school_options_modal">

                                <span class="uil-setting"></span> Thay đổi
                              </button>
                            </div>
                            <div class="row gx-xxl-9" id="bulk-select-body">

                             @forelse($options_list as $item)
                             @php $option = $options->firstWhere('key', $item['key']); @endphp
                             <div class="row ">
                              <div class="col-3"><h6 class="mb-0 fw-semibold fs-9 text-body-tertiary">{{ $item['name'] }}</h6></div>
                              <div class="col-auto"><h6 class="mb-0 fw-semibold fs-9 text-body-tertiary">:</h6></div>
                              <div class="col-auto">
                                @if($item['type'] === 'photo')
                                  @if($option->data ?? null)
                                    <img src="{{ getPhotoUrl($option->data) }}" alt="{{ $item['name'] }}" style="max-height: 60px; max-width: 120px; object-fit: contain;">
                                  @endif
                                @elseif($item['type'] === 'url')
                                  @if($option->data ?? null)
                                    <a href="{{ $option->data }}" target="_blank" rel="noopener noreferrer" class="fw-semibold fs-9">
                                      @if(str_contains($item['key'], 'facebook'))
                                        <span class="fab fa-facebook text-primary me-1"></span>
                                      @elseif(str_contains($item['key'], 'youtube'))
                                        <span class="fab fa-youtube text-danger me-1"></span>
                                      @endif
                                      {{ $item['name'] }}
                                    </a>
                                  @endif
                                @else
                                  <h6 class="mb-0 fw-semibold fs-9 text-body-tertiary">{{ $option->data ?? '' }}</h6>
                                @endif
                              </div>
                             </div>
                             <hr class="my-2">
                             @empty
                             <p class="mb-0 text-body-tertiary">Chưa có cấu hình nào.</p>
                             @endforelse

                            </div>
                          </div>
                        </div>


                </div>
              </div>

              <div class="modal fade" id="school_options_modal" tabindex="-1" aria-labelledby="school_options_modal_label" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form class="ajax_form" action="{{ route('options.store') }}" method="POST">
                      @csrf
                      <div class="modal-header">
                        <h5 class="modal-title" id="school_options_modal_label">Cập nhật thông tin nhà trường</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        @foreach($options_list as $item)
                        @php $option = $options->firstWhere('key', $item['key']); @endphp
                        <div class="mb-3">
                          @if($item['type'] === 'photo')
                            <label class="form-label">{{ $item['name'] }}</label>
                            @include('admin.components.file_picker', [
                                'id' => 'option_' . $item['key'] . '_picker',
                                'name' => $item['key'],
                                'label' => 'Chọn ' . $item['name'],
                                'multiple' => false,
                                'reopenModal' => 'school_options_modal',
                                'initial' => ($option->data ?? null) ? [['id' => $option->data, 'path' => getPhotoUrl($option->data)]] : [],
                            ])
                            <div class="invalid-feedback"></div>
                          @else
                            <div class="form-floating">
                              <input class="form-control" value="{{ $option->data ?? '' }}" name="{{ $item['key'] }}" id="{{ $item['key'] }}_input" type="{{ $item['type'] }}" placeholder="{{ $item['name'] }}">
                              <label for="{{ $item['key'] }}_input">{{ $item['name'] }}</label>
                              <div class="invalid-feedback"></div>
                            </div>
                          @endif
                        </div>
                        @endforeach
                        <input type="hidden" name="redirect_url" value="/admin/options">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <div class="col-sm-12 col-md-12">
                <div class="card mt-5">
                  <div class="card-body pt-0">
                    <div class="myfiles-action-bar mx-n4 mb-4 d-flex align-items-center justify-content-between">
                      <h6 class="mb-0 text-body-tertiary">Cơ sở</h6>
                      <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#campus_create_canvas">
                        <span class="fas fa-plus me-1"></span>Thêm cơ sở
                      </button>
                    </div>
                    @if($campuses->isEmpty())
                      <p class="mb-0 text-body-tertiary">Chưa có cơ sở nào.</p>
                    @else
                      <div class="table-responsive">
                        <table class="table table-sm fs-9 mb-0">
                          <thead>
                            <tr>
                              <th>Tên cơ sở</th>
                              <th>Địa chỉ</th>
                              <th>Điện thoại</th>
                              <th class="text-end">Tác vụ</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($campuses as $campus)
                              <tr>
                                <td class="fw-semibold">{{ $campus->name }}</td>
                                <td>{{ $campus->address ?: '-' }}</td>
                                <td>{{ $campus->phone ?: '-' }}</td>
                                <td class="text-end">
                                  <button class="btn btn-link text-body p-0" type="button" data-bs-toggle="modal" data-bs-target="#campus_edit_{{ $campus->id }}" aria-label="Chỉnh sửa {{ $campus->name }}">
                                    <span class="fas fa-edit"></span>
                                  </button>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    @endif
                  </div>
                </div>
              </div>

              <div class="offcanvas offcanvas-end" id="campus_create_canvas" tabindex="-1" aria-labelledby="campus_create_label">
                <div class="offcanvas-header">
                  <h5 id="campus_create_label">Thêm cơ sở</h5>
                  <button class="btn-close" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                  <form class="ajax_form" action="{{ route('settings.campuses.store') }}" method="POST">
                    @csrf
                    @include('admin.settings.campus-form', ['campus' => null, 'submitLabel' => 'Tạo cơ sở'])
                  </form>
                </div>
              </div>

              @foreach($campuses as $campus)
                <div class="modal fade" id="campus_edit_{{ $campus->id }}" tabindex="-1" aria-labelledby="campus_edit_label_{{ $campus->id }}" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form class="ajax_form" action="{{ route('settings.campuses.update', $campus->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                          <h5 class="modal-title" id="campus_edit_label_{{ $campus->id }}">Cập nhật cơ sở</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          @include('admin.settings.campus-form', ['campus' => $campus, 'submitLabel' => 'Cập nhật', 'hideSubmit' => true])
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                          <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @endforeach

























             



<div class="offcanvas offcanvas-end" id="map_canvas" tabindex="-1" aria-labelledby="offcanvasRightLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasRightLabel">Bản đồ nhúng</h5>
                    <button class="btn-close text-reset" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                <div class="offcanvas-body">
                   <form class="ajax_form" action="/admin/settings/{{$school->id}}" class="mb-9" method="POST" >
                                @csrf
                                @method('PUT')
                                <label>Bản đồ</label>
               
              <div class="col-sm-12 col-md-12" style="margin-bottom: 10px;"> 
                 <div class="form-floating">
                  <textarea class="form-control" style="height:200px" cols="10" rows="5" name="map_embed" id="map_embed_input" type="text" placeholder="Project title">{{$school->map_embed}}</textarea>
                  <label for="map_embed_input">Bản đồ nhúng</label>
                  <div class="invalid-feedback"></div> 
                </div>
              </div>
                           


              <div class="col-sm-12 col-md-12" style="margin-bottom: 10px; padding:10px"> 
                <input type="hidden" name="redirect_url"  value="/admin/settings">
                <button  style="margin-bottom:10px !important; width:100%" class="btn btn-primary rounded-pill me-1 mb-1" type="submit">
                <span class="fas fas fa-plus" data-fa-transform="shrink-3"></span>Cập nhật</button>
                </div>

                  </form>
                </div>
              </div>
                
  
         




             

               





            
           
          </div>
          
</div>
 


@endsection



@section('js')
<script type="text/javascript">


  $(".ajax_form").on("submit", function (e) {
    e.preventDefault();        // stop full page reload
    
    var formData = new FormData(this);
    $(".invalid-feedback").html(''); 
    $("input").removeClass("is-invalid");
    $("select").removeClass("is-invalid");
    $.ajax({
        url: $(this).attr("action"),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        success: function (res) {
            window.location.href = formData.get('redirect_url'); 
        },

        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function(field, msgs) {
                    $("[name='" + field + "']").addClass("is-invalid");
                    $("[name='" + field + "']").siblings(".invalid-feedback").html(msgs);
                });
                
            }
        }
    });
});
</script>
@endsection
