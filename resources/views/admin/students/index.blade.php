@extends('admin.layouts.app')
@section('content')

<div class="pb-9">
  <div class="row">
    <div class="row g-3 flex-between-end mb-5" style="padding-top:0px !important">
            <div class="col-auto"> 
              <h2 class="mb-2">Học sinh</h2>
            </div>
            <div class="col-auto">
              <a href="/admin/students/import" class="btn btn-phoenix-primary me-2 mb-2 mb-sm-0" type="button">Import</a>
              <button type="button" class="btn btn-primary mb-2 mb-sm-0" data-bs-toggle="modal" data-bs-target="#create_student_modal">Thêm học sinh</button>
            </div>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <div class="card col-xl-12" > 
        
       <div class="card-body" id="products" data-list='{"valueNames":["customer","email","father","mother","total-orders","total-spent","city","last-seen","last-order","class_filter"],"page":10,"pagination":true,"filter":{"key":"class_filter"}}'>
            <div class="mb-4">
              <div class="row g-3">
                <div class="col-auto">
                  <div class="search-box">
                    <form class="position-relative"><input class="form-control search-input search" type="search" placeholder="Tìm " aria-label="Search" />
                      <span class="fas fa-search search-box-icon"></span>
                    </form>
                  </div>
                </div>
                <div class="col-auto">
                  <select class="form-select" data-list-filter aria-label="Lọc theo lớp">
                    <option value="">Tất cả lớp</option>
                    <?php foreach ($classes as $class) { ?>
                    <option value=",<?php echo $class->id; ?>,"><?php echo $class->name; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-auto d-flex align-items-center">
                  <span class="badge badge-phoenix badge-phoenix-secondary" id="student-filter-count">{{ count($students) }} học sinh</span>
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
                      <th class="sort align-middle pe-5" scope="col" data-sort="student" >Học sinh</th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="class">Lớp</th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="father">Bố</th>
                      <th class="sort align-middle pe-5" scope="col" data-sort="mother">Mẹ</th>
                      <th class="sort align-middle pe-5" scope="col">Học phí</th>
                      <th class="sort align-middle text-end pe-3" scope="col"  style="min-width:100px">Tác vụ</th>
                      
                    </tr>
                  </thead>
                  <tbody class="list" id="customers-table-body">

                    <?php 
                    foreach ($students as $student) 
                    {
                        ?>

                    <tr class="hover-actions-trigger btn-reveal-trigger position-static">
                      <td class="fs-9 align-middle ps-0 py-3">
                        <div class="form-check mb-0 fs-8"><input class="form-check-input" type="checkbox" data-bulk-select-row='{"customer":{"avatar":"/team/32.webp","name":"Carry Anna"},"email":"annac34@gmail.com","city":"Budapest","totalOrders":89,"totalSpent":23987,"lastSeen":"34 min ago","lastOrder":"Dec 12, 12:56 PM"}' /></div>
                      </td>
                      <td class="customer align-middle white-space-nowrap pe-5">
                        <a target="_blank" class="d-flex align-items-center text-body-emphasis" href="/admin/students/<?php echo $student->id; ?>">
                          <div class="avatar avatar-m"><img class="rounded-square" src="<?php echo ($student->photo_id)? getPhotoUrl($student->photo_id):"/assets/admin/trans.png"; ?>" alt="" /></div>
                          <p class="mb-0 ms-3 text-body-emphasis fw-bold"><?php echo $student->name; ?></p>
                        </a></td>
                      <td class="email align-middle white-space-nowrap pe-5">
                        @if($student->class_id)
                          <a href="/admin/classes/{{$student->class_id}}">{{$student->class_name}}</a>
                        @else
                          {{$student->class_name}}
                        @endif
                      </td>
                      <td class="father align-middle white-space-nowrap pe-5">
                        @if($student->father_id)
                          <a href="/admin/parents/{{$student->father_id}}">{{$student->father_name}}</a>
                        @else
                          {{$student->father_name}}
                        @endif
                      </td>
                      <td class="mother align-middle white-space-nowrap pe-5">
                        @if($student->mother_id)
                          <a href="/admin/parents/{{$student->mother_id}}">{{$student->mother_name}}</a>
                        @else
                          {{$student->mother_name}}
                        @endif
                      </td>
                      <td class="d-none class_filter">,{{$student->class_id}},</td>
                      <td class="align-middle pe-5">
                        <a href="/admin/students/{{$student->id}}/tuitions" class="btn btn-link text-body-quaternary p-0" title="{{ $student->has_unpaid_tuition ? 'Còn học phí chưa thanh toán' : 'Học phí' }}">
                         <span class="fas fa-money-bill-wave {{ $student->has_unpaid_tuition ? 'text-warning' : 'text-body' }}"></span>
                         @if($student->last_tuition_month)
                           <span class="ms-1">{{ sprintf('%02d', $student->last_tuition_month) }}/{{ $student->last_tuition_year }}</span>
                         @endif
                        </a>
                      </td>

                      <td class="align-middle actions  text-end pe-3">
                        <button type="button" class="btn btn-link text-body-quaternary p-0 me-2 edit-student-btn" data-student-id="{{ $student->id }}">
                         <span class="fas fa-edit text-body"></span>
                        </button>
                        <button data-bs-toggle="offcanvas" data-bs-target="#offcanvas_<?php echo $student->id; ?>" aria-controls="offcanvas_<?php echo $student->id; ?>" class="btn btn-link text-body-quaternary p-0 text-danger"> 
                          <span class="fa-solid fa-trash text-danger"></span> 
                        </button>                 
                          <div class="offcanvas offcanvas-end" id="offcanvas_<?php echo $student->id; ?>" tabindex="-1" aria-labelledby="offcanvas_<?php echo $student->id; ?>Label">
                           <div class="offcanvas-body " style="padding-top: 100px;" >
                             {{$student->name}}
                            </div>
                            <div class="offcanvas-body bottom" style="position: absolute;bottom: 0;width: 100%;">
                              Xóa bài viết này?
                              <div class="mt-3">
                                <form method="POST" action="/admin/students/<?php echo $student->id; ?>">
                                  @csrf
                                  @method('DELETE')
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Hủy</button>
                                  <button type="submit" class="btn btn-danger">Xóa bài viết <?php echo $student->id; ?></button>
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

<div class="modal fade" id="create_student_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 900px;">
    <div class="modal-content">
      <form id="create_student_form" action="{{ route('students.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Thêm học sinh</h5>
          <button type="button" class="btn btn-close p-1" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-floating">
                <input class="form-control" name="name" id="create_student_name" type="text" placeholder="Tên học sinh">
                <label for="create_student_name">Tên học sinh</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <input class="form-control" name="address" id="create_student_address" type="text" placeholder="Địa chỉ">
                <label for="create_student_address">Địa chỉ</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <select name="gender" class="form-select" id="create_student_gender">
                  <option value="" selected>Chọn giới tính</option>
                  <option value="male">Nam</option>
                  <option value="female">Nữ</option>
                </select>
                <label for="create_student_gender">Giới tính</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <input name="birthdate" class="form-control datetimepicker flatpickr-input" id="create_student_birthdate" type="text" placeholder="yyyy-mm-dd" data-options="{'enableTime':false,'dateFormat':'y-m-d','disableMobile':true}" readonly="readonly">
                <label for="create_student_birthdate">Ngày sinh</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <select name="class_id" class="form-select" id="create_student_class_id">
                  <option value="" selected>Lớp học</option>
                  <?php foreach ($classes as $class) { ?>
                  <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                  <?php } ?>
                </select>
                <label for="create_student_class_id">Lớp học</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-floating">
                <input class="form-control" name="tuition_discount" id="create_student_tuition_discount" type="number" min="0" max="100" step="1" placeholder="Giảm học phí">
                <label for="create_student_tuition_discount">Giảm học phí (%)</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-floating">
                <input class="form-control" name="tuition_discount_reason" id="create_student_tuition_discount_reason" type="text" placeholder="Lý do giảm học phí">
                <label for="create_student_tuition_discount_reason">Lý do giảm học phí</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="col-12">
              <h6 class="text-body-tertiary mb-0 mt-2">Thông tin bố</h6>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="father_name" id="create_student_father_name" type="text" placeholder="Tên">
                <label for="create_student_father_name">Tên</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="father_phone" id="create_student_father_phone" type="text" placeholder="Điện thoại">
                <label for="create_student_father_phone">Điện thoại</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="father_email" id="create_student_father_email" type="text" placeholder="Email">
                <label for="create_student_father_email">Email</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="col-12">
              <h6 class="text-body-tertiary mb-0 mt-2">Thông tin mẹ</h6>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="mother_name" id="create_student_mother_name" type="text" placeholder="Tên">
                <label for="create_student_mother_name">Tên</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="mother_phone" id="create_student_mother_phone" type="text" placeholder="Điện thoại">
                <label for="create_student_mother_phone">Điện thoại</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="mother_email" id="create_student_mother_email" type="text" placeholder="Email">
                <label for="create_student_mother_email">Email</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="col-12">
              <h6 class="text-body-tertiary mb-2 mt-2">Ảnh đại diện</h6>
              @include('admin.components.file_picker', [
                  'id' => 'create_student_photo_picker',
                  'name' => 'photo_id',
                  'label' => 'Chọn ảnh đại diện',
                  'multiple' => false,
                  'reopenModal' => 'create_student_modal',
              ])
              <div class="invalid-feedback"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-phoenix-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Thêm học sinh</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="edit_student_modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 900px;">
    <div class="modal-content">
      <form id="edit_student_form" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Sửa học sinh</h5>
          <button type="button" class="btn btn-close p-1" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-floating">
                <input class="form-control" name="name" id="edit_student_name" type="text" placeholder="Tên học sinh">
                <label for="edit_student_name">Tên học sinh</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <input class="form-control" name="address" id="edit_student_address" type="text" placeholder="Địa chỉ">
                <label for="edit_student_address">Địa chỉ</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <select name="gender" class="form-select" id="edit_student_gender">
                  <option value="" selected>Chọn giới tính</option>
                  <option value="male">Nam</option>
                  <option value="female">Nữ</option>
                </select>
                <label for="edit_student_gender">Giới tính</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <input name="birthdate" class="form-control datetimepicker flatpickr-input" id="edit_student_birthdate" type="text" placeholder="yyyy-mm-dd" data-options="{'enableTime':false,'dateFormat':'y-m-d','disableMobile':true}" readonly="readonly">
                <label for="edit_student_birthdate">Ngày sinh</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-floating">
                <select name="class_id" class="form-select" id="edit_student_class_id">
                  <option value="" selected>Lớp học</option>
                  <?php foreach ($classes as $class) { ?>
                  <option value="<?php echo $class->id; ?>"><?php echo $class->name; ?></option>
                  <?php } ?>
                </select>
                <label for="edit_student_class_id">Lớp học</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-floating">
                <input class="form-control" name="tuition_discount" id="edit_student_tuition_discount" type="number" min="0" max="100" step="1" placeholder="Giảm học phí">
                <label for="edit_student_tuition_discount">Giảm học phí (%)</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-floating">
                <input class="form-control" name="tuition_discount_reason" id="edit_student_tuition_discount_reason" type="text" placeholder="Lý do giảm học phí">
                <label for="edit_student_tuition_discount_reason">Lý do giảm học phí</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="col-12">
              <h6 class="text-body-tertiary mb-0 mt-2">Thông tin bố</h6>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="father_name" id="edit_student_father_name" type="text" placeholder="Tên">
                <label for="edit_student_father_name">Tên</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="father_phone" id="edit_student_father_phone" type="text" placeholder="Điện thoại">
                <label for="edit_student_father_phone">Điện thoại</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="father_email" id="edit_student_father_email" type="text" placeholder="Email">
                <label for="edit_student_father_email">Email</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="col-12">
              <h6 class="text-body-tertiary mb-0 mt-2">Thông tin mẹ</h6>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="mother_name" id="edit_student_mother_name" type="text" placeholder="Tên">
                <label for="edit_student_mother_name">Tên</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="mother_phone" id="edit_student_mother_phone" type="text" placeholder="Điện thoại">
                <label for="edit_student_mother_phone">Điện thoại</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-floating">
                <input class="form-control" name="mother_email" id="edit_student_mother_email" type="text" placeholder="Email">
                <label for="edit_student_mother_email">Email</label>
                <div class="invalid-feedback"></div>
              </div>
            </div>

            <div class="col-12">
              <h6 class="text-body-tertiary mb-2 mt-2">Ảnh đại diện</h6>
              @include('admin.components.file_picker', [
                  'id' => 'edit_student_photo_picker',
                  'name' => 'photo_id',
                  'label' => 'Chọn ảnh đại diện',
                  'multiple' => false,
                  'reopenModal' => 'edit_student_modal',
              ])
              <div class="invalid-feedback"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-phoenix-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  (function () {
    var container = document.getElementById('products');
    var tbody = document.getElementById('customers-table-body');
    var counter = document.getElementById('student-filter-count');
    if (!container || !tbody || !counter) return;

    var filterSelect = container.querySelector('[data-list-filter]');
    var searchInput = container.querySelector('.search-input');

    // List.js removes non-visible rows from the DOM on every filter/search/pagination
    // update (it doesn't just toggle display:none), so snapshot the row data up front
    // and count against that instead of re-querying the (possibly trimmed) live DOM.
    var rows = Array.prototype.map.call(tbody.querySelectorAll('tr'), function (row) {
      var classCell = row.querySelector('.class_filter');
      var parts = [];
      ['.customer', '.email', '.father', '.mother'].forEach(function (selector) {
        var el = row.querySelector(selector);
        if (el) parts.push(el.textContent);
      });
      return {
        classText: classCell ? classCell.textContent.toLowerCase() : '',
        searchText: parts.join(' ').toLowerCase(),
      };
    });

    function updateCount() {
      var filterValue = filterSelect ? filterSelect.value.toLowerCase() : '';
      var searchValue = searchInput ? searchInput.value.trim().toLowerCase() : '';
      var count = 0;

      rows.forEach(function (row) {
        var matchesFilter = filterValue === '' || row.classText.includes(filterValue);
        var matchesSearch = searchValue === '' || row.searchText.includes(searchValue);
        if (matchesFilter && matchesSearch) count++;
      });

      counter.textContent = count + ' học sinh';
    }

    if (filterSelect) filterSelect.addEventListener('change', updateCount);
    if (searchInput) searchInput.addEventListener('input', updateCount);
    if (searchInput) searchInput.addEventListener('keyup', updateCount);
    updateCount();
  })();
</script>

<script>
function applyStudentFormErrors($form, errors) {
    $form.find('.invalid-feedback').html('');
    $form.find('.form-control, .form-select').removeClass('is-invalid');
    $.each(errors, function (field, msgs) {
        $form.find("[name='" + field + "']")
            .addClass('is-invalid')
            .siblings('.invalid-feedback').html(msgs[0]);
    });
}

function resetStudentForm($form, pickerId) {
    $form.find('.form-control, .form-select').val('').removeClass('is-invalid');
    $form.find('.invalid-feedback').html('');
    $('#' + pickerId).trigger('picker:reset');
    var birthdateInput = $form.find('.datetimepicker')[0];
    if (birthdateInput && birthdateInput._flatpickr) {
        birthdateInput._flatpickr.clear();
    }
}

$('#create_student_modal').on('hidden.bs.modal', function () {
    resetStudentForm($('#create_student_form'), 'create_student_photo_picker');
});

$('#create_student_form').on('submit', function (e) {
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
                applyStudentFormErrors($form, xhr.responseJSON.errors);
            }
        }
    });
});

