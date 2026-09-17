@extends('admin.layouts.app')
@section('content')
<div class="pb-9">
<div class="row">
    <div class="row g-3 flex-between-end mb-5" style="padding-top:0px !important">
            <div class="col-auto">
              <h2 class="mb-2">Giáo viên</h2>

            </div>
            <div class="col-auto">
              <button class="btn btn-phoenix-primary me-2 mb-2 mb-sm-0" type="button">Import</button>
              <button type="button" class="btn btn-primary mb-2 mb-sm-0" data-bs-toggle="modal" data-bs-target="#create_teacher_modal">Thêm giáo viên</button>
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
                      <th class="sort align-middle pe-5" scope="col" data-sort="customer" style="width:90%;">Tiêu đề</th>
                      <th class="sort align-middle text-end pe-3" scope="col"  style="min-width:100px">tác vụ</th>

                    </tr>
                  </thead>
                  <tbody class="list" id="customers-table-body">

                    <?php
                    foreach ($teachers as $teacher)
                    {
                        ?>

                    <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                      <td class="fs-9 align-middle ps-0 py-3">
                        <div class="form-check mb-0 fs-8"><input class="form-check-input" type="checkbox" data-bulk-select-row='{"customer":{"avatar":"/team/32.webp","name":"Carry Anna"},"email":"annac34@gmail.com","city":"Budapest","totalOrders":89,"totalSpent":23987,"lastSeen":"34 min ago","lastOrder":"Dec 12, 12:56 PM"}' /></div>
                      </td>
                      <td class="customer align-middle white-space-nowrap pe-5">
                        <a target="_blank" class="d-flex align-items-center text-body-emphasis" href="/admin/teachers/<?php echo $teacher->id; ?>">
                          <div class="avatar avatar-m"><img class="rounded-square" src="<?php echo (isset($teacher->photo_id))? getPhotoUrl($teacher->photo_id):"/assets/admin/trans.png"; ?>" alt="" /></div>
                          <p class="mb-0 ms-3 text-body-emphasis fw-bold"><?php echo $teacher->name; ?></p>
                        </a></td>

                      <td class="align-middle actions  text-end pe-3">
                        <button type="button" class="btn btn-link text-body-quaternary p-0 me-2 edit-teacher-btn" data-teacher-id="<?php echo $teacher->id; ?>">
                         <span class="fas fa-edit text-body"></span>
                        </button>
                        <button data-bs-toggle="offcanvas" data-bs-target="#offcanvas_<?php echo $teacher->id; ?>" aria-controls="offcanvas_<?php echo $teacher->id; ?>" class="btn btn-link text-body-quaternary p-0 text-danger">
                          <span class="fa-solid fa-trash text-danger"></span>
                        </button>
                          <div class="offcanvas offcanvas-end" id="offcanvas_<?php echo $teacher->id; ?>" tabindex="-1" aria-labelledby="offcanvas_<?php echo $teacher->id; ?>Label">
                           <div class="offcanvas-body " style="padding-top: 100px;" >
                             {{$teacher->name}}
                            </div>
                            <div class="offcanvas-body bottom" style="position: absolute;bottom: 0;width: 100%;">
                              Xóa bài viết này?
                              <div class="mt-3">
                                <form method="POST" action="/admin/teachers/<?php echo $teacher->id; ?>">
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

@foreach (['create' => 'Thêm giáo viên', 'edit' => 'Cập nhật giáo viên'] as $mode => $modalTitle)
<div class="modal fade" id="{{ $mode }}_teacher_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <form id="{{ $mode }}_teacher_form" action="{{ $mode == 'create' ? route('teachers.store') : '' }}" method="POST">
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
                    <input class="form-control" name="name" id="{{ $mode }}_teacher_name" type="text" placeholder="Tên giáo viên">
                    <label for="{{ $mode }}_teacher_name">Tên giáo viên</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input class="form-control" name="phone" id="{{ $mode }}_teacher_phone" type="text" placeholder="Số điện thoại">
                    <label for="{{ $mode }}_teacher_phone">Số điện thoại</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <input class="form-control" name="address" id="{{ $mode }}_teacher_address" type="text" placeholder="Địa chỉ">
                    <label for="{{ $mode }}_teacher_address">Địa chỉ</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input class="form-control" name="birthday" id="{{ $mode }}_teacher_birthday" type="date" placeholder="Ngày sinh">
                    <label for="{{ $mode }}_teacher_birthday">Ngày sinh</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating">
                    <select name="campus_id" class="form-select" id="{{ $mode }}_teacher_campus_select">
                      <option value="" selected>Chọn cơ sở</option>
                      <?php foreach ($campuses as $campus) { ?>
                      <option value="<?php echo $campus->id; ?>"><?php echo $campus->name; ?></option>
                      <?php } ?>
                    </select>
                    <label for="{{ $mode }}_teacher_campus_select">Cơ sở</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input class="form-control" name="email" id="{{ $mode }}_teacher_email" type="text" placeholder="Email">
                    <label for="{{ $mode }}_teacher_email">Email cá nhân</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Email trường</label>
                  <div class="input-group">
                    <input class="form-control" name="school_email_alias" id="{{ $mode }}_school_email_input" type="text" placeholder="ten.giaovien">
                    <span class="input-group-text">{{ '@' . $app['school']->domain }}</span>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>

                @if($mode == 'create')
                <div class="col-md-6">
                  <div class="form-floating">
                    <input class="form-control" value="{{ $password }}" name="password" id="create_teacher_password" type="text" placeholder="Mật khẩu">
                    <label for="create_teacher_password">Mật khẩu</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
                @endif

                <div class="col-12">
                  <div class="form-floating">
                    <div class="quill_editor" for="{{ $mode }}_teacher_about" id="{{ $mode }}_teacher_editor"></div>
                    <input id="{{ $mode }}_teacher_about" type="hidden" name="about" value="">
                    <label style="padding-top:30px !important;left:auto !important; right:0 !important">Giới thiệu giáo viên</label>
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-xl-3">
              <div class="mb-4">
                <h5 class="mb-3">Ảnh đại diện</h5>
                @include('admin.components.file_picker', [
                    'id' => $mode.'_teacher_photo_picker',
                    'name' => 'photo_id',
                    'label' => 'Chọn ảnh đại diện',
                    'multiple' => false,
                    'reopenModal' => $mode.'_teacher_modal',
                ])
                <div class="invalid-feedback"></div>
              </div>

              <div class="card mt-5">
                <div class="card-body pt-0">
                  <div class="myfiles-action-bar mx-n4 mb-4" style="padding: 20px;">
                    <h6 class="mb-0 text-body-tertiary">Cấp quyền</h6>
                  </div>
                  <div class="{{ $mode }}-permissions">
                    <?php foreach ($permission_groups as $group) { ?>
                    <h6><?php echo $group['name']; ?> <i>(<?php echo $group['description']; ?>)</i></h6>
                    <?php foreach ($group['permissions'] as $permission) { ?>
                    <div class="form-check form-switch">
                      <input <?php echo ($permission->enable_by_default == 1) ? 'checked' : ''; ?> class="form-check-input" name="permissions[]" value="<?php echo $permission->permission_id; ?>" id="<?php echo $mode; ?>_<?php echo $permission->resource; ?>_permission" type="checkbox">
                      <label class="form-check-label" for="<?php echo $mode; ?>_<?php echo $permission->resource; ?>_permission"><?php echo $permission->permission_name; ?></label>
                    </div>
                    <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-phoenix-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">{{ $mode == 'create' ? 'Thêm giáo viên' : 'Cập nhật' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<script>
function removeVietnameseAccents(str) {
    return str
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd')
        .replace(/Đ/g, 'D');
}

$('#create_teacher_name').on('input', function () {
    let name = $(this).val();

    let emailPrefix = removeVietnameseAccents(name)
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '')
        .replace(/[^a-z0-9.]/g, '');

    $('#create_school_email_input').val(emailPrefix);
});

function applyTeacherFormErrors($form, errors) {
    $form.find('.invalid-feedback').html('');
    $form.find('.form-control, .form-select').removeClass('is-invalid');
    $.each(errors, function (field, msgs) {
        $form.find("[name='" + field + "']")
            .addClass('is-invalid')
            .siblings('.invalid-feedback').html(msgs[0]);
    });
}

function resetTeacherForm($form, mode) {
    $form.find('.form-control').not('#create_teacher_password').val('').removeClass('is-invalid');
    $form.find('.form-select').val('').removeClass('is-invalid');
    $form.find('.invalid-feedback').html('');
    $form.find('.form-check-input[type=checkbox]').each(function () {
        this.checked = this.defaultChecked;
    });
    $('#' + mode + '_teacher_photo_picker').trigger('picker:reset');
    var quill = quills[mode + '_teacher_about'];
    if (quill) quill.setText('');
}

function setTeacherAbout(mode, html) {
    var inputId = mode + '_teacher_about';
    var quill = quills[inputId];
    $('#' + inputId).val(html || '');
    if (quill) {
        quill.setContents(quill.clipboard.convert({ html: html || '' }));
    }
}

$('#create_teacher_modal').on('hidden.bs.modal', function () {
    resetTeacherForm($('#create_teacher_form'), 'create');
});

$('#create_teacher_form').on('submit', function (e) {
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
                applyTeacherFormErrors($form, xhr.responseJSON.errors);
            }
        }
    });
});

