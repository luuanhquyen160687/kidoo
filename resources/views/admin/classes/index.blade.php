@extends('admin.layouts.app')
@section('content')

<div class="row">
    <div class="row g-3 flex-between-end mb-5" style="padding-top:0px !important">
            <div class="col-auto">
              <h2 class="mb-2">Lớp học</h2>
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-primary mb-2 mb-sm-0" data-bs-toggle="modal" data-bs-target="#create_class_modal">Thêm lớp học</button>
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
                    <form class="position-relative"><input class="form-control search-input search" type="search" placeholder="Tìm" aria-label="Search" />
                      <span class="fas fa-search search-box-icon"></span>
                    </form>
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
                      <th class="sort align-middle pe-5" scope="col" data-sort="customer" style="width:40%;">Tiêu đề</th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="email" style="width:20%;">Chương trình học</th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="email" style="width:20%;">Cơ sở</th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="email" style="width:20%;">Năm học</th>
                      <th class="sort align-middle text-end pe-3" scope="col"  style="min-width:100px">tác vụ</th>

                    </tr>
                  </thead>
                  <tbody class="list" id="customers-table-body">

                    <?php
                    foreach ($classes as $class)
                    {
                        ?>

                    <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                      <td class="fs-9 align-middle ps-0 py-3">
                        <div class="form-check mb-0 fs-8"><input class="form-check-input" type="checkbox" data-bulk-select-row='{"customer":{"avatar":"/team/32.webp","name":"Carry Anna"},"email":"annac34@gmail.com","city":"Budapest","totalOrders":89,"totalSpent":23987,"lastSeen":"34 min ago","lastOrder":"Dec 12, 12:56 PM"}' /></div>
                      </td>
                      <td class="customer align-middle white-space-nowrap pe-5">
                        <a target="_blank" class="d-flex align-items-center text-body-emphasis" href="/admin/classes/<?php echo $class->id; ?>">
                          <div class="avatar avatar-m"><img class="rounded-square" src="<?php echo (isset($class->thumbnail_path))? $class->thumbnail_path:"/assets/admin/trans.png"; ?>" alt="" /></div>
                          <p class="mb-0 ms-3 text-body-emphasis fw-bold"><?php echo $class->name; ?></p>
                        </a></td>
                      <td class="email align-middle white-space-nowrap pe-5"><?php echo $class->program_name; ?></td>
                      <td class="email align-middle white-space-nowrap pe-5"><?php echo $class->campus_name ?? '—'; ?></td>
                      <td class="email align-middle white-space-nowrap pe-5"><?php echo $class->year; ?></td>

                      <td class="align-middle actions  text-end pe-3">
                        <button type="button" class="btn btn-link text-body-quaternary p-0 me-2 edit-class-btn" data-class-id="<?php echo $class->id; ?>">
                         <span class="fas fa-edit text-body"></span>
                        </button>
                        <button data-bs-toggle="offcanvas" data-bs-target="#offcanvas_<?php echo $class->id; ?>" aria-controls="offcanvas_<?php echo $class->id; ?>" class="btn btn-link text-body-quaternary p-0 text-danger">
                          <span class="fa-solid fa-trash text-danger"></span>
                        </button>
                          <div class="offcanvas offcanvas-end" id="offcanvas_<?php echo $class->id; ?>" tabindex="-1" aria-labelledby="offcanvas_<?php echo $class->id; ?>Label">
                           <div class="offcanvas-body " style="padding-top: 100px;" >
                             {{$class->name}}
                            </div>
                            <div class="offcanvas-body bottom" style="position: absolute;bottom: 0;width: 100%;">
                              Xóa bài viết này?
                              <div class="mt-3">
                                <form method="POST" action="/admin/classes/<?php echo $class->id; ?>">
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

@foreach (['create' => 'Thêm lớp học', 'edit' => 'Cập nhật lớp học'] as $mode => $modalTitle)
<div class="modal fade" id="{{ $mode }}_class_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <form id="{{ $mode }}_class_form" action="{{ $mode == 'create' ? route('classes.store') : '' }}" method="POST">
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
                    <input class="form-control" name="name" id="{{ $mode }}_class_name" type="text" placeholder="Tên lớp">
                    <label for="{{ $mode }}_class_name">Tên lớp</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <select name="program_id" class="form-select program-select" id="{{ $mode }}_program_select">
                      <option value="" selected>Chọn chương trình</option>
                      <?php foreach ($programs as $level) { ?>
                      <option data-photo_id="<?php echo $level->photo_id; ?>" data-thumbnail_path="<?php echo $level->thumbnail_path; ?>" data-tuition="<?php echo $level->tuition; ?>" value="<?php echo $level->id; ?>"><?php echo $level->name; ?></option>
                      <?php } ?>
                    </select>
                    <label for="{{ $mode }}_program_select">Chương trình học</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <select name="campus_id" class="form-select" id="{{ $mode }}_campus_select">
                      <option value="" selected>Chọn cơ sở</option>
                      <?php foreach ($campuses as $campus) { ?>
                      <option value="<?php echo $campus->id; ?>"><?php echo $campus->name; ?></option>
                      <?php } ?>
                    </select>
                    <label for="{{ $mode }}_campus_select">Cơ sở</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-floating">
                    <input class="form-control" value="{{ $mode == 'create' ? date('Y') : '' }}" name="year" id="{{ $mode }}_class_year" type="text" placeholder="Năm học">
                    <label for="{{ $mode }}_class_year">Năm học</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-floating">
                    <input class="form-control" name="tuition" id="{{ $mode }}_class_tuition" type="text" placeholder="Học phí">
                    <label for="{{ $mode }}_class_tuition">Học phí căn bản</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <select name="teacher_id" class="form-select" id="{{ $mode }}_teacher_select">
                      <option value="" selected>giáo viên</option>
                      <?php foreach ($teachers as $teacher) { ?>
                      <option value="<?php echo $teacher->id; ?>"><?php echo $teacher->name; ?></option>
                      <?php } ?>
                    </select>
                    <label for="{{ $mode }}_teacher_select">Chủ nhiệm</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-12">
                  <label for="{{ $mode }}_assistant_teacher_ids" class="form-label">Giáo viên phụ trách thêm</label>
                  <select name="assistant_teacher_ids[]" class="form-select" id="{{ $mode }}_assistant_teacher_ids" multiple size="5">
                    <?php foreach ($teachers as $teacher) { ?>
                    <option value="<?php echo $teacher->id; ?>"><?php echo $teacher->name; ?></option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback"></div>
                </div>
              </div>
            </div>
            <div class="col-12 col-xl-3">
              <h5 class="mb-3">Ảnh đại diện</h5>
              @include('admin.components.file_picker', [
                  'id' => $mode.'_class_photo_picker',
                  'name' => 'photo_id',
                  'label' => 'Chọn ảnh đại diện',
                  'multiple' => false,
                  'reopenModal' => $mode.'_class_modal',
              ])
              <div class="invalid-feedback"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-phoenix-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">{{ $mode == 'create' ? 'Thêm lớp học' : 'Cập nhật' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<script>
function applyClassFormErrors($form, errors) {
    $form.find('.invalid-feedback').html('');
    $form.find('.form-control, .form-select').removeClass('is-invalid');
    $.each(errors, function (field, msgs) {
        $form.find("[name='" + field + "'], [name='" + field + "[]']")
            .addClass('is-invalid')
            .siblings('.invalid-feedback').html(msgs[0]);
    });
}

function resetClassForm($form, mode) {
    $form.find('.form-control').val('').removeClass('is-invalid');
    $form.find('.form-select').not('[multiple]').val('').removeClass('is-invalid');
    $form.find('.form-select[multiple]').val([]).removeClass('is-invalid');
    $form.find('.invalid-feedback').html('');
    $('#' + mode + '_class_photo_picker').trigger('picker:reset');
    if (mode == 'create') {
        $('#create_class_year').val('<?php echo date('Y'); ?>');
    }
}

$('.program-select').on('change', function () {
    var $form = $(this).closest('form');
    var selected = $(this).find(':selected');

    var photo_id = selected.data('photo_id');
    var thumbnail_path = selected.data('thumbnail_path');
    var tuition = selected.data('tuition');

    var pickerId = $form.attr('id').replace('_form', '_photo_picker');
    if (photo_id) {
        $('#' + pickerId).trigger('picker:set', [[{ id: photo_id, path: thumbnail_path }]]);
    } else {
        $('#' + pickerId).trigger('picker:reset');
    }
    $form.find("[name='tuition']").val(tuition);
});

$('#create_class_modal').on('hidden.bs.modal', function () {
    resetClassForm($('#create_class_form'), 'create');
});

$('#create_class_form').on('submit', function (e) {
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
                applyClassFormErrors($form, xhr.responseJSON.errors);
            }
        }
    });
});

