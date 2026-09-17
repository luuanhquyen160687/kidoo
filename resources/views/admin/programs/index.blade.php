@extends('admin.layouts.app')
@section('content')
<div class="pb-9">
<div class="row">
    <div class="row g-3 flex-between-end mb-5" style="padding-top:0px !important">
            <div class="col-auto">
              <h2 class="mb-2">Chương trình học</h2>
           </div>
            <div class="col-auto">
              <button type="button" class="btn btn-primary mb-2 mb-sm-0" data-bs-toggle="modal" data-bs-target="#create_program_modal">Thêm chương trình</button>
            </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <div class="card col-xl-12" >

       <div class="card-body" id="products" data-list='{"valueNames":["customer","email","total-orders","total-spent","city","last-seen","last-order"],"page":10,"pagination":true}'>
            <div class="mb-4">
              <div class="row g-3">
                <div class="col-auto">
                  <div class="search-box">

                  </div>
                </div>


              </div>
            </div>
            <div class=" border-top border-bottom border-translucent position-relative top-1">
              <div class="table-responsive scrollbar-overlay mx-n1 px-1">
                <table class="table table-sm fs-9 mb-0">
                  <thead>
                    <tr>
                      <th class="white-space-nowrap fs-9 align-middle ps-0">
                        <div class="form-check mb-0 fs-8"><input class="form-check-input" id="checkbox-bulk-customers-select" type="checkbox" data-bulk-select='{"body":"customers-table-body"}' /></div>
                      </th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="customer" style="width:90%;">Tiêu đề</th>
                      <th class="sort align-middle text-end pe-3" scope="col"  style="min-width:100px">tác vụ</th>

                    </tr>
                  </thead>
                  <tbody class="list" id="customers-table-body">

                    <?php
                    foreach ($programs as $level)
                    {
                        ?>

                    <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                      <td class="fs-9 align-middle ps-0 py-3">
                        <div class="form-check mb-0 fs-8"><input class="form-check-input" type="checkbox" data-bulk-select-row='{"customer":{"avatar":"/team/32.webp","name":"Carry Anna"},"email":"annac34@gmail.com","city":"Budapest","totalOrders":89,"totalSpent":23987,"lastSeen":"34 min ago","lastOrder":"Dec 12, 12:56 PM"}' /></div>
                      </td>
                      <td class="customer align-middle white-space-nowrap pe-5">
                        <a target="_blank" class="d-flex align-items-center text-body-emphasis" href="/tin-tuc-<?php echo $level->slug; ?>">
                          <div class="avatar avatar-m"><img class="rounded-square" src="/get_photo/<?php echo $level->photo_id;?>/500" alt="" /></div>
                          <p class="mb-0 ms-3 text-body-emphasis fw-bold"><?php echo $level->name; ?></p>
                        </a></td>

                      <td class="align-middle actions  text-end pe-3">
                        <button type="button" class="btn btn-link text-body-quaternary p-0 me-2 edit-program-btn" data-program-id="<?php echo $level->id; ?>">
                         <span class="fas fa-edit text-body"></span>
                        </button>
                        <button data-bs-toggle="offcanvas" data-bs-target="#offcanvas_<?php echo $level->id; ?>" aria-controls="offcanvas_<?php echo $level->id; ?>" class="btn btn-link text-body-quaternary p-0 text-danger">
                          <span class="fa-solid fa-trash text-danger"></span>
                        </button>
                          <div class="offcanvas offcanvas-end" id="offcanvas_<?php echo $level->id; ?>" tabindex="-1" aria-labelledby="offcanvas_<?php echo $level->id; ?>Label">
                           <div class="offcanvas-body " style="padding-top: 100px;" >
                             {{$level->name}}
                            </div>
                            <div class="offcanvas-body bottom" style="position: absolute;bottom: 0;width: 100%;">
                              Xóa bài viết này?
                              <div class="mt-3">
                                <form method="POST" action="/admin/programs/<?php echo $level->id; ?>">
                                  @csrf
                                  @method('DELETE')
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Hủy</button>
                                  <button type="submit" class="btn btn-danger">Xóa bài viết</button>
                                </form>
                              </div>
                            </div>
                          </div>

                        </td>

                    </tr>

                    <?php
                    }
                    ?>

                  </tbody>
                </table>
              </div>
              <div class="row align-items-center justify-content-between py-2 pe-0 fs-9">
                <div class="col-auto d-flex">
                  <p class="mb-0 d-none d-sm-block me-3 fw-semibold text-body" data-list-info="data-list-info"></p><a class="fw-semibold" href="#!" data-list-view="*">View all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a><a class="fw-semibold d-none" href="#!" data-list-view="less">View Less<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                </div>
                <div class="col-auto d-flex"><button class="page-link" data-list-pagination="prev"><span class="fas fa-chevron-left"></span></button>
                  <ul class="mb-0 pagination"></ul><button class="page-link pe-0" data-list-pagination="next"><span class="fas fa-chevron-right"></span></button>
                </div>
              </div>
            </div>
          </div>


    </div>



</div>
</div>

@php
    $ageOptions = '';
    for ($i = 6; $i <= 72; $i++) {
        $ageOptions .= '<option value="'.$i.'">'.$i.' tháng'.(($i % 6) == 0 ? ' ('.($i / 12).' tuổi)' : '').'</option>';
    }
@endphp