$(document).on('click', '.edit-teacher-btn', function () {
    var teacherId = $(this).data('teacher-id');

    $.ajax({
        url: '{{ url('/admin/teachers') }}/' + teacherId + '/edit',
        type: 'GET',
        dataType: 'json',
        success: function (teacher) {
            var $form = $('#edit_teacher_form');
            $form.attr('action', '{{ url('/admin/teachers') }}/' + teacherId);
            $form.find('.invalid-feedback').html('');
            $form.find('.form-control, .form-select').removeClass('is-invalid');

            $('#edit_teacher_name').val(teacher.name);
            $('#edit_teacher_phone').val(teacher.phone);
            $('#edit_teacher_address').val(teacher.address);
            $('#edit_teacher_birthday').val(teacher.birthday);
            $('#edit_teacher_campus_select').val(teacher.campus_id);
            $('#edit_teacher_email').val(teacher.email);
            $('#edit_school_email_input').val(teacher.school_email_alias);

            setTeacherAbout('edit', teacher.about);

            var grantedIds = (teacher.permission_ids || []).map(String);
            $('.edit-permissions .form-check-input[type=checkbox]').each(function () {
                $(this).prop('checked', grantedIds.indexOf(String($(this).val())) > -1);
            });

            $('#edit_teacher_photo_picker').trigger('picker:set', [
                teacher.photo_id ? [{ id: teacher.photo_id, path: teacher.photo_path }] : []
            ]);

            $('#edit_teacher_modal').modal('show');
        }
    });
});

$('#edit_teacher_modal').on('hidden.bs.modal', function () {
    resetTeacherForm($('#edit_teacher_form'), 'edit');
});

$('#edit_teacher_form').on('submit', function (e) {
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
                applyTeacherFormErrors($form, xhr.responseJSON.errors);
            }
        }
    });
});
</script>

@endsection