$(document).on('click', '.edit-class-btn', function () {
    var classId = $(this).data('class-id');

    $.ajax({
        url: '{{ url('/admin/classes') }}/' + classId + '/edit',
        type: 'GET',
        dataType: 'json',
        success: function (cls) {
            var $form = $('#edit_class_form');
            $form.attr('action', '{{ url('/admin/classes') }}/' + classId);
            $form.find('.invalid-feedback').html('');
            $form.find('.form-control, .form-select').removeClass('is-invalid');

            $('#edit_class_name').val(cls.name);
            $('#edit_program_select').val(cls.program_id);
            $('#edit_campus_select').val(cls.campus_id);
            $('#edit_class_year').val(cls.year);
            $('#edit_class_tuition').val(cls.tuition);
            $('#edit_teacher_select').val(cls.teacher_id);
            $('#edit_assistant_teacher_ids').val((cls.assistant_teacher_ids || []).map(String));

            $('#edit_class_photo_picker').trigger('picker:set', [
                cls.photo_id ? [{ id: cls.photo_id, path: cls.photo_path }] : []
            ]);

            $('#edit_class_modal').modal('show');
        }
    });
});

$('#edit_class_modal').on('hidden.bs.modal', function () {
    resetClassForm($('#edit_class_form'), 'edit');
});

$('#edit_class_form').on('submit', function (e) {
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
                applyClassFormErrors($form, xhr.responseJSON.errors);
            }
        }
    });
});
</script>

@endsection