@foreach (['create' => 'Thêm chương trình học', 'edit' => 'Cập nhật chương trình học'] as $mode => $modalTitle)
<div class="modal fade" id="{{ $mode }}_program_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <form id="{{ $mode }}_program_form" action="{{ $mode == 'create' ? route('programs.store') : '' }}" method="POST">
        @csrf
        @if($mode == 'edit')
          @method('PUT')
        @endif
        <div class="modal-header">
          <h5 class="modal-title">{{ $modalTitle }}</h5>
          <button type="button" class="btn btn-close p-1" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-5">
            <div class="col-12 col-xl-9">
              <div class="row g-3">
                <div class="col-md-6">
                  <div class="form-floating">
                    <input class="form-control" type="text" name="name" id="{{ $mode }}_program_name" placeholder="Tên chương trình">
                    <label for="{{ $mode }}_program_name">Tên chương trình</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input class="form-control" type="text" name="tuition" id="{{ $mode }}_program_tuition" placeholder="Học phí căn bản">
                    <label for="{{ $mode }}_program_tuition">Học phí căn bản</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input class="form-control" type="text" name="class_count" id="{{ $mode }}_program_class_count" placeholder="Số học sinh">
                    <label for="{{ $mode }}_program_class_count">Số học sinh</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-12">
                  <h6>Lứa tuổi</h6>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <select name="age_from" class="form-select" id="{{ $mode }}_program_age_from">
                      <option value="" selected>từ</option>
                      {!! $ageOptions !!}
                    </select>
                    <label for="{{ $mode }}_program_age_from">Từ</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <select name="age_to" class="form-select" id="{{ $mode }}_program_age_to">
                      <option value="" selected>đến</option>
                      {!! $ageOptions !!}
                    </select>
                    <label for="{{ $mode }}_program_age_to">Đến</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <select name="manager_id" class="form-select" id="{{ $mode }}_program_manager_id">
                      <option value="" selected>giáo viên</option>
                      <?php foreach ($teachers as $teacher) { ?>
                      <option value="<?php echo $teacher->id; ?>"><?php echo $teacher->name; ?></option>
                      <?php } ?>
                    </select>
                    <label for="{{ $mode }}_program_manager_id">Chủ nhiệm</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-12">
                  <div class="form-floating">
                    <div class="quill_editor" for="{{ $mode }}_program_introduction" id="{{ $mode }}_program_editor"></div>
                    <input id="{{ $mode }}_program_introduction" type="hidden" name="introduction" value="">
                    <label style="padding-top:30px !important;left:auto !important; right:0 !important">Giới thiệu chương trình học</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

               
              </div>
            </div>
            <div class="col-12 col-xl-3">
              <h5 class="mb-3">Ảnh đại diện</h5>
              @include('admin.components.file_picker', [
                  'id' => $mode.'_program_photo_picker',
                  'name' => 'photo_id',
                  'label' => 'Chọn ảnh đại diện',
                  'multiple' => false,
                  'reopenModal' => $mode.'_program_modal',
              ])
              <div class="invalid-feedback"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-phoenix-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">{{ $mode == 'create' ? 'Thêm chương trình học' : 'Cập nhật' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<script>
function applyProgramFormErrors($form, errors) {
    $form.find('.invalid-feedback').html('');
    $form.find('.form-control, .form-select').removeClass('is-invalid');
    $.each(errors, function (field, msgs) {
        $form.find("[name='" + field + "']")
            .addClass('is-invalid')
            .siblings('.invalid-feedback').html(msgs[0]);
    });
}

function resetProgramForm($form, mode) {
    $form.find('.form-control, .form-select').val('').removeClass('is-invalid');
    $form.find('.invalid-feedback').html('');
    $('#' + mode + '_program_photo_picker').trigger('picker:reset');
    $('#' + mode + '_program_files_picker').trigger('picker:reset');
    var quill = quills[mode + '_program_introduction'];
    if (quill) quill.setText('');
}

function setProgramIntroduction(mode, html) {
    var inputId = mode + '_program_introduction';
    var quill = quills[inputId];
    $('#' + inputId).val(html || '');
    if (quill) {
        quill.setContents(quill.clipboard.convert({ html: html || '' }));
    }
}

$('#create_program_modal').on('hidden.bs.modal', function () {
    resetProgramForm($('#create_program_form'), 'create');
});

$('#create_program_form').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);

    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: $form.serialize(),
        success: function () {
            window.location.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                applyProgramFormErrors($form, xhr.responseJSON.errors);
            }
        }
    });
});

$(document).on('click', '.edit-program-btn', function () {
    var programId = $(this).data('program-id');

    $.ajax({
        url: '{{ url('/admin/programs') }}/' + programId + '/edit',
        type: 'GET',
        dataType: 'json',
        success: function (program) {
            var $form = $('#edit_program_form');
            $form.attr('action', '{{ url('/admin/programs') }}/' + programId);
            $form.find('.invalid-feedback').html('');
            $form.find('.form-control, .form-select').removeClass('is-invalid');

            $('#edit_program_name').val(program.name);
            $('#edit_program_tuition').val(program.tuition);
            $('#edit_program_class_count').val(program.class_count);
            $('#edit_program_age_from').val(program.age_from);
            $('#edit_program_age_to').val(program.age_to);
            $('#edit_program_manager_id').val(program.manager_id);

            setProgramIntroduction('edit', program.introduction);

            $('#edit_program_photo_picker').trigger('picker:set', [
                program.photo_id ? [{ id: program.photo_id, path: program.photo_path }] : []
            ]);
            $('#edit_program_files_picker').trigger('picker:set', [program.files]);

            $('#edit_program_modal').modal('show');
        }
    });
});

$('#edit_program_modal').on('hidden.bs.modal', function () {
    resetProgramForm($('#edit_program_form'), 'edit');
});

$('#edit_program_form').on('submit', function (e) {
    e.preventDefault();
    var $form = $(this);

    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: $form.serialize(),
        success: function () {
            window.location.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                applyProgramFormErrors($form, xhr.responseJSON.errors);
            }
        }
    });
});
</script>

@endsection