$(document).on('click', '.edit-student-btn', function () {
    var studentId = $(this).data('student-id');

    $.ajax({
        url: '{{ url('/admin/students') }}/' + studentId + '/edit',
        type: 'GET',
        dataType: 'json',
        success: function (student) {
            var $form = $('#edit_student_form');
            $form.attr('action', '{{ url('/admin/students') }}/' + studentId);
            $form.find('.invalid-feedback').html('');
            $form.find('.form-control, .form-select').removeClass('is-invalid');

            $('#edit_student_name').val(student.name);
            $('#edit_student_address').val(student.address);
            $('#edit_student_gender').val(student.gender || '');
            $('#edit_student_class_id').val(student.class_id || '');
            $('#edit_student_tuition_discount').val(student.tuition_discount);
            $('#edit_student_tuition_discount_reason').val(student.tuition_discount_reason);
            $('#edit_student_father_name').val(student.father_name);
            $('#edit_student_father_phone').val(student.father_phone);
            $('#edit_student_father_email').val(student.father_email);
            $('#edit_student_mother_name').val(student.mother_name);
            $('#edit_student_mother_phone').val(student.mother_phone);
            $('#edit_student_mother_email').val(student.mother_email);

            var birthdateInput = document.getElementById('edit_student_birthdate');
            if (birthdateInput._flatpickr) {
                birthdateInput._flatpickr.setDate(student.birthdate, true);
            } else {
                $(birthdateInput).val(student.birthdate);
            }

            $('#edit_student_photo_picker').trigger('picker:set', [
                student.photo_id ? [{ id: student.photo_id, path: student.thumbnail_path }] : []
            ]);

            $('#edit_student_modal').modal('show');
        }
    });
});

$('#edit_student_modal').on('hidden.bs.modal', function () {
    resetStudentForm($('#edit_student_form'), 'edit_student_photo_picker');
});

$('#edit_student_form').on('submit', function (e) {
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
                applyStudentFormErrors($form, xhr.responseJSON.errors);
            }
        }
    });
});
</script>

@endsection